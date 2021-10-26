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
$page_title = Discord::getLanguageTerm('discord');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    $errors = array();

    if (Token::check()) {
        if (isset($_POST['discord_guild_id'])) {
            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'discord_guild_id' => [
                    Validate::MIN => 18,
                    Validate::MAX => 18,
                    Validate::NUMERIC => true,
                    Validate::REQUIRED => true,
                ]
            ])->messages([
                'discord_guild_id' => [
                    Validate::MIN => Discord::getLanguageTerm('discord_id_length'),
                    Validate::MAX => Discord::getLanguageTerm('discord_id_length'),
                    Validate::NUMERIC => Discord::getLanguageTerm('discord_id_numeric')
                ]
            ]);

            if ($validation->passed()) {

                $discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
                $discord_id = $discord_id[0]->id;

                $queries->update('settings', $discord_id, array(
                    'value' => Output::getClean(Input::get('discord_guild_id'))
                ));

                $success = Discord::getLanguageTerm('discord_settings_updated');

            } else {
                $errors = $validation->errors();
            }
        } else {
            // Valid token
            // Either enable or disable Discord integration
            $enable_discord_id = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
            $enable_discord_id = $enable_discord_id[0]->id;
            if ($_POST['enable_discord'] == '1') {
                if (BOT_URL == '' || BOT_USERNAME == '' || Discord::getGuildId() == '') {
                    $errors[] = Discord::getLanguageTerm('discord_bot_must_be_setup');
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
        }

        if (!count($errors)) {
            $success = Discord::getLanguageTerm('discord_settings_updated');
        }
    } else {
        // Invalid token
        $errors[] = array($language->get('general', 'invalid_token'));
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $staffcp_nav), $widgets, $template);

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

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'DISCORD' => Discord::getLanguageTerm('discord'),
    'PAGE' => PANEL_PAGE,
    'INFO' => $language->get('general', 'info'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_DISCORD_INTEGRATION' => Discord::getLanguageTerm('enable_discord_integration'),
    'DISCORD_ENABLED' => Discord::isBotSetup(),
    'INVITE_LINK' => Discord::getLanguageTerm('discord_invite_info'),
    'GUILD_ID_SET' => (Discord::getGuildId() != ''),
    'BOT_URL_SET' => (BOT_URL != ''),
    'BOT_USERNAME_SET' => (BOT_USERNAME != ''),
    'REQUIREMENTS' => rtrim($language->get('installer', 'requirements'), ':'),
    'BOT_SETUP' => Discord::getLanguageTerm('discord_bot_setup'),
    'DISCORD_GUILD_ID' => Discord::getLanguageTerm('discord_guild_id'),
    'DISCORD_GUILD_ID_VALUE' => Discord::getGuildId(),
    'ID_INFO' => Discord::getLanguageTerm('discord_id_help'),
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/discord/discord.tpl', $smarty);
