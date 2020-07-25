<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel Discord page
 */

// Can the user view the panel?
if ($user->isLoggedIn()) {
    if (!$user->canViewACP()) {
        // No
        Redirect::to(URL::build('/'));
        die();
    }
    if (!$user->isAdmLoggedIn()) {
        // Needs to authenticate
        Redirect::to(URL::build('/panel/auth'));
        die();
    } else {
        if (!$user->hasPermission('admincp.discord')) {
            require_once(ROOT_PATH . '/403.php');
            die();
        }
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'discord');
$page_title = $language->get('admin', 'discord');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    // Check token
    if (Token::check(Input::get('token'))) {
        // Valid token
        // Process input
        if (isset($_POST['enable_discord'])) {
            // Either enable or disable Minecraft integration
            $enable_discord_id = $queries->getWhere('settings', array('name', '=', 'discord_integration'))[0]->id;

            $queries->update('settings', $enable_discord_id, array(
                'value' => Input::get('enable_discord')
            ));
        }
        else if (isset($_POST['guild_id'])) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'guild_id' => array(
                    'required' => true,
                    'min' => 18,
                    'max' => 18,
                    'numeric' => true
                ),
                'bot_url' => array(
                    'required' => true,
                    'min' => 10,
                    'max' => 2048
                )
            ));

            if ($validation->passed()) {
                $discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
                $discord_id = $discord_id[0]->id;
                $queries->update('settings', $discord_id, array(
                    'value' => Input::get('guild_id')
                ));
                $bot_url = $queries->getWhere('settings', array('name', '=', 'discord_bot_url'));
                $bot_url = $bot_url[0]->id;
                $queries->update('settings', $bot_url, array(
                    'value' => Output::getClean(Input::get('bot_url'))
                ));
                $success = $language->get('admin', 'discord_settings_updated');
            } else {
                // Validation errors
                foreach ($validation->errors() as $validation_error) {
                    if (strpos($validation_error, 'minimum') !== false || strpos($validation_error, 'maximum') !== false) {
                        $errors[] = $language->get('admin', 'discord_id_length');
                    } else if (strpos($validation_error, 'numeric') !== false) {
                        $errors[] = $language->get('admin', 'discord_id_numeric');
                    } else if (strpos($validation_error, 'required') !== false) {
                        if (strpos($validation_error, 'guild_id') !== false) {
                            $errors[] = $language->get('admin', 'discord_guild_id_required');
                        } else if (strpos($validation_error, 'bot_url') !== false) {
                            $errors[] = $language->get('admin', 'discord_bot_url_required');
                        }
                    }
                }
            }
        }
    } else {
        // Invalid token
        $errors = array($language->get('general', 'invalid_token'));
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

// Check if Discord integration is enabled
$discord_enabled = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
$discord_enabled = $discord_enabled[0]->value;

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'DISCORD' => $language->get('admin', 'discord'),
    'PAGE' => PANEL_PAGE,
    'INFO' => $language->get('general', 'info'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_DISCORD_INTEGRATION' => $language->get('admin', 'enable_discord_integration'),
    'DISCORD_ENABLED' => $discord_enabled
));

if ($discord_enabled == 1) {
    $guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
    $smarty->assign(array(
        'GUILD_ID' => $language->get('admin', 'discord_id'),
        'GUILD_ID_VALUE' => $guild_id[0]->value,
        'BOT_URL' => $language->get('admin', 'discord_bot_url'),
        'BOT_URL_VALUE' => BOT_URL,
        'BOT_URL_INFO' => $language->get('admin', 'discord_bot_url_info')
    ));
}

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/discord/discord.tpl', $smarty);
