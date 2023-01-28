<?php
if (Input::exists()) {
    if (Token::check()) {
        if (Input::get('action') === 'integration_settings') {
            Util::setSetting('integration_link_method', Input::get('link_method'), 'Discord Integration');

            Session::flash('integrations_success', $language->get('admin', 'integration_updated_successfully'));
            Redirect::to(URL::build('/panel/core/integrations/', 'integration=' . $integration->getName()));
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}

$smarty->assign([
    'OAUTH' => $language->get('admin', 'oauth'),
    'DISCORD_BOT' => Discord::getLanguageTerm('discord_bot'),
    'LINK_METHOD' => Discord::getLanguageTerm('link_method'),
    'LINK_METHOD_VALUE' => Util::getSetting('integration_link_method', 'bot', 'Discord Integration'),
    'SETTINGS_TEMPLATE' => 'integrations/discord/integration_settings.tpl'
]);