<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  404 Not Found page
 */

header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

define('PAGE', 404);
$page_title = '404';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define(
    'PAGE_LOAD_TIME',
    str_replace(
        '{x}',
        round($page_load, 3), $language->get('general', 'page_loaded_in')
    )
);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Assign Smarty variables
$smarty->assign(
    array(
        '404_TITLE' => $language->get('errors', '404_title'),
        'CONTENT' => $language->get('errors', '404_content'),
        'BACK' => $language->get('errors', '404_back'),
        'HOME' => $language->get('errors', '404_home'),
        'ERROR' => str_replace(array('{x}', '{y}'), array('<a href="' . URL::build('/contact') . '">', '</a>'), $language->get('errors', '404_error')),
        'PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : '')
    )
);

// Display template
$template->displayTemplate('404.tpl', $smarty);
