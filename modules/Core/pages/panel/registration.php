<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel registration page
 */

if(!$user->handlePanelPageLoad('admincp.core.registration')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'registration');
$page_title = $language->get('admin', 'registration');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if (Input::exists()) {
    $errors = array();

    // Check token
    if (Token::check()) {
        // Valid token
        // Process input
        if (isset($_POST['enable_registration'])) {
            // Either enable or disable registration
            $enable_registration_id = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
            $enable_registration_id = $enable_registration_id[0]->id;

            $queries->update('settings', $enable_registration_id, array(
                'value' => Input::get('enable_registration')
            ));
        } else {
            // Registration settings

            // Email verification
            $verification = isset($_POST['verification']) && $_POST['verification'] == 'on' ? 1 : 0;
            $configuration->set('Core', 'email_verification', $verification);

            // reCAPTCHA enabled?
            if (Input::get('enable_recaptcha') == 1) {
                $captcha = 'true';
            } else {
                $captcha = 'false';
            }
            $captcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
            $captcha_id = $captcha_id[0]->id;
            $queries->update('settings', $captcha_id, array(
                'value' => $captcha
            ));

            // Login reCAPTCHA enabled?
            if (Input::get('enable_recaptcha_login') == 1) {
                $captcha = 'true';
            } else {
                $captcha = 'false';
            }
            $captcha_login = $queries->getWhere('settings', array('name', '=', 'recaptcha_login'));
            $captcha_login = $captcha_login[0]->id;
            $queries->update('settings', $captcha_login, array(
                'value' => $captcha
            ));

            // Config value
            if (Input::get('enable_recaptcha') == 1 || Input::get('enable_recaptcha_login') == 1) {
                if (is_writable(ROOT_PATH . '/' . join(DIRECTORY_SEPARATOR, array('core', 'config.php')))) {
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
                } else $errors = array($language->get('admin', 'config_not_writable'));
            }

            // reCAPTCHA type
            $captcha_type = $queries->getWhere('settings', array('name', '=', 'recaptcha_type'));
            if (!count($captcha_type)) {
                $queries->create('settings', array(
                    'name' => 'recaptcha_type',
                    'value' => Input::get('captcha_type')
                ));
                $cache->setCache('configuration');
                $cache->store('recaptcha_type', Input::get('captcha_type'));
            } else {
                $configuration->set('Core', 'recaptcha_type', Input::get('captcha_type'));
            }

            // reCAPTCHA key
            $configuration->set('Core', 'recaptcha_key', Input::get('recaptcha'));

            // reCAPTCHA secret key
            $configuration->set('Core', 'recaptcha_secret', Input::get('recaptcha_secret'));

            // Registration disabled message
            $registration_disabled_id = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));
            $registration_disabled_id = $registration_disabled_id[0]->id;
            $queries->update('settings', $registration_disabled_id, array(
                'value' => htmlspecialchars(Input::get('message'))
            ));

            // Validation group
            $validation_group_id = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
            $validation_action = $validation_group_id[0]->value;
            $validation_action = json_decode($validation_action, true);
            if (isset($validation_action['action']))
                $validation_action = $validation_action['action'];
            else
                $validation_action = 'promote';
            $validation_group_id = $validation_group_id[0]->id;

            $new_value = json_encode(array('action' => $validation_action, 'group' => $_POST['promote_group']));

            try {
                $queries->update('settings', $validation_group_id, array(
                    'value' => $new_value
                ));
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }

            $cache->setCache('validate_action');
            $cache->store('validate_action', array('action' => $validation_action, 'group' => $_POST['promote_group']));
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
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

// Check if registration is enabled
$registration_enabled = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
$registration_enabled = $registration_enabled[0]->value;

// Is email verification enabled
$emails = $configuration->get('Core', 'email_verification');

// Recaptcha
$captcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
$captcha_login = $queries->getWhere('settings', array('name', '=', 'recaptcha_login'));
$captcha_type = $queries->getWhere('settings', array('name', '=', 'recaptcha_type'));
$captcha_key = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
$captcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
$registration_disabled_message = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));

// Validation group
$validation_group = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
$validation_group = $validation_group[0]->value;
$validation_group = json_decode($validation_group, true);
if (isset($validation_group['group']))
    $validation_group = $validation_group['group'];
else
    $validation_group = 1;

$all_captcha_options = CaptchaBase::getAllProviders();
$captcha_options = array();
$active_option = $configuration->get('Core', 'recaptcha_type');
$active_option_name = $active_option ? $active_option : '';

foreach ($all_captcha_options as $option) {
    $captcha_options[] = array(
        'value' => $option->getName(),
        'active' => $option->getName() == $active_option_name
    );
}

$smarty->assign(array(
    'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
    'EMAIL_VERIFICATION_VALUE' => $emails,
    'CAPTCHA_GENERAL' => $language->get('admin', 'captcha_general'),
    'CAPTCHA_GENERAL_VALUE' => $captcha_id[0]->value,
    'CAPTCHA_LOGIN' => $language->get('admin', 'captcha_login'),
    'CAPTCHA_LOGIN_VALUE' => $captcha_login[0]->value,
    'CAPTCHA_TYPE' => $language->get('admin', 'captcha_type'),
    'CAPTCHA_TYPE_VALUE' => count($captcha_type) ? $captcha_type[0]->value : 'Recaptcha2',
    'CAPTCHA_SITE_KEY' => $language->get('admin', 'captcha_site_key'),
    'CAPTCHA_SITE_KEY_VALUE' => Output::getClean($captcha_key[0]->value),
    'CAPTCHA_SECRET_KEY' => $language->get('admin', 'captcha_secret_key'),
    'CAPTCHA_SECRET_KEY_VALUE' => Output::getClean($captcha_secret[0]->value),
    'REGISTRATION_DISABLED_MESSAGE' => $language->get('admin', 'registration_disabled_message'),
    'REGISTRATION_DISABLED_MESSAGE_VALUE' => Output::getPurified(Output::getDecoded($registration_disabled_message[0]->value)),
    'VALIDATE_PROMOTE_GROUP' => $language->get('admin', 'validation_promote_group'),
    'VALIDATE_PROMOTE_GROUP_INFO' => $language->get('admin', 'validation_promote_group_info'),
    'INFO' => $language->get('general', 'info'),
    'GROUPS' => $queries->getWhere('groups', array('staff', '=', 0)),
    'VALIDATION_GROUP' => $validation_group,
    'CAPTCHA_OPTIONS' => $captcha_options
));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'REGISTRATION' => $language->get('admin', 'registration'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_REGISTRATION' => $language->get('admin', 'enable_registration'),
    'REGISTRATION_ENABLED' => $registration_enabled
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/registration.tpl', $smarty);
