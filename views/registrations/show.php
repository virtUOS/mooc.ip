<?
$body_id = 'mooc-registrations-index';
?>

<h1><?= htmlReady($course->name) ?></h1>

<h2><?= _mooc('Vielen Dank für Ihre Anmeldung!') ?></h2>

<div id="messages"></div>

<b><?= _mooc('Bitte loggen Sie sich ein!') ?></b>
<br>

<?= _mooc('Wir haben soeben eine E-Mail an die angegebene Adresse geschickt, in der Ihr Passwort enthalten ist.'
        . ' Mit diesem Passwort können Sie sich nun einloggen.') ?>
<br><br>
<?= _mooc('Falls Sie die E-Mail nicht erhalten haben, können Sie sie sich erneut schicken lassen.') ?><br>

<?= Studip\LinkButton::create(_mooc('E-Mail erneut senden'), 'javascript:', array(
    'name'         => 'resend_mail',
    'data-user-id' => $user->getId(),
    'data-mooc-id' => $course->getId()
)) ?>
<br>
<br>

<form class="signin" method="post" action="<?= $controller->url_for('courses/show/'. $course->getId() .'?cid=') ?>">
    <input type="text" name="username" placeholder="<?= _mooc('Benutzername') ?>" value="<?= $user->username ?>" required><br>
    <input type="password" name="password" placeholder="<?= _mooc('Passwort') ?>" required><br>
    <?= Studip\Button::create(_mooc('Jetzt einloggen')) ?>
</form>

<?= $this->render_partial('registrations/_js_templates') ?>