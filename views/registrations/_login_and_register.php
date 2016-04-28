<form class="infobox-signin" method="post" action="<?= $controller->url_for('registrations/create') ?>">
    <input type="text" name="username" placeholder="<?= _mooc('Benutzername') ?>" required><br>
    <input type="password" name="password" placeholder="<?= _mooc('Passwort') ?>" required><br>
    <br>
    <label class=tos>
        <input type="checkbox" name="accept_tos" value="yes" required>
        <span><?= _mooc('Ich bin mit den Nutzungsbedingungen einverstanden.') ?></span>
    </label>

    <input type="hidden" name="type" value="login">
    <input type="hidden" name="moocid" value="<?= htmlReady($cid) ?>">
    <?= Studip\Button::create(_mooc('Jetzt anmelden')) ?>
</form>
