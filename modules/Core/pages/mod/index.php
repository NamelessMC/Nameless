<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Moderator Overview page
 */

// Can the user view the ModCP?
if($user->isLoggedIn()){
	if(!$user->canViewMCP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
define('PAGE', 'mod_overview');
$page_title = $language->get('moderator', 'mod_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require(ROOT_PATH . '/core/templates/mod_navbar.php');

// Count number of open reports
$count_reports = $queries->getWhere('reports', array('status', '=', 0));
$count_reports = count($count_reports);

// Smarty variables
$smarty->assign(array(
	'OVERVIEW' => $language->get('admin', 'overview'),
	'OPEN_REPORTS' => ($count_reports == 1) ? $language->get('moderator', '1_open_report') : str_replace('{x}', $count_reports, $language->get('moderator', 'open_reports'))
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('mod/index.tpl', $smarty);