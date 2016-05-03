<? if($GLOBALS['user']->id && $GLOBALS['user']->id != 'nobody'): ?>
<section id="mooc-course-list">
<? foreach ($courses as $data) : ?>
    <article data-cid="<?= $data['course']->id ?>">
        <img class="course-avatar-medium course-<?= $data['course']->id ?>"
             alt="<?= $name = htmlReady($data['course']->name) ?>"
             title="<?= $name ?>"
             src="<?= $data['datafields']['preview_image'] ?: CourseAvatar::getAvatar($data['course']->id)->getURL(CourseAvatar::MEDIUM) ?>" />

        <div class="description">
            <h1><?= $name ?></h1>

            <? if ($untertitel = trim($data['course']->untertitel)) : ?>
                <p class=subtitle><?= htmlReady($untertitel) ?></p>
            <? endif ?>

            <? if ($data['datafields']['duration']) : ?>
                <div class=duration>Dauer: <?= $data['datafields']['duration'] ?></div>
            <? endif ?>


            <div class="controls">
                <?= \Studip\LinkButton::create(_mooc('Kurs anzeigen'),
                                               PluginEngine::getLink($plugin,
                                                                     array('cid' => $data['course']->id),
                                                                     'courses/show/'.$data['course']->id,
                                                                     true)) ?>

                <a class="kill"
                   href="<?= \URLHelper::getLink("dispatch.php/my_courses/decline/{$data['course']->id}",
                                                 array(), true)  ?>">
                    <?= _mooc("Mitgliedschaft beenden") ?>
                </a>
            </div>
        </div>

    </article>
<? endforeach ?>


<? /* TODO: DRY!!! */ ?>
<? if ($prelim_courses): ?>
<? foreach ($prelim_courses as $data) : ?>
    <article data-cid="<?= $data['course']->id ?>">
        <img class="course-avatar-medium course-<?= $data['course']->id ?>"
             alt="<?= $name = htmlReady($data['course']->name) ?>"
             title="<?= $name ?>"
             src="<?= $data['datafields']['preview_image'] ?: CourseAvatar::getAvatar($data['course']->id)->getURL(CourseAvatar::MEDIUM) ?>" />

        <div class="description">
            <h1><?= $name ?></h1>

            <? if ($untertitel = trim($data['course']->untertitel)) : ?>
                <p class=subtitle><?= htmlReady($untertitel) ?></p>
            <? endif ?>

            <? if ($data['datafields']['duration']) : ?>
                <div class=duration>Dauer: <?= $data['datafields']['duration'] ?></div>
            <? endif ?>


            <div class="controls">
                <?= \Studip\LinkButton::create(_mooc('Kurs anzeigen'),
                                               PluginEngine::getLink($plugin,
                                                                     array(),
                                                                     'courses/show/'.$data['course']->id,
                                                                     true)) ?>

                <a class="kill"
                   href="<?= \URLHelper::getLink("dispatch.php/my_courses/decline/{$data['course']->id}",
                                                 array(), true)  ?>">
                    <?= _mooc("Mitgliedschaft beenden") ?>
                </a>
            </div>
        </div>

    </article>
<? endforeach ?>
<? endif ?>

  <div class="empty">
    <p><?= _mooc("Sie sind noch in keinem Mooc-Kurs eingetragen. ") ?></p>
  </div>

</section>

<?= \Studip\LinkButton::createEnroll('Für weiteren Kurs registrieren',
                               PluginEngine::getURL($plugin, array('cid' => null), 'courses/index')) ?>
<? endif ?>