<?php

require_once __DIR__.'/vendor/autoload.php';

use Mooc\Container;
use Mooc\User;

function _mooc($message) {
    return dgettext('mooc', $message);
}

/**
 * MoocIP.class.php
 *
 * ...
 *
 * @author  <tgloeggl@uos.de>
 * @author  <mlunzena@uos.de>
 */
class Mooc extends StudIPPlugin implements PortalPlugin, StandardPlugin, SystemPlugin
{
    /**
     * @var Container
     */
    private $container;

    public function __construct() {
        parent::__construct();

        // set text-domain for translations in this plugin
        bindtextdomain('mooc', dirname(__FILE__) . '/locale');

        // define a short-hand function for translating text. This function is now registerd in the global scope. xD
        if (!function_exists('_mooc')) {
            function _mooc($message) {
                return dgettext('mooc', $message);
            }
        }

        // adjust host system
        $this->setupCompatibility();

        $this->setupAutoload();
        $this->setupContainer();
        $this->setupNavigation();

        // deactivate Vips-Plugin for students if this course is capture by the mooc-plugin
        if ($this->isSlotModule() && !$GLOBALS['perm']->have_studip_perm("tutor", $this->container['cid'])) {
            Navigation::removeItem('/course/vipsplugin');
        }

        if (strpos($_SERVER['REQUEST_URI'], 'dispatch.php/course/basicdata') !== false) {
            PageLayout::addHeadElement('script', array(),
                    "$(function() { $('textarea[name=course_description], textarea[name=course_requirements]').addClass('add_toolbar'); });");
        }

    }

    public function getPluginname()
    {
        return 'MOOC.IP - Open Courses';
    }

