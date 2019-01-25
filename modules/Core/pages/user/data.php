<?php

if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}

// Always define page name for navbar
define('PAGE', 'cc_data');
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$prefix = Config::get("mysql/prefix");
$tables = DB::getInstance()->query("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = ?", ["{$prefix}users"])->results();
$userData = [];
foreach($tables as $table){
	$tableName = $table->TABLE_NAME;
	$tableName = substr($tableName, strlen($prefix)-strlen($tableName)); // Tables name are based on the mysql installtion... Sorry forigners
	$columnName = $table->COLUMN_NAME;
	$userData[$tableName] = DB::getInstance()->get($tableName, [$columnName, "=", $user->data()->id])->results(); // This is the data we want
}

// Language values
$smarty->assign(array(
	'USER_CP' => $language->get('user', 'user_cp'),
	'USER_DETAILS' => $language->get('user', 'user_details'),
	'USER_DETAILS_VALUES' => $user_details,
	'OVERVIEW' => $language->get('user', 'overview'),
	'DATA' => $language->get('user', 'data'),
	'USER_DATA' => $userData,
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/data.tpl', $smarty);