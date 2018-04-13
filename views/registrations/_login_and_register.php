<form class="infobox-signin" method="post" action="<?= $controller->url_for('registrations/create') ?>">
    <input type="text" name="username" placeholder="<?= _mooc('Login') ?>" required><br>
    <input type="password" name="password" placeholder="<?= _mooc('Passwort') ?>" required><br>
    <br>
    

    <input type="hidden" name="type" value="login">
    <input type="hidden" name="moocid" value="<?= htmlReady($cid) ?>">
    <?= Studip\Button::create(_mooc('Jetzt anmelden')) ?>
</form>
