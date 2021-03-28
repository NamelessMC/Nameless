<?php
// define('PAGE', 'portal');
// $page_title = $language->get('general', 'home');
// require_once(ROOT_PATH . '/core/templates/frontend_init.php');
// // Load modules + template
// Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

// $page_load = microtime(true) - $start;
// define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

// $template->onPageLoad();

// require(ROOT_PATH . '/core/templates/navbar.php');
// require(ROOT_PATH . '/core/templates/footer.php');

// // Display template
// $template->displayTemplate('legal.tpl', $smarty);

// Always define page name
define('PAGE', 'legal');
$page_title = $language->get('general', 'legal');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
// require_once(ROOT_PATH . '/core/templates/legal.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$smarty->assign(array(
	'LEGAL_TITLE' => $language->get('general', 'legal')
));

// Display template
$template->displayTemplate('namelessmc_legal.tpl', $smarty);
