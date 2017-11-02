<form class="signedin" method="post" action="<?= $controller->url_for('registrations/create') ?>">
  <label class=tos>
    <input type="checkbox" name="accept_tos" value="yes" required>
    <span><?= _mooc('Ich bin mit den Nutzungsbedingungen einverstanden.') ?></span>
  </label>

  <input type="hidden" name="type" value="register">
  <input type="hidden" name="moocid" value="<?= htmlReady($cid) ?>">
  <?= Studip\Button::create(_mooc('Jetzt anmelden')) ?>
</form>
