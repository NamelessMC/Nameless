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
  }
	if (!$user->hasPermission('administrator')) {
		Redirect::to(URL::build('/'));
    die();
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

if (isset($_GET['id'])) {
  if (is_numeric($_GET['id'])) {
    $server_id = $_GET['id'];
  } else {
    require_once(ROOT_PATH . '/403.php');
    die();
  }
}

$server = end($queries->getWhere('mc_servers', array('id', '=', $server_id)));
$groups = $queries->getWhere('groups', array('id', '<>', '0')); // Get a list of all groups
foreach ($groups as $key => $group) {
	$groups[$key]->permissions = json_decode($group->permissions, TRUE);
}
$smarty->assign(array(
  'SERVER' => $server,
	'GROUPS' => $groups,
	'SERVER_PERMISSION' => 'admincp.minecraft.rcon.' . $server->id
));

if (Token::check($_POST['token'])) {
	if (isset($_POST['permissions'])) {
		foreach ($groups as $key => $group) {
			if ($group->id == 2) {
				continue;
			}
			if (in_array($group->id, $_POST['group'])) {
				$group_permissions = $group->permissions;
				$group_permissions['admincp.minecraft.rcon.' . $server->id] = 1;
				$group_permissions = json_encode($group_permissions);
				$queries->update('groups', $group->id, array('permissions' => $group_permissions));
			} else {
				$group_permissions = $group->permissions;
				unset($group_permissions['admincp.minecraft.rcon.' . $server->id]);
				$group_permissions = json_encode($group_permissions);
				$queries->update('groups', $group->id, array('permissions' => $group_permissions));
			}
		}
		Redirect::to(URL::build('/panel/minecraft//rcon/permissions', 'id=' . $server->id));
		die();
	}
}




$template_file = 'integrations/minecraft/minecraft_servers_rcon_permissions.tpl';
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
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/minecraft//rcon/console', 'id=' . $server->id),
    'PERMISSION_LABEL' => $language->get('admin', 'permissions')
));

require(ROOT_PATH . '/core/templates/panel_navbar.php');

$template->displayTemplate($template_file, $smarty);
