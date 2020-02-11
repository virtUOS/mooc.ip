<?php
/** @var \Mooc $plugin */

$body_id = 'mooc-courses-show';

if (class_exists('Sidebar')):
    NotificationCenter::addObserver('MoocSidebarOverview', 'addPreview', 'SidebarWillRender');

    // #TODO: find a better solution for this ugly piece of code
    $GLOBALS['mooc_widget'] = new WidgetElement($this->render_partial('courses/_show_sidebar'));

    class MoocSidebarOverview {
        function addPreview($event, $sidebar) {
            $actions = new ActionsWidget();
            $actions->setTitle(null);
            $actions->insertElement($GLOBALS['mooc_widget'], 'navigation');
            $sidebar->insertWidget($actions, ':first');
        }
    }

else:
    $infobox = $this->render_partial('courses/_show_infobox');
endif;
?>

<? if ($preliminary) : ?>
    <?= MessageBox::info(_mooc('Sie sind bereits für diesen Kurs eingetragen, allerdings können Sie auf die Kursinhalte erst zugreifen, sobald der Kurs begonnen hat!')) ?>
<? endif ?>

<? if ($course->untertitel): ?>
    <p class=subtitle><?= htmlReady($course->untertitel) ?></p>
<? endif ?>

<!--<article class=requirements>
  <h1><?= _mooc('Voraussetzungen') ?></h1>
  <p><?= formatReady($course->vorrausetzungen) ?></p>
</article>-->

<article class=description>
  <h1><?= _mooc('Kursbeschreibung') ?></h1>
  <p><?= formatReady($course->beschreibung) ?></p>
</article>

<?php
        global $perm;
        $endAdmission = strtotime("+1 day", strtotime("now"));
        
        if ($start):
            echo 'Start: '.strftime('%x', strtotime($start));
            $endAdmission = strtotime("+1 week 1 day", strtotime($start));
        endif;

        if ($duration):
            echo '<br>';
            echo 'Dauer: '.$duration.'<br>';
        endif;

        if ($hint):
            echo formatReady($hint);
        endif;
        ?>
        <br>
        <? if (!$perm->have_studip_perm('autor', $course->id) && !$preliminary && ($endAdmission > strtotime("now"))): ?>
        <?= \Studip\LinkButton::create("Zur Anmeldung", $controller->url_for('registrations/new', array('moocid' => $course->id))) ?>
        <? endif ?>


<div class=clear></div>


<?= $this->render_partial('courses/_requirejs') ?>
