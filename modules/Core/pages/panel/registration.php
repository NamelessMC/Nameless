<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
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
            DB::getInstance()->update('settings', ['name', 'registration_enabled'], [
                'value' => Input::get('enable_registration')
            ]);
        } else {
            // Registration settings

            if (Input::get('action') == 'oauth') {

                foreach (array_keys(OAuth::getInstance()->getProviders()) as $provider_name) {
                    $client_id = Input::get("client-id-{$provider_name}");
                    $client_secret = Input::get("client-secret-{$provider_name}");
                    if ($client_id && $client_secret) {
                        OAuth::getInstance()->setEnabled($provider_name, Input::get("enable-{$provider_name}") == 'on' ? 1 : 0);
                    } else {
                        OAuth::getInstance()->setEnabled($provider_name, 0);
                    }

                    OAuth::getInstance()->setCredentials($provider_name, $client_id, $client_secret);
                }

            } else {
                // Email verification
                Util::setSetting((isset($_POST['verification']) && $_POST['verification'] == 'on') ? '1' : '0')

                // Registration disabled message
                DB::getInstance()->update('settings', ['name', 'registration_disabled_message'], [
                    'value' => Output::getClean(Input::get('message'))
                ]);

                // reCAPTCHA type
                Util::setSetting('recaptcha_type', Input::get('captcha_type'));

                // Verify if the captha inputted is correct (if enabled)
                if (Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                    if (
                        (
                            CaptchaBase::isCaptchaEnabled('recaptcha_login')
                            || CaptchaBase::isCaptchaEnabled()
                        )
                        && (
                            CaptchaBase::getActiveProvider()->validateSecret(Input::get('recaptcha_secret')) == false
                            || CaptchaBase::getActiveProvider()->validateKey(Input::get('recaptcha')) == false
                        )
                    ) {
                        $errors = [$language->get('admin', 'invalid_recaptcha_settings', [
                            'recaptchaProvider' => Util::bold(Input::get('captcha_type'))
                        ])];
                    } else {
                        // reCAPTCHA enabled?
                        if (Input::get('enable_recaptcha') == 1) {
                            $captcha = 'true';
                        } else {
                            $captcha = 'false';
                        }


                        DB::getInstance()->update('settings', ['name', 'recaptcha'], [
                            'value' => $captcha
                        ]);

                        // Login reCAPTCHA enabled?
                        if (Input::get('enable_recaptcha_login') == 1) {
                            $captcha = 'true';
                        } else {
                            $captcha = 'false';
                        }

                        DB::getInstance()->update('settings', ['name', 'recaptcha_login'], [
                            'value' => $captcha
                        ]);

                        // Config value
                        if (Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                            if (is_writable(ROOT_PATH . '/' . implode(DIRECTORY_SEPARATOR, ['core', 'config.php']))) {
                                // Require config
                                if (isset($path) && file_exists($path . 'core/config.php')) {
                                    $loadedConfig = json_decode(file_get_contents($path . 'core/config.php'), true);
                                } else {
                                    $loadedConfig = json_decode(file_get_contents(ROOT_PATH . '/core/config.php'), true);
                                }

                                if (is_array($loadedConfig)) {
                                    $GLOBALS['config'] = $loadedConfig;
                                }

                                Config::set('core/captcha', true);
                            } else {
                                $errors = [$language->get('admin', 'config_not_writable')];
                            }
                        }

                        // Save settings
                        Util::setSetting('recaptcha_key', Input::get('recaptcha'));
                        Util::setSettings('recaptcha_secret', Input::get('recaptcha_secret'));
                    }
                }

                // Validation group
                $validation_action = json_decode(Util::getSetting('validate_user_action'));
                $new_value = json_encode(['action' => $validation_action['action'] ?? 'promote', 'group' => $_POST['promote_group']]);
                Util::setSetting('validate_user_action', $new_value);
            }
        }

        if (!count($errors)) {
            $success = $language->get('admin', 'registration_settings_updated');
        }
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// Check if registration is enabled
$registration_enabled = DB::getInstance()->get('settings', ['name', 'registration_enabled'])->results();
$registration_enabled = $registration_enabled[0]->value;

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
foreach (OAuth::getInstance()->getProviders() as $provider_name => $provider_data) {
    [$client_id, $client_secret] = OAuth::getInstance()->getCredentials($provider_name);
    $oauth_provider_data[$provider_name] = [
        'enabled' => OAuth::getInstance()->isEnabled($provider_name),
        'setup' => OAuth::getInstance()->isSetup($provider_name),
        'icon' => $provider_data['icon'],
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
