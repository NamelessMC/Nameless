<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Discord page
 */

if(!$user->handlePanelPageLoad('admincp.discord')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'discord');
$page_title = $language->get('admin', 'discord');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    // Check token
    $errors = array();

    if (Token::check()) {
        // Valid token
        // Either enable or disable Discord integration
        $enable_discord_id = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
        $enable_discord_id = $enable_discord_id[0]->id;
        if ($_POST['enable_discord'] == '1') {
            $guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
            $guild_id = $guild_id[0]->value;
            if (BOT_URL == '' || BOT_USERNAME == '' || $guild_id == '') {
                $errors[] = $language->get('admin', 'discord_bot_must_be_setup');
                $queries->update('settings', $enable_discord_id, array(
                    'value' => 0
                ));
            } else {
                $queries->update('settings', $enable_discord_id, array(
                    'value' => 1
                ));
            }
        } else {
            $queries->update('settings', $enable_discord_id, array(
                'value' => 0
            ));
        }

        if (!count($errors))
            $success = $language->get('admin', 'discord_settings_updated');
    } else {
        // Invalid token
        $errors[] = array($language->get('general', 'invalid_token'));
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

if (Session::exists('discord_error'))
    $smarty->assign(array(
        'ERRORS' => array(Session::flash('discord_error')),
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

// Check if Discord integration is enabled
$discord_enabled = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
$discord_enabled = $discord_enabled[0]->value;
$guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
$guild_id = $guild_id[0]->value;

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
    'DISCORD_ENABLED' => $discord_enabled,
    'INVITE_LINK' => $language->get('admin', 'discord_invite_info'),
    'GUILD_ID_SET' => ($guild_id != ''),
    'BOT_URL_SET' => (BOT_URL != ''),
    'BOT_USERNAME_SET' => (BOT_USERNAME != ''),
    'REQUIREMENTS' => rtrim($language->get('installer', 'requirements'), ':'),
    'BOT_SETUP' => $language->get('admin', 'discord_bot_setup')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/discord/discord.tpl', $smarty);