    // bei Aufruf des Plugins über plugin.php/mooc/...
    public function initialize ()
    {
        PageLayout::setTitle($_SESSION['SessSemName']['header_line'] . ' - ' . $this->getPluginname());
        $this->addStyleSheet('/assets/style.less');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabNavigation($course_id)
    {
        $tabs = array();

        if ($this->isSlotModule()) {
            $tabs['mooc_overview']   = $this->getOverviewNavigation();
        }

        return $tabs;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotificationObjects($course_id, $since, $user_id)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getIconNavigation($course_id, $last_visit, $user_id)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getInfoTemplate($course_id)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPortalTemplate()
    {
        // hide the widget if the Stud.IP version doesn't support them
        if (!class_exists('WidgetHelper')) {
            return null;
        }

        $my_cids       = $this->getCurrentUser()->course_memberships->pluck('seminar_id');
        $my_admissions = $this->getCurrentUser()->admission_applications->pluck('seminar_id');
        $dfids = $this->container['datafields'];

        $courses = array();
        foreach (\Mooc\SemClass::getMoocSemClass()->getCourses() as $course) {
            if (in_array($course->id, $my_cids) !== false || in_array($course->id, $my_admissions) !== false) {
                $datafields = array_reduce($course->datafields->toArray(), function ($memo, $elem) use ($dfids) {
                    if ($key = array_search($elem['datafield_id'], $dfids)) {
                        $memo[$key] = trim($elem['content']);
                    }
                    return $memo;
                }, array());

                if (in_array($course->id, $my_admissions) !== false) {
                    $prelim_courses[$course->id] = compact('course', 'datafields');
                } else {
                    $courses[$course->id] = compact('course', 'datafields');
                }
            }
        }

        PageLayout::addStyleSheet($this->getPluginURL().'/assets/start.css');
        PageLayout::addScript($this->getPluginURL().'/assets/js/moocip_widget.js');

        $template_factory = new Flexi_TemplateFactory(__DIR__.'/views');
        $template = $template_factory->open('start/index');
        $template->plugin = $this;
        $template->courses = $courses;
        $template->prelim_courses = $prelim_courses;
        $template->preview_images = $preview_images;
        $template->title = _mooc('Mooc.IP-Kurse');

        return $template;
    }

    public function perform($unconsumed_path)
    {
        require_once 'vendor/trails/trails.php';
        require_once 'app/controllers/studip_controller.php';
        require_once 'app/controllers/authenticated_controller.php';

        // load i18n only if plugin is un use
        PageLayout::addHeadElement('script', array(),
            "String.toLocaleString('".PluginEngine::getLink($this, array('cid' => null), "localization") ."');");

        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            NULL
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    /**
     * @return string[]
     */
    public function getDataFields()
    {
        return $this->container['datafields'];
    }

    /**
     * @return string
     */
    public function getCourseId()
    {
        return $this->container['cid'];
    }

    /**
     * @return string
     */
    public function getCurrentUserId()
    {
        return $this->container['current_user_id'];
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        return $this->container['current_user'];
    }

    /**
     * @return Pimple
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**********************************************************************/
    /* PRIVATE METHODS                                                    */
    /**********************************************************************/

    private function setupContainer()
    {
        $this->container = new Mooc\Container($this);
    }

    private function setupNavigation()
    {
        $moocid = Request::option('moocid');

        $url_overview = PluginEngine::getURL($this, array(), 'courses/overview', true);
        $url_courses = PluginEngine::getURL($this, array(), 'courses/index', true);

        $navigation = new Navigation('MOOCs', $url_overview);
        $navigation->setImage(Icon::create('category', 'clickable'));

        if (Request::get('moocid')) {
            $overview_url = PluginEngine::getURL($this, compact('moocid'), 'courses/show/' . $moocid, true);;
            $overview_subnav = new Navigation(_('Übersicht'), $overview_url);
            $navigation->addSubnavigation("overview", $overview_subnav);

            $navigation->addSubnavigation('registrations', $this->getRegistrationsNavigation());
        } else {
            $navigation->addSubnavigation("overview", new Navigation(_mooc('MOOCs'), $url_overview));
            $navigation->addSubnavigation("all", new Navigation(_mooc('Alle Kurse'), $url_courses));
        }

        Navigation::addItem('/mooc', $navigation);
    }

    private function setupAutoload()
    {
        StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
    }

    public function fixCourseNavigation()
    {
        // don't do anything if we are not in a course context
        if (!\Context::getId()) {
            return;
        }

        // don't do anything if there already is an overview item
        if (Navigation::hasItem('/course/overview') || Navigation::hasItem('/course/mooc_overview')) {
            return;
        }

        /** @var Navigation $courseNavigation */
        $courseNavigation = Navigation::getItem('/course');
        $it = $courseNavigation->getIterator();

        if (Navigation::hasItem('/course/main')) {
            Navigation::activateItem('/course/main');
        }
    }

    private function getSemClass()
    {
        global $SEM_CLASS, $SEM_TYPE;
        return $SEM_CLASS[$SEM_TYPE[Context::getArtNum()]['class']];
    }

    private function isSlotModule()
    {
        if (!$this->getSemClass()) {
            return false;
        }

        return $this->getSemClass()->isSlotModule(get_class($this));
    }


    private function getOverviewNavigation()
    {
        $cid = \Context::getId();
        $url = PluginEngine::getURL($this, compact('cid'), 'courses/show/' . $cid, true);

        $navigation = new Navigation(_('Übersicht'), $url);

        $course = Course::find($cid);
        $sem_class = self::getMoocSemClass();

        $navigation->addSubNavigation('overview', new Navigation(_('Übersicht'), $url));

        if (!$course->admission_binding && !$this->container['current_user']->hasPerm($cid, 'tutor')
                && $this->container['current_user_id'] != 'nobody') {
            $navigation->addSubNavigation('leave', new Navigation(_mooc('Austragen aus der Veranstaltung'),
                    'meine_seminare.php?auswahl='. $cid .'&cmd=suppose_to_kill'));
        }

        if ($this->container['version']->olderThan(3.3)
                && $this->container['current_user']->hasPerm($cid, 'admin')
                && !$sem_class['studygroup_mode']
                && ($sem_class->getSlotModule("admin"))) {
            $navigation->addSubNavigation('admin', new Navigation(_mooc('Administration dieser Veranstaltung'), 'adminarea_start.php?new_sem=TRUE'));
        }

        return $navigation;
    }

    private function getRegistrationsNavigation()
    {
        $moocid = Request::option('moocid');
        $url = PluginEngine::getURL($this, compact('moocid'), 'registrations/new', true);

        $navigation = new Navigation('Anmeldung', $url);

        return $navigation;
    }

    static function onEnable($id)
    {
        // enable nobody role by default
        RolePersistence::assignPluginRoles($id, array(7));

        self::insertMoocIntoOverviewSlot();
    }

    static function onDisable($id)
    {
        $res = self::removeMoocFromOverviewSlot();
    }

    const OVERVIEW_SLOT = 'overview';

    private static function insertMoocIntoOverviewSlot()
    {
        $sem_class = self::getMoocSemClass();
        $sem_class->setSlotModule(self::OVERVIEW_SLOT, __CLASS__);

        $sem_class->store();
    }

    private static function removeMoocFromOverviewSlot()
    {
        $sem_class = self::getMoocSemClass();
        $default_module = SemClass::getDefaultSemClass()->getSlotModule(self::OVERVIEW_SLOT);
        $sem_class->setSlotModule(self::OVERVIEW_SLOT, $default_module);
        $sem_class->store();
    }

    private static function getMoocSemClass()
    {
        return new SemClass(
            intval(self::getMoocSemClassID()));
    }

    private static function getMoocSemClassID()
    {
        $id = Config::get()->getValue(\Mooc\SEM_CLASS_CONFIG_ID);
        return $id;
    }

    private function setupCompatibility()
    {
        if (!class_exists('\\Metrics')) {
            require_once __DIR__ . '/models/Metrics.v3_0.php';
        }
    }
}
