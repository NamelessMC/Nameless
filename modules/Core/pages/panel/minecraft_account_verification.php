<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel Minecraft account verification page
 */

if (!$user->handlePanelPageLoad('admincp.minecraft.verification')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
const MINECRAFT_PAGE = 'verification';
$page_title = $language->get('admin', 'account_verification');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (Input::exists()) {
    $errors = [];
    if (Token::check()) {
        if (isset($_POST['premium'])) {
            if (isset($_POST['enable_premium_accounts']) && $_POST['enable_premium_accounts'] == 1) {
                $use_premium = 1;
            } else {
                $use_premium = 0;
            }

            Util::setSetting('uuid_linking', $use_premium);
        }
    } else {
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

// Get UUID linking settings
$uuid_linking = Util::getSetting('uuid_linking');

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'MINECRAFT_LINK' => URL::build('/panel/minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ACCOUNT_VERIFICATION' => $language->get('admin', 'account_verification'),
    'FORCE_PREMIUM_ACCOUNTS' => $language->get('admin', 'force_premium_accounts'),
    'FORCE_PREMIUM_ACCOUNTS_VALUE' => ($uuid_linking == '1')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft_account_verification.tpl', $smarty);
