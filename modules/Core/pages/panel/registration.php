<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel registration page
 */

if (!$user->handlePanelPageLoad('admincp.core.registration')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'registration';
$page_title = $language->get('admin', 'registration');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if (Input::exists()) {
    $errors = [];

    // Check token
    if (Token::check()) {
        // Valid token
        // Process input
        if (isset($_POST['enable_registration'])) {
            // Either enable or disable registration
            Util::setSetting('registration_enabled', Input::get('enable_registration'));
        } else {
            // Registration settings

            if (Input::get('action') == 'oauth') {

                foreach (array_keys(NamelessOAuth::getInstance()->getProviders()) as $provider_name) {
                    $client_id = Input::get("client-id-{$provider_name}");
                    $client_secret = Input::get("client-secret-{$provider_name}");
                    if ($client_id && $client_secret) {
                        NamelessOAuth::getInstance()->setEnabled($provider_name, Input::get("enable-{$provider_name}") == 'on' ? 1 : 0);
                    } else {
                        NamelessOAuth::getInstance()->setEnabled($provider_name, 0);
                    }

                    NamelessOAuth::getInstance()->setCredentials($provider_name, $client_id, $client_secret);
                }

            } else {
                // Email verification
                Util::setSetting('email_verification', (isset($_POST['verification']) && $_POST['verification'] == 'on') ? '1' : '0');

                // Registration disabled message
                Util::setSetting('registration_disabled_message', (isset($_POST['message']) && !empty($_POST['message'])) ? $_POST['message'] : 'Website registration is disabled.');

                // reCAPTCHA type
                Util::setSetting('recaptcha_type', Input::get('captcha_type'));

                // Validate captcha key and secret key
                if (!empty(Input::get('recaptcha_key')) || !empty(Input::get('recaptcha_secret')) || Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                    CaptchaBase::setActiveProvider(Input::get('captcha_type'));

                    $provider = CaptchaBase::getActiveProvider();
                    if ($provider->validateSecret(Input::get('recaptcha_secret')) == false || $provider->validateKey(Input::get('recaptcha')) == false) {
                        $captcha_warning = $language->get('admin', 'invalid_recaptcha_settings', [
                            'recaptchaProvider' => Text::bold(Input::get('captcha_type'))
                        ]);
                    }

                    Util::setSetting('recaptcha_key', Input::get('recaptcha'));
                    Util::setSetting('recaptcha_secret', Input::get('recaptcha_secret'));

                } else if (empty(Input::get('recaptcha_key')) && empty(Input::get('recaptcha_secret'))) {
                    Util::setSetting('recaptcha_key', '');
                    Util::setSetting('recaptcha_secret', '');
                }

                Util::setSetting('recaptcha', (isset($_POST['enable_recaptcha']) && $_POST['enable_recaptcha'] == '1') ? '1' : '0');
                Util::setSetting('recaptcha_login', (isset($_POST['enable_recaptcha_login']) && $_POST['enable_recaptcha_login'] == '1') ? '1' : '0');

                // Config value
                if (Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                    if (is_writable(ROOT_PATH . '/' . implode(DIRECTORY_SEPARATOR, ['core', 'config.php']))) {
                        Config::set('core.captcha', true);
                    } else {
                        $errors = [$language->get('admin', 'config_not_writable')];
                    }
                }

                // Validation group
                $validation_action = json_decode(Util::getSetting('validate_user_action'), true);
                $new_value = json_encode(['action' => $validation_action['action'] ?? 'promote', 'group' => $_POST['promote_group']]);
                Util::setSetting('validate_user_action', $new_value);
            }
        }

        if (!count($errors)) {
            Session::flash('registration_success', $language->get('admin', 'registration_settings_updated'));
            Redirect::to(URL::build('/panel/core/registration'));
        }
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('registration_success')) {
    $success = Session::flash('registration_success');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($captcha_warning)) {
    $smarty->assign([
        'CAPTCHA_WARNINGS' => [$captcha_warning, $language->get('admin', 'invalid_recaptcha_settings_info')],
        'WARNING' => $language->get('general', 'warning')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// Check if registration is enabled
$registration_enabled = Util::getSetting('registration_enabled');

// Validation group
$validation_group = Util::getSetting('validate_user_action');
$validation_group = json_decode($validation_group, true);
$validation_group = $validation_group['group'] ?? 1;

$all_captcha_options = CaptchaBase::getAllProviders();
$captcha_options = [];
$active_option = Util::getSetting('recaptcha_type');
$active_option_name = $active_option ?: '';

foreach ($all_captcha_options as $option) {
    $captcha_options[] = [
        'value' => $option->getName(),
        'active' => $option->getName() == $active_option_name
    ];
}

$oauth_provider_data = [];
foreach (NamelessOAuth::getInstance()->getProviders() as $provider_name => $provider_data) {
    [$client_id, $client_secret] = NamelessOAuth::getInstance()->getCredentials($provider_name);
    $oauth_provider_data[$provider_name] = [
        'enabled' => NamelessOAuth::getInstance()->isEnabled($provider_name),
        'setup' => NamelessOAuth::getInstance()->isSetup($provider_name),
        'icon' => $provider_data['icon'] ?? null,
        'logo_url' => $provider_data['logo_url'] ?? null,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    ];
}

$smarty->assign([
    'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
    'EMAIL_VERIFICATION_VALUE' => Util::getSetting('email_verification') === '1',
    'CAPTCHA_GENERAL' => $language->get('admin', 'captcha_general'),
    'CAPTCHA_GENERAL_VALUE' => Util::getSetting('recaptcha'),
    'CAPTCHA_LOGIN' => $language->get('admin', 'captcha_login'),
    'CAPTCHA_LOGIN_VALUE' => Util::getSetting('recaptcha_login'),
    'CAPTCHA_TYPE' => $language->get('admin', 'captcha_type'),
    'CAPTCHA_TYPE_VALUE' => Util::getSetting('recaptcha_type', 'Recaptcha2'),
    'CAPTCHA_SITE_KEY' => $language->get('admin', 'captcha_site_key'),
    'CAPTCHA_SITE_KEY_VALUE' => Output::getClean(Util::getSetting('recaptcha_key')),
    'CAPTCHA_SECRET_KEY' => $language->get('admin', 'captcha_secret_key'),
    'CAPTCHA_SECRET_KEY_VALUE' => Output::getClean(Util::getSetting('recaptcha_secret')),
    'REGISTRATION_DISABLED_MESSAGE' => $language->get('admin', 'registration_disabled_message'),
    'REGISTRATION_DISABLED_MESSAGE_VALUE' => Output::getPurified(Util::getSetting('registration_disabled_message')),
    'VALIDATE_PROMOTE_GROUP' => $language->get('admin', 'validation_promote_group'),
    'VALIDATE_PROMOTE_GROUP_INFO' => $language->get('admin', 'validation_promote_group_info'),
    'INFO' => $language->get('general', 'info'),
    'GROUPS' => DB::getInstance()->get('groups', ['staff', 0])->results(),
    'VALIDATION_GROUP' => $validation_group,
    'CAPTCHA_OPTIONS' => $captcha_options,
    'OAUTH' => $language->get('admin', 'oauth'),
    'OAUTH_INFO' => $language->get('admin', 'oauth_info', [
        'docLinkStart' => '<a href="https://docs.namelessmc.com/en/oauth" target="_blank">',
        'docLinkEnd' => '</a>'
    ]),
    'REDIRECT_URL' => $language->get('admin', 'redirect_url'),
    'CLIENT_ID' => $language->get('admin', 'client_id'),
    'CLIENT_SECRET' => $language->get('admin', 'client_secret'),
    'OAUTH_URL' => rtrim(URL::getSelfURL(), '/') . URL::build('/oauth', 'provider={{provider}}', 'non-friendly'),
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'REGISTRATION' => $language->get('admin', 'registration'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_REGISTRATION' => $language->get('admin', 'enable_registration'),
    'REGISTRATION_ENABLED' => $registration_enabled,
    'OAUTH_PROVIDER_DATA' => $oauth_provider_data,
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/registration.tpl', $smarty);
