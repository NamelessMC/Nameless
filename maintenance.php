<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Maintenance Mode page
 */

$pages = new Pages();

define('PAGE', 'maintenance');
$page_title = $language->get('errors', 'maintenance_title');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (!$user->isLoggedIn()) {
    $smarty->assign(
        array(
            'LOGIN' => $language->get('general', 'sign_in'),
            'LOGIN_LINK' => URL::build('/login')
        )
    );
}

// Assign Smarty variables
$smarty->assign(
    array(
        'MAINTENANCE_TITLE' => $language->get('errors', 'maintenance_title'),
        'RETRY' => $language->get('errors', 'maintenance_retry')
    )
);

// Retrieve maintenance message
$maintenance_message = $maintenance['message'];
if (!empty($maintenance_message)) {
    $smarty->assign('MAINTENANCE_MESSAGE', Output::getPurified(htmlspecialchars_decode($maintenance_message)));
} else {
    $smarty->assign('MAINTENANCE_MESSAGE', 'Maintenance mode is enabled.');
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

// Display template
$template->displayTemplate('maintenance.tpl', $smarty);
