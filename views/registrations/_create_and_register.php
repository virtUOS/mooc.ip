<?php
/** @var \Mooc $plugin */
/** @var array $fields */
/** @var array $userInput */

/** @var string $termsOfServiceUrl */
$termsOfServiceUrl = PluginEngine::getLink($plugin, array(), 'registrations/terms');
?>
<form class="signup" method="post" action="<?= $controller->url_for('registrations/create') ?>">
    <?php foreach ($fields as $field): ?>
        <?php if (is_array($field) && $field['fieldName'] === 'accept_tos'): ?>
            <input type="checkbox" name="accept_tos" id="mooc_sign_up_terms_of_service"<?= $field['required'] ? ' required' : '' ?>>
            <label for="mooc_sign_up_terms_of_service" class="tos">
                Ich erkläre mich mit den <a href="<?= $termsOfServiceUrl ?>" target="_blank">Nutzungsbedingungen</a> einverstanden.
            </label>
        <?php elseif (is_array($field)): ?>
            <label for="mooc_sign_up_<?= $field['fieldName'] ?>"<?= $field['required'] ? ' class="required"' : '' ?>>
                <?= $field['label'] ?>
                <?php if ($field['required']): ?>
                    *
                <?php endif ?>
            </label>
            <input type="text"
                name="<?= $field['fieldName'] ?>"
                id="mooc_sign_up_<?= $field['fieldName'] ?>"
                placeholder="<?= $field['label'] ?>"
                value="<?= htmlReady($userInput[$field['fieldName']]) ?>"<?= $field['required'] ? ' required' : '' ?>>
        <?php else: ?>
            <span class="mooc_registration_form_text"><?= $field ?></span>
        <?php endif ?>
    <?php endforeach ?>

    <br>

    <input type="hidden" name="type" value="create">
    <input type="hidden" name="moocid" value="<?= htmlReady($cid) ?>">
    <?= Studip\Button::create(_('Jetzt anmelden')) ?>
</form>
