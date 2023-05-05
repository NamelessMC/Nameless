<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Maintenance Mode page
 */

$pages = new Pages();

const PAGE = 'maintenance';
$page_title = $language->get('errors', 'maintenance_title');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (!$user->isLoggedIn()) {
    $smarty->assign(
        [
            'LOGIN' => $language->get('general', 'sign_in'),
            'LOGIN_LINK' => URL::build('/login')
        ]
    );
}

// Assign Smarty variables
$smarty->assign(
    [
        'MAINTENANCE_TITLE' => $language->get('errors', 'maintenance_title'),
        'MAINTENANCE_MESSAGE' => Output::getPurified(Util::getSetting('maintenance_message', 'Maintenance mode is enabled.')),
        'RETRY' => $language->get('errors', 'maintenance_retry')
    ]
);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

// Display template
$template->displayTemplate('maintenance.tpl', $smarty);
