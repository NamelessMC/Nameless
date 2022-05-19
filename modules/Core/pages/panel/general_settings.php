<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel general settings page
 */

if (!$user->handlePanelPageLoad('admincp.core.general')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'general_settings';
$page_title = $language->get('admin', 'general_settings');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (isset($_GET['do'])) {
    if ($_GET['do'] == 'installLanguage') {
        // Install new language
        $languages = glob('custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*');
        foreach ($languages as $item) {
            // cursed
            $short_code = explode('.', explode(DIRECTORY_SEPARATOR, $item)[2])[0];

            // Is it already in the database?
            $exists = $queries->getWhere('languages', ['short_code', $short_code]);
            if (!count($exists)) {
                // No, add it now
                $queries->create('languages', [
                    // If they try and install a language which is not "official", default to the short code for the name
                    'name' => Language::LANGUAGES[$short_code]['name'] ?? $short_code,
                    'short_code' => $short_code
                ]);
            }
        }

        Session::flash('general_language', $language->get('admin', 'installed_languages'));
    } else {
        if ($_GET['do'] == 'updateLanguages') {
            $active_language = $queries->getWhere('languages', ['is_default', true]);
            if (count($active_language)) {
                DB::getInstance()->query('UPDATE nl2_users SET language_id = ?', [$active_language[0]->id]);
                $language = new Language('core', $active_language[0]->short_code);
            }

            Session::flash('general_language', $language->get('admin', 'updated_user_languages'));
        }
    }

    Redirect::to(URL::build('/panel/core/general_settings'));
}

// Deal with input
if (Input::exists()) {
    if (Token::check()) {
        // Validate input
        $validation = Validate::check($_POST, [
            'sitename' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 64
            ],
            'contact_email' => [
                Validate::REQUIRED => true,
                Validate::MIN => 3,
                Validate::MAX => 255
            ]
        ])->messages([
            'sitename' => $language->get('admin', 'missing_sitename'),
            'contact_email' => $language->get('admin', 'missing_contact_address')
        ]);

        if ($validation->passed()) {
            // Update settings
            // Sitename
            $queries->update('settings', ['name', 'sitename'], [
                'value' => Output::getClean(Input::get('sitename'))
            ]);

            // Update cache
            $cache->setCache('sitenamecache');
            $cache->store('sitename', Output::getClean(Input::get('sitename')));

            // Email address
            $queries->update('settings', ['name', 'incoming_email'], [
                'value' => Output::getClean(Input::get('contact_email'))
            ]);

            // Language
            // Get current default language
            $queries->update('languages', ['is_default', true], [
                'is_default' => 0
            ]);

            $language_id = $queries->getWhere('languages', ['id', Input::get('language')]);
            $language_short_code = Output::getClean($language_id[0]->short_code);
            $language_id = $language_id[0]->id;

            $queries->update('languages', $language_id, [
                'is_default' => 1
            ]);

            // Update cache
            $cache->setCache('languagecache');
            $cache->store('language', $language_short_code);

            // Timezone
            try {
                $queries->update('settings', ['name', 'timezone'], [
                    'value' => Output::getClean($_POST['timezone'])
                ]);

                // Cache
                $cache->setCache('timezone_cache');
                $cache->store('timezone', Output::getClean($_POST['timezone']));
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
            }

            // Portal
            if ($_POST['homepage'] === 'portal') {
                $home_type = 'portal';
            } else if ($_POST['homepage'] === 'news') {
                $home_type = 'news';
            } else if ($_POST['homepage'] === 'custom') {
                $home_type = 'custom';
            }

            $queries->update('settings', ['name', 'home_type'], [
                'value' => $home_type
            ]);

            // Update cache
            $cache->setCache('home_type');
            $cache->store('type', $home_type);

            // Private profile
            if ($_POST['privateProfile']) {
                $private_profile = 1;
            } else {
                $private_profile = 0;
            }

            $queries->update('settings', ['name', 'private_profile'], [
                'value' => $private_profile
            ]);

            // Registration displaynames
            $queries->update('settings', ['name', 'displaynames'], [
                'value' => $_POST['displaynames']
            ]);

            // Friendly URLs
            $friendly = Input::get('friendlyURL') == 'true';

            // Force HTTPS?
            if (Input::get('forceHTTPS') == 'true') {
                $https = true;
            } else {
                $https = false;
            }

            // Force WWW?
            if (Input::get('forceWWW') == 'true') {
                $www = true;
            } else {
                $www = false;
            }

            // Update config
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

                Config::setMultiple([
                    'core/friendly' => $friendly,
                    'core/force_https' => $https,
                    'core/force_www' => $www
                ]);
            } else {
                $errors = [$language->get('admin', 'config_not_writable')];
            }

            // Login method
            $queries->update('settings', ['name', 'login_method'], [
                'value' => $_POST['login_method']
            ]);

            Log::getInstance()->log(Log::Action('admin/core/general'));

            Session::flash('general_language', $language->get('admin', 'settings_updated_successfully'));

            // Redirect in case URL type has changed
            if (!isset($errors)) {
                if ($friendly === true) {
                    $redirect = URL::build('/panel/core/general_settings', '', 'friendly');
                } else {
                    $redirect = URL::build('/panel/core/general_settings', '', 'non-friendly');
                }
                Redirect::to($redirect);
            }
        } else {
            $errors = $validation->errors();
        }
    } else {
        // Invalid token
        $errors = [$language->get('general', 'invalid_token')];
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('general_language')) {
    $success = Session::flash('general_language');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS_TITLE' => $language->get('general', 'success'),
        'SUCCESS' => $success
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ]);
}

