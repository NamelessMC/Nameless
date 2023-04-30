<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Discord page
 */

if (!$user->handlePanelPageLoad('admincp.discord')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'discord';
$page_title = Discord::getLanguageTerm('discord');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        if (isset($_POST['discord_guild_id'])) {
            $validation = Validate::check($_POST, [
                'discord_guild_id' => [
                    Validate::MIN => 18,
                    Validate::MAX => 20,
                    Validate::NUMERIC => true,
                    Validate::REQUIRED => true,
                ]
            ])->messages([
                'discord_guild_id' => [
                    Validate::MIN => Discord::getLanguageTerm('discord_id_length', ['min' => 18, 'max' => 20]),
                    Validate::MAX => Discord::getLanguageTerm('discord_id_length', ['min' => 18, 'max' => 20]),
                    Validate::NUMERIC => Discord::getLanguageTerm('discord_id_numeric'),
                    Validate::REQUIRED => Discord::getLanguageTerm('discord_id_required'),
                ]
            ]);

            if ($validation->passed()) {
                Util::setSetting('discord', Input::get('discord_guild_id'));

                $success = Discord::getLanguageTerm('discord_settings_updated');

            } else {
                $errors = $validation->errors();
            }
        } else {
            // Valid token
            // Either enable or disable Discord integration
            if ($_POST['enable_discord'] === '1') {
                if (BOT_URL == '' || BOT_USERNAME == '' || Discord::getGuildId() == '') {
                    $errors[] = Discord::getLanguageTerm('discord_bot_must_be_setup', [
                        'linkStart' => '<a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">',
                        'linkEnd' => '</a>',
                    ]);
                    Util::setSetting('discord_integration', '0');
                } else {
                    Util::setSetting('discord_integration', '1');
                }
            } else {
                Util::setSetting('discord_integration', '0');
            }
        }

        if (!count($errors)) {
            Session::flash('discord_success', Discord::getLanguageTerm('discord_settings_updated'));
            Redirect::to(URL::build('/panel/discord'));
        }
    } else {
        // Invalid token
        $errors[] = [$language->get('general', 'invalid_token')];
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('discord_success')) {
    $success = Session::flash('discord');
}

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

if (Session::exists('discord_error')) {
    $smarty->assign([
        'ERRORS' => [Session::flash('discord_error')],
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// TODO: Add a check to see if the bot is online using `/status` endpoint Discord::botRequest('/status');

$smarty->assign([
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
    'INVITE_LINK' => Discord::getLanguageTerm('discord_invite_info', [
        'inviteLinkStart' => '<a target="_blank" href="https://namelessmc.com/discord-bot-invite">',
        'inviteLinkEnd' => '</a>',
        'command' => '<code>/configure link</code>',
        'selfHostLinkStart' => '<a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Installation-guide">',
        'selfHostLinkEnd' => '</a>',
    ]),
    'GUILD_ID_SET' => (Discord::getGuildId() != ''),
    'BOT_URL_SET' => (BOT_URL != ''),
    'BOT_USERNAME_SET' => (BOT_USERNAME != ''),
    'REQUIREMENTS' => rtrim($language->get('installer', 'requirements'), ':'),
    'BOT_SETUP' => Discord::getLanguageTerm('discord_bot_setup'),
    'DISCORD_GUILD_ID' => Discord::getLanguageTerm('discord_guild_id'),
    'DISCORD_GUILD_ID_VALUE' => Discord::getGuildId(),
    'ID_INFO' => Discord::getLanguageTerm('discord_id_help', [
        'linkStart' => '<a href="https://support.discord.com/hc/en-us/articles/206346498" target="_blank">',
        'linkEnd' => '</a>',
    ]),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/discord/discord.tpl', $smarty);
