<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  404 Not Found page
 */

header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

const PAGE = 404;
$page_title = '404';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Assign Smarty variables
$smarty->assign(
    [
        '404_TITLE' => $language->get('errors', '404_title'),
        'CONTENT' => $language->get('errors', '404_content'),
        'BACK' => $language->get('errors', '404_back'),
        'HOME' => $language->get('errors', '404_home'),
        'ERROR' => $language->get('errors', '404_error'),
        'PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : '')
    ]
);

// Display template
$template->displayTemplate('404.tpl', $smarty);