// Get form values
$contact_email = $queries->getWhere('settings', ['name', 'incoming_email']);
$contact_email = Output::getClean($contact_email[0]->value);

$languages = $queries->getWhere('languages', ['id', '<>', 0]);
$count = count($languages);
for ($i = 0; $i < $count; $i++) {
    $language_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', $languages[$i]->short_code . '.json']);
    if (!file_exists($language_path)) {
        unset($languages[$i]);
    }
}

$timezone = $queries->getWhere('settings', ['name', 'timezone']);
$timezone = $timezone[0]->value;

$home_type = $queries->getWhere('settings', ['name', 'home_type']);
$home_type = $home_type[0]->value;

$friendly_url = Config::get('core/friendly');

$private_profile = $queries->getWhere('settings', ['name', 'private_profile']);
$private_profile = $private_profile[0]->value;

$displaynames = $queries->getWhere('settings', ['name', 'displaynames']);
$displaynames = $displaynames[0]->value;

$method = $queries->getWhere('settings', ['name', 'login_method']);
$method = $method[0]->value;

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'GENERAL_SETTINGS' => $language->get('admin', 'general_settings'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'SITE_NAME_LABEL' => $language->get('admin', 'sitename'),
    'CONTACT_EMAIL_ADDRESS' => $language->get('admin', 'contact_email_address'),
    'CONTACT_EMAIL_ADDRESS_VALUE' => $contact_email,
    'INFO' => $language->get('general', 'info'),
    'DEFAULT_LANGUAGE' => $language->get('admin', 'default_language'),
    'DEFAULT_LANGUAGE_HELP' => $language->get('admin', 'default_language_help'),
    'DEFAULT_LANGUAGE_VALUES' => $languages,
    'INSTALL_LANGUAGE_LINK' => URL::build('/panel/core/general_settings/', 'do=installLanguage'),
    'INSTALL_LANGUAGE' => $language->get('admin', 'install_language'),
    'UPDATE_USER_LANGUAGES_LINK' => URL::build('/panel/core/general_settings/', 'do=updateLanguages'),
    'UPDATE_USER_LANGUAGES' => $language->get('admin', 'update_user_languages'),
    'UPDATE_USER_LANGUAGES_INFO' => $language->get('admin', 'update_user_languages_warning'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'DEFAULT_TIMEZONE' => $language->get('admin', 'default_timezone'),
    'DEFAULT_TIMEZONE_LIST' => Util::listTimezones(),
    'DEFAULT_TIMEZONE_VALUE' => $timezone,
    'HOMEPAGE_TYPE' => $language->get('admin', 'homepage_type'),
    'HOMEPAGE_NEWS' => $language->get('admin', 'homepage_news'),
    'HOMEPAGE_PORTAL' => $language->get('admin', 'portal'),
    'HOMEPAGE_CUSTOM' => $language->get('admin', 'homepage_custom_content'),
    'HOMEPAGE_VALUE' => $home_type,
    'USE_FRIENDLY_URLS' => $language->get('admin', 'use_friendly_urls'),
    'USE_FRIENDLY_URLS_VALUE' => $friendly_url,
    'USE_FRIENDLY_URLS_HELP' => $language->get('admin', 'use_friendly_urls_help'),
    'ENABLED' => $language->get('admin', 'enabled'),
    'DISABLED' => $language->get('admin', 'disabled'),
    'PRIVATE_PROFILES' => $language->get('admin', 'private_profiles'),
    'PRIVATE_PROFILES_VALUE' => $private_profile,
    'FORCE_HTTPS' => $language->get('admin', 'force_https'),
    'FORCE_HTTPS_VALUE' => (defined('FORCE_SSL')),
    'FORCE_HTTPS_HELP' => $language->get('admin', 'force_https_help'),
    'FORCE_WWW' => $language->get('admin', 'force_www'),
    'FORCE_WWW_VALUE' => (defined('FORCE_WWW')),
    'ENABLE_NICKNAMES' => $language->get('admin', 'enable_nicknames_on_registration'),
    'ENABLE_NICKNAMES_VALUE' => $displaynames,
    'LOGIN_METHOD' => $language->get('admin', 'login_method'),
    'LOGIN_METHOD_VALUE' => $method,
    'EMAIL' => $language->get('user', 'email'),
    'EMAIL_OR_USERNAME' => $language->get('user', 'email_or_username'),
    'USERNAME' => $language->get('user', 'username'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/general_settings.tpl', $smarty);
