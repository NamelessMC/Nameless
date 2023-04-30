<?php
if (Input::exists()) {
    if (Token::check()) {
        if (Input::get('action') === 'integration_settings') {
            $premium_account = isset($_POST['premium_account']) && $_POST['premium_account'] == 'on' ? '1' : '0';
            Util::setSetting('uuid_linking', $premium_account);

            $username_registration = isset($_POST['username_registration']) && $_POST['username_registration'] == 'on' ? '1' : '0';
            Util::setSetting('mc_username_registration', $username_registration, 'Minecraft Integration');

            Session::flash('integrations_success', $language->get('admin', 'integration_updated_successfully'));
            Redirect::to(URL::build('/panel/core/integrations/', 'integration=' . $integration->getName()));
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}

$smarty->assign([
    'PREMIUM_ACCOUNTS' => $language->get('admin', 'force_premium_accounts'),
    'PREMIUM_ACCOUNTS_VALUE' => Util::getSetting('uuid_linking'),
    'REQUIRE_USERNAME_REGISTRATION' => $language->get('admin', 'require_minecraft_username_on_registration'),
    'REQUIRE_USERNAME_REGISTRATION_VALUE' => Util::getSetting('mc_username_registration', '1', 'Minecraft Integration'),
    'SETTINGS_TEMPLATE' => 'integrations/minecraft/integration_settings.tpl'
]);