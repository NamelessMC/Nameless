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

if(!$user->handlePanelPageLoad('admincp.update')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (isset($_GET['recheck'])) {
    $cache->setCache('update_check');
    if ($cache->isCached('update_check')) {
        $cache->erase('update_check');
    }

    Redirect::to(URL::build('/panel/update'));
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'update');
define('PANEL_PAGE', 'update');
$page_title = $language->get('admin', 'update');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

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

$cache->setCache('update_check');
if ($cache->isCached('update_check')) {
    $update_check = $cache->retrieve('update_check');
} else {
    $update_check = Util::updateCheck();
    $cache->store('update_check', $update_check, 3600);
}

$update_check = json_decode($update_check);

if (!$update_check->error && $update_check->update_available) {
    $smarty->assign(array(
        'INSTRUCTIONS' => $language->get('admin', 'instructions'),
        'INSTRUCTIONS_VALUE' => Output::getPurified($update_check->install_instructions)
    ));
}

$smarty->assign(array(
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
    'DOWNLOAD_LINK' => 'https://namelessmc.com/nl_core/nl2/updates/' . str_replace(array('.', '-'), '', Output::getClean($update_check->new_version)) . '.zip',
    'DOWNLOAD' => $language->get('admin', 'download'),
    'WARNING' => $language->get('general', 'warning'),
    'CANCEL' => $language->get('general', 'cancel'),
    'INSTALL_CONFIRM' => $language->get('admin', 'install_confirm')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/update.tpl', $smarty);
