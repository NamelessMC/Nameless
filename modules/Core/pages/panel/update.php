<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel update page
 */

if (!$user->handlePanelPageLoad('admincp.update')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (isset($_GET['recheck'])) {
    $cache->setCache('update_check');
    if ($cache->isCached('update_check')) {
        $cache->erase('update_check');
    }

    Redirect::to(URL::build('/panel/update'));
}

const PAGE = 'panel';
const PARENT_PAGE = 'update';
const PANEL_PAGE = 'update';
$page_title = $language->get('admin', 'update');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

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

$cache->setCache('update_check');
if ($cache->isCached('update_check')) {
    $update_check = $cache->retrieve('update_check');
} else {
    $update_check = Util::updateCheck();
    $cache->store('update_check', $update_check, 3600);
}

$update_check = json_decode($update_check);

if (!isset($update_check->error) && !isset($update_check->no_update) && isset($update_check->new_version)) {
    // Unique ID + current version
    $uid = $queries->getWhere('settings', ['name', 'unique_id']);
    $uid = $uid[0]->value;

    $instructions = HttpClient::get('https://namelessmc.com/nl_core/nl2/instructions.php?uid=' . $uid . '&version=' . NAMELESS_VERSION);

    if ($instructions->hasError()) {
        $instructions = $instructions->getError();
    } else {
        $instructions = $instructions->contents();
        if ($instructions == 'Failed') {
            $instructions = 'Unknown error';
        }
    }

    $smarty->assign([
        'INSTRUCTIONS' => $language->get('admin', 'instructions'),
        'INSTRUCTIONS_VALUE' => Output::getPurified($instructions)
    ]);
}

// PHP version check
if (PHP_VERSION_ID < 70400) {
    $smarty->assign('PHP_WARNING', $language->get('admin', 'upgrade_php_version'));

    if (NAMELESS_VERSION !== '2.0.0-pr11') {
        $smarty->assign('PREVENT_UPGRADE', true);
    }
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'UPDATE' => $language->get('admin', 'update'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'UP_TO_DATE' => $language->get('admin', 'up_to_date'),
    'CHECK_AGAIN' => $language->get('admin', 'check_again'),
    'CHECK_AGAIN_LINK' => URL::build('/panel/update/', 'recheck=true'),
    'UPGRADE_LINK' => URL::build('/panel/upgrade'),
    'DOWNLOAD_LINK' => 'https://namelessmc.com/nl_core/nl2/updates/' . str_replace(['.', '-'], '', Output::getClean($update_check->new_version)) . '.zip',
    'DOWNLOAD' => $language->get('admin', 'download'),
    'WARNING' => $language->get('general', 'warning'),
    'CANCEL' => $language->get('general', 'cancel'),
    'INSTALL_CONFIRM' => $language->get('admin', 'install_confirm')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/update.tpl', $smarty);
