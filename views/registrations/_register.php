<form class="signedin" method="post" action="<?= $controller->url_for('registrations/create') ?>">

  <input type="hidden" name="type" value="register">
  <input type="hidden" name="moocid" value="<?= htmlReady($cid) ?>">
  <?= Studip\Button::create(_mooc('Jetzt anmelden')) ?>
</form>
