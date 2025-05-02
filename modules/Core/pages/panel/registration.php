<?php
/**
 * Staff panel registration page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

if (!$user->handlePanelPageLoad('admincp.core.registration')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'registration';
$page_title = $language->get('admin', 'registration');
require_once ROOT_PATH . '/core/templates/backend_init.php';

// Deal with input
if (Input::exists()) {
    $errors = [];

    // Check token
    if (Token::check()) {
        // Valid token
        // Process input
        if (isset($_POST['enable_registration'])) {
            // Either enable or disable registration
            Settings::set('registration_enabled', Input::get('enable_registration'));
        } else {
            // Registration settings

            // Email verification
            Settings::set('email_verification', (isset($_POST['verification']) && $_POST['verification'] == 'on') ? '1' : '0');

            // Registration disabled message
            Settings::set('registration_disabled_message', (isset($_POST['message']) && !empty($_POST['message'])) ? $_POST['message'] : 'Website registration is disabled.');

            // reCAPTCHA type
            Settings::set('recaptcha_type', Input::get('captcha_type'));

            // Validate captcha key and secret key
            if (!empty(Input::get('recaptcha_key')) || !empty(Input::get('recaptcha_secret')) || Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                CaptchaBase::setActiveProvider(Input::get('captcha_type'));

                $provider = CaptchaBase::getActiveProvider();
                if ($provider->validateSecret(Input::get('recaptcha_secret')) == false || $provider->validateKey(Input::get('recaptcha')) == false) {
                    $captcha_warning = $language->get('admin', 'invalid_recaptcha_settings', [
                        'recaptchaProvider' => Text::bold(Input::get('captcha_type'))
                    ]);
                }

                Settings::set('recaptcha_key', Input::get('recaptcha'));
                Settings::set('recaptcha_secret', Input::get('recaptcha_secret'));

            } else if (empty(Input::get('recaptcha_key')) && empty(Input::get('recaptcha_secret'))) {
                Settings::set('recaptcha_key', '');
                Settings::set('recaptcha_secret', '');
            }

            Settings::set('recaptcha', (isset($_POST['enable_recaptcha']) && $_POST['enable_recaptcha'] == '1') ? '1' : '0');
            Settings::set('recaptcha_login', (isset($_POST['enable_recaptcha_login']) && $_POST['enable_recaptcha_login'] == '1') ? '1' : '0');

            // Config value
            if (Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                if (is_writable(ROOT_PATH . '/' . implode(DIRECTORY_SEPARATOR, ['core', 'config.php']))) {
                    Config::set('core.captcha', true);
                } else {
                    $errors = [$language->get('admin', 'config_not_writable')];
                }
            }

            // Validation group
            $validation_action = json_decode(Settings::get('validate_user_action'), true);
            $new_value = json_encode(['action' => $validation_action['action'] ?? 'promote', 'group' => $_POST['promote_group']]);
            Settings::set('validate_user_action', $new_value);

            if (is_numeric(Input::get('purge_users')) && (int) Input::get('purge_users') >= 0) {
                // Purge users after x days
                Settings::set('purge_inactive_users_cutoff', Input::get('purge_users'));
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
    $template->getEngine()->addVariables([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($captcha_warning)) {
    $template->getEngine()->addVariables([
        'CAPTCHA_WARNINGS' => [$captcha_warning, $language->get('admin', 'invalid_recaptcha_settings_info')],
        'WARNING' => $language->get('general', 'warning'),
    ]);
}

if (isset($errors) && count($errors)) {
    $template->getEngine()->addVariables([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

// Check if registration is enabled
$registration_enabled = Settings::get('registration_enabled');

// Validation group
$validation_group = Settings::get('validate_user_action');
$validation_group = json_decode($validation_group, true);
$validation_group = $validation_group['group'] ?? 1;

$all_captcha_options = CaptchaBase::getAllProviders();
$captcha_options = [];
$active_option = Settings::get('recaptcha_type');
$active_option_name = $active_option ?: '';

foreach ($all_captcha_options as $option) {
    $captcha_options[] = [
        'value' => $option->getName(),
        'active' => $option->getName() == $active_option_name
    ];
}

$template->getEngine()->addVariables([
    'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
    'EMAIL_VERIFICATION_VALUE' => Settings::get('email_verification') === '1',
    'CAPTCHA_GENERAL' => $language->get('admin', 'captcha_general'),
    'CAPTCHA_GENERAL_VALUE' => Settings::get('recaptcha'),
    'CAPTCHA_LOGIN' => $language->get('admin', 'captcha_login'),
    'CAPTCHA_LOGIN_VALUE' => Settings::get('recaptcha_login'),
    'CAPTCHA_TYPE' => $language->get('admin', 'captcha_type'),
    'CAPTCHA_TYPE_VALUE' => Settings::get('recaptcha_type', 'Recaptcha2'),
    'CAPTCHA_SITE_KEY' => $language->get('admin', 'captcha_site_key'),
    'CAPTCHA_SITE_KEY_VALUE' => Output::getClean(Settings::get('recaptcha_key')),
    'CAPTCHA_SECRET_KEY' => $language->get('admin', 'captcha_secret_key'),
    'CAPTCHA_SECRET_KEY_VALUE' => Output::getClean(Settings::get('recaptcha_secret')),
    'REGISTRATION_DISABLED_MESSAGE' => $language->get('admin', 'registration_disabled_message'),
    'REGISTRATION_DISABLED_MESSAGE_VALUE' => Output::getPurified(Settings::get('registration_disabled_message')),
    'VALIDATE_PROMOTE_GROUP' => $language->get('admin', 'validation_promote_group'),
    'VALIDATE_PROMOTE_GROUP_INFO' => $language->get('admin', 'validation_promote_group_info'),
    'INFO' => $language->get('general', 'info'),
    'GROUPS' => DB::getInstance()->get('groups', ['staff', 0])->results(),
    'VALIDATION_GROUP' => $validation_group,
    'CAPTCHA_OPTIONS' => $captcha_options,
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'REGISTRATION' => $language->get('admin', 'registration'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_REGISTRATION' => $language->get('admin', 'enable_registration'),
    'REGISTRATION_ENABLED' => $registration_enabled,
    'PURGE_USERS_AFTER' => $language->get('admin', 'purge_inactive_users_after'),
    'PURGE_USERS_AFTER_INFO' => $language->get('admin', 'purge_inactive_users_info'),
    'PURGE_USERS_AFTER_VALUE' => Settings::get('purge_inactive_users_cutoff', '0'),
]);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate('core/registration');
