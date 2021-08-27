<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel general settings page
 */

if(!$user->handlePanelPageLoad('admincp.core.general')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'general_settings');
$page_title = $language->get('admin', 'general_settings');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (isset($_GET['do'])) {
    if ($_GET['do'] == 'installLanguage') {
        // Install new language
        $languages = glob(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        foreach ($languages as $item) {
            if (file_exists($item . DIRECTORY_SEPARATOR . 'version.php')) {
                $folders = explode(DIRECTORY_SEPARATOR, $item);
                $folder_name = $folders[count($folders) - 1];

                // Is it already in the database?
                $exists = $queries->getWhere('languages', array('name', '=', Output::getClean($folder_name)));
                if (!count($exists)) {
                    // No, add it now
                    $queries->create('languages', array(
                        'name' => Output::getClean($folder_name)
                    ));
                }
            }
        }

        Session::flash('general_language', $language->get('admin', 'installed_languages'));
    } else if ($_GET['do'] == 'updateLanguages') {
        $active_language = $queries->getWhere('languages', array('is_default', '=', 1));
        if (count($active_language)) {
            DB::getInstance()->createQuery('UPDATE nl2_users SET language_id = ?', array($active_language[0]->id));
            $language = new Language('core', $active_language[0]->name);
        }

        Session::flash('general_language', $language->get('admin', 'updated_user_languages'));
    }

    Redirect::to(URL::build('/panel/core/general_settings'));
    die();
}

// Deal with input
if (Input::exists()) {
    if (Token::check()) {
        // Validate input
        $validate = new Validate();

        $validation = $validate->check($_POST, [
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
            $sitename_id = $queries->getWhere('settings', array('name', '=', 'sitename'));
            $sitename_id = $sitename_id[0]->id;

            $queries->update('settings', $sitename_id, array(
                'value' => Output::getClean(Input::get('sitename'))
            ));

            // Update cache
            $cache->setCache('sitenamecache');
            $cache->store('sitename', Output::getClean(Input::get('sitename')));

            // Email address
            $contact_id = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
            $contact_id = $contact_id[0]->id;

            $queries->update('settings', $contact_id, array(
                'value' => Output::getClean(Input::get('contact_email'))
            ));

            // Language
            // Get current default language
            $default_language = $queries->getWhere('languages', array('is_default', '=', 1));
            $default_language = $default_language[0];

            if ($default_language->name != Input::get('language')) {
                // The default language has been changed
                $queries->update('languages', $default_language->id, array(
                    'is_default' => 0
                ));

                $language_id = $queries->getWhere('languages', array('id', '=', Input::get('language')));
                $language_name = Output::getClean($language_id[0]->name);
                $language_id = $language_id[0]->id;

                $queries->update('languages', $language_id, array(
                    'is_default' => 1
                ));

                // Update cache
                $cache->setCache('languagecache');
                $cache->store('language', $language_name);
            }

            // Timezone
            $timezone_id = $queries->getWhere('settings', array('name', '=', 'timezone'));
            $timezone_id = $timezone_id[0]->id;

            try {
                $queries->update('settings', $timezone_id, array(
                    'value' => Output::getClean($_POST['timezone'])
                ));

                // Cache
                $cache->setCache('timezone_cache');
                $cache->store('timezone', Output::getClean($_POST['timezone']));
            } catch (Exception $e) {
                $errors = array($e->getMessage());
            }

            // Portal
            $portal_id = $queries->getWhere('settings', array('name', '=', 'portal'));
            $portal_id = $portal_id[0]->id;

            if ($_POST['homepage'] == 'portal') {
                $use_portal = 1;
            } else $use_portal = 0;

            $queries->update('settings', $portal_id, array(
                'value' => $use_portal
            ));

            // Update cache
            $cache->setCache('portal_cache');
            $cache->store('portal', $use_portal);

            // Private profile
            $private_profile_id = $queries->getWhere('settings', array('name', '=', 'private_profile'));
            $private_profile_id = $private_profile_id[0]->id;

            if ($_POST['privateProfile'])
                $private_profile = 1;
            else
                $private_profile = 0;

            $queries->update('settings', $private_profile_id, array(
                'value' => $private_profile
            ));

            // Registration displaynames
            $displaynames_id = $queries->getWhere('settings', array('name', '=', 'displaynames'));
            $displaynames_id = $displaynames_id[0]->id;

            $queries->update('settings', $displaynames_id, array(
                'value' => $_POST['displaynames']
            ));

            // Post formatting
            $formatting_id = $queries->getWhere('settings', array('name', '=', 'formatting_type'));
            $formatting_id = $formatting_id[0]->id;

            $queries->update('settings', $formatting_id, array(
                'value' => Output::getClean(Input::get('formatting'))
            ));

            // Update cache
            $cache->setCache('post_formatting');
            $cache->store('formatting', Output::getClean(Input::get('formatting')));

            // Friendly URLs
            if (Input::get('friendlyURL') == 'true') $friendly = true;
            else $friendly = false;

            // Force HTTPS?
            if (Input::get('forceHTTPS') == 'true') $https = true;
            else $https = false;

            // Force WWW?
            if (Input::get('forceWWW') == 'true') $www = true;
            else $www = false;

            // Update config
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

                Config::setMultiple(array(
                    'core/friendly' => $friendly,
                    'core/force_https' => $https,
                    'core/force_www' => $www
                ));
            } else $errors = array($language->get('admin', 'config_not_writable'));

            /*
            if(!empty($_POST["allowedProxies"])) {
                $allowedProxies = $_POST["allowedProxies"];
                $allowedProxies = str_replace("\r", "", $allowedProxies);
                $allowedProxies = preg_replace('/\s+/', ' ', $allowedProxies);
                $allowedProxies = str_replace(" ", "", $allowedProxies);

                Config::set("allowedProxies", $allowedProxies);
            }else {
                Config::set("allowedProxies", "");
            }
            */

            // Login method
            $login_method_id = $queries->getWhere('settings', array('name', '=', 'login_method'));
            $login_method_id = $login_method_id[0]->id;

            $queries->update('settings', $login_method_id, array(
                'value' => $_POST['login_method']
            ));

            Log::getInstance()->log(Log::Action('admin/core/general'));

            Session::flash('general_language', $language->get('admin', 'settings_updated_successfully'));

            // Redirect in case URL type has changed
            if (!isset($errors)) {
                if ($friendly == 'true') {
                    $redirect = URL::build('/panel/core/general_settings', '', 'friendly');
                } else {
                    $redirect = URL::build('/panel/core/general_settings', '', 'non-friendly');
                }
                Redirect::to($redirect);
                die();
            }
        } else {
            $errors = $validation->errors();
        }
    } else {
        // Invalid token
        $errors = array($language->get('general', 'invalid_token'));
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('general_language'))
    $success = Session::flash('general_language');

if (isset($success)) {
    $smarty->assign(array(
        'SUCCESS_TITLE' => $language->get('general', 'success'),
        'SUCCESS' => $success
    ));
}

if (isset($errors) && count($errors)) {
    $smarty->assign(array(
        'ERRORS_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ));
}

// Get form values
$contact_email = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
$contact_email = Output::getClean($contact_email[0]->value);

$languages = $queries->getWhere('languages', array('id', '<>', 0));
$count = count($languages);

for ($i = 0; $i < $count; $i++) {
    $language_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'languages', $languages[$i]->name, 'version.php'));
    if (!file_exists($language_path))
        unset($languages[$i]);
}

$timezone = $queries->getWhere('settings', array('name', '=', 'timezone'));
$timezone = $timezone[0]->value;

$portal = $queries->getWhere('settings', array('name', '=', 'portal'));
$portal = $portal[0]->value;

$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

$friendly_url = Config::get('core/friendly');

$private_profile = $queries->getWhere('settings', array('name', '=', 'private_profile'));
$private_profile = $private_profile[0]->value;

$displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$displaynames = $displaynames[0]->value;

$method = $queries->getWhere('settings', array('name', '=', 'login_method'));
$method = $method[0]->value;

$smarty->assign(array(
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
    'HOMEPAGE_DEFAULT' => $language->get('admin', 'default'),
    'HOMEPAGE_PORTAL' => $language->get('admin', 'portal'),
    'HOMEPAGE_VALUE' => $portal,
    'POST_FORMATTING' => $language->get('admin', 'post_formatting_type'),
    'POST_FORMATTING_VALUE' => $formatting,
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
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/general_settings.tpl', $smarty);
