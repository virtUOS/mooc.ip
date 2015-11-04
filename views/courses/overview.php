<? $body_id = 'mooc-courses-overview' ?>
<? if ($root && $view == 'student') : ?>
    <?= Studip\LinkButton::create(_('Bearbeiten'), $controller->url_for('courses/overview/edit')); ?><br>
    <br>
<? endif ?>


<? if ($view == 'student') : ?>
    <?= $data ?>

<? elseif ($view == 'author') : ?>
    <form method="post" action="<?= $controller->url_for('courses/store_overview') ?>">
        <textarea class="wysiwyg add_toolbar" name="content" placeholder="Geben Sie hier Ihren Text ein"><?= htmlReady($data) ?></textarea>

        <?= \Studip\Button::createAccept('Speichern') ?>
        <?= \Studip\LinkButton::createCancel('Abbrechen', $controller->url_for('courses/overview')) ?>
    </form>
<? endif ?>
