<?php

$page_title = $language->get('admin', 'rcon');
if ($user->isLoggedIn()) {
	if (!$user->canViewStaffCP()) {

		Redirect::to(URL::build('/'));
		die();
	}
	if (!$user->isAdmLoggedIn()) {

		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if (!$user->hasPermission('admincp.minecraft.rcon')) {
				require_once(ROOT_PATH . '/403.php');
				die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'rcon');
define('MINECRAFT_PAGE', 'rcon');

require_once(ROOT_PATH . '/core/templates/backend_init.php');

$servers = $queries->getWhere('mc_servers', array('rcon_status', '=', 1));

$smarty->assign(array(
	'SERVERS' => $servers,
	'MINECRAFT_SERVERS' => $language->get('admin', 'minecraft_servers'),
	'NEW_SERVER' => $language->get('admin', 'add_server'),
	'NEW_SERVER_LINK' => URL::build('/panel/minecraft/servers/', 'action=new'),
	'EDIT_SERVER_LINK' => URL::build('/panel/minecraft/servers/', 'action=edit&id='),
	'CONSOLE_RCON_LINK' => URL::build('/panel/minecraft/rcon/console', 'id='),
	'MINECRAFT_SERVERS_LINK' => URL::build('/panel/minecraft/servers'),
	'NO_RCON_SERVERS' => $language->get('admin', 'no_rcon_servers'),
));


$template_file = 'integrations/minecraft/minecraft_servers_rcon.tpl';
// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $staffcp_nav), $widgets, $template);
$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));
$template->onPageLoad();

if (Session::exists('staff_rcon'))
	$success = Session::flash('staff_rcon');

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

	$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
));

require(ROOT_PATH . '/core/templates/panel_navbar.php');

$template->displayTemplate($template_file, $smarty);