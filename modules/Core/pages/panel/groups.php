<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel groups page
 */

// Can the user view the panel?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if(!$user->isAdmLoggedIn()){
		// Needs to authenticate
		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if(!$user->hasPermission('admincp.groups')){
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
define('PARENT_PAGE', 'groups');
define('PANEL_PAGE', 'groups');
$page_title = $language->get('admin', 'groups');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$discord_integration = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
$discord_integration = $discord_integration[0]->value;

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('admin_groups')){
	$success = Session::flash('admin_groups');
}

if(Session::exists('admin_groups_error'))
	$errors = array(Session::flash('admin_groups_error'));

if(isset($_GET['action'])){
	if($_GET['action'] == 'new'){
		if(Input::exists()){
			$errors = array();
			if(Token::check(Input::get('token'))){
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'groupname' => array(
						'required' => true,
						'min' => 2,
						'max' => 20
					),
					'html' => array(
						'max' => 1024
					),
					'discord_role_id' => array(
						'min' => 18,
						'max' => 18,
						'numeric' => true
					)
				));

				if($validation->passed()){
					try {
						if(isset($_POST['default']) && $_POST['default'] == 1)
							$default = 1;
						else
							$default = 0;
						
						$role_id = Input::get('discord_role_id');
						if ($role_id == '') $role_id = null;

						// If this is the new default group, update old default group
						$default_group = $queries->getWhere('groups', array('default_group', '=', 1));
						if(!count($default_group) && $default == 0)
							$default = 1;

						$queries->create('groups', array(
							'name' => Input::get('groupname'),
							'group_html' => Input::get('html'),
							'group_html_lg' => Input::get('html'),
							'group_username_css' => ($_POST['username_style'] ? Input::get('username_style') : null),
							'mod_cp' => Input::get('staffcp'),
							'admin_cp' => Input::get('staffcp'),
							'staff' => Input::get('staff'),
							'default_group' => $default,
							'order' => Input::get('order'),
							'discord_role_id' => $role_id
						));

						$group_id = $queries->getLastId();

						if($default == 1){
							if(count($default_group) && $default_group[0]->id != $group_id){
								$queries->update('groups', $default_group[0]->id, array(
									'default_group' => 0
								));
							}

							$cache->setCache('default_group');
							$cache->store('default_group', $group_id);
						}

						Session::flash('admin_groups', $language->get('admin', 'group_created_successfully'));
						Redirect::to(URL::build('/panel/core/groups'));
						die();

					} catch(Exception $e) {
						$errors[] = $e->getMessage();
					}

				} else {
					foreach ($validation->errors() as $error) {
						if (strpos($error, 'is required') !== false) {
							$errors[] = $language->get('admin', 'group_name_required');
						} else if (strpos($error, 'minimum') !== false) {
							switch ($error) {
								case (strpos($error, 'groupname') !== false):
									$errors[] = $language->get('admin', 'group_name_minimum') . '<br />';
									break;
								case (strpos($error, 'discord_role_id') !== false):
									$errors[] = $language->get('admin', 'discord_role_id_length') . '<br />';
									break;
							}
						} else if (strpos($error, 'maximum') !== false) {
							switch ($error) {
								case (strpos($error, 'groupname') !== false):
									$errors[] = $language->get('admin', 'group_name_maximum') . '<br />';
									break;
								case (strpos($error, 'html') !== false):
									$errors[] = $language->get('admin', 'html_maximum') . '<br />';
									break;
								case (strpos($error, 'discord_role_id') !== false):
									$errors[] = $language->get('admin', 'discord_role_id_length') . '<br />';
									break;
							}
						} else if (strpos($error, 'numeric') !== false) {
							$errors[] = $language->get('admin', 'discord_role_id_numeric');
						}
					}
				}
			} else
				$errors[] = $language->get('general', 'invalid_token');
		}

		$smarty->assign(array(
			'CREATING_NEW_GROUP' => $language->get('admin', 'creating_group'),
			'CANCEL' => $language->get('general', 'cancel'),
			'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
			'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
			'YES' => $language->get('general', 'yes'),
			'NO' => $language->get('general', 'no'),
			'CANCEL_LINK' => URL::build('/panel/core/groups'),
			'NAME' => $language->get('admin', 'name'),
			'GROUP_HTML' => $language->get('admin', 'group_html'),
			'GROUP_USERNAME_COLOUR' => $language->get('admin', 'group_username_colour'),
			'DISCORD_INTEGRATION' => $discord_integration ? true : false,
			'GROUP_ORDER' => $language->get('admin', 'group_order'),
			'STAFF_GROUP' => $language->get('admin', 'group_staff'),
			'STAFF_CP' => $language->get('admin', 'can_view_staffcp'),
			'DEFAULT_GROUP' => $language->get('admin', 'default_group')
		));

		if ($discord_integration) {
			$smarty->assign(array(
				'DISCORD_ROLE_ID' => $language->get('admin', 'discord_role_id'),
				'DISCORD_ROLE_ID_VALUE' => $group->discord_role_id
			));
		}

		$template_file = 'core/groups_new.tpl';

	} else if($_GET['action'] == 'edit'){
		if(!isset($_GET['group']) || !is_numeric($_GET['group'])){
			Redirect::to(URL::build('/panel/core/groups'));
			die();
		}

		$group = $queries->getWhere('groups', array('id', '=', $_GET['group']));
		if(!count($group)){
			Redirect::to(URL::build('/panel/core/groups'));
			die();
		}
		$group = $group[0];

		if($group->id == 2 || (($group->id == $user->data()->group_id || in_array($group->id, json_decode($user->data()->secondary_groups))) && !$user->hasPermission('admincp.groups.self'))){
			$smarty->assign(array(
				'OWN_GROUP' => $language->get('admin', 'cant_edit_this_group'),
				'INFO' => $language->get('general', 'info')
			));

		} else {
			$smarty->assign(array(
				'PERMISSIONS' => $language->get('admin', 'permissions'),
				'PERMISSIONS_LINK' => URL::build('/panel/core/groups/', 'action=permissions&group=' . Output::getClean($group->id)),
				'DELETE' => $language->get('general', 'delete'),
				'DELETE_GROUP' => $language->get('admin', 'delete_group'),
				'CONFIRM_DELETE' => str_replace('{x}', Output::getClean($group->name), $language->get('admin', 'confirm_group_deletion'))
			));
		}

		if(Input::exists()){
			$errors = array();
			if(Token::check(Input::get('token'))){
				if(Input::get('action') == 'update'){
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'groupname' => array(
							'required' => true,
							'min' => 2,
							'max' => 20
						),
						'html' => array(
							'max' => 1024
						),
						'discord_role_id' => array(
							'min' => 18,
							'max' => 18,
							'numeric' => true
						)
					));

					if($validation->passed()){
						try {
							if(isset($_POST['default']) && $_POST['default'] == 1){
								$default = 1;
								$cache->setCache('default_group');
								$cache->store('default_group', $_GET['group']);
							} else
								$default = 0;

							$role_id = Input::get('discord_role_id');
							if ($role_id == '') $role_id = null;

							// If this is the new default group, update old default group
							$default_group = $queries->getWhere('groups', array('default_group', '=', 1));
							if(count($default_group) && $default == 1 && $default_group[0]->id != $_GET['group'])
								$queries->update('groups', $default_group[0]->id, array(
									'default_group' => 0
								));
							else if(!count($default_group) && $default == 0)
								$default = 1;

							if($group->id == 2){
								$staff_cp = 1;
							} else {
								$staff_cp = Input::get('staffcp');
							}

							$queries->update('groups', $_GET['group'], array(
								'name' => Input::get('groupname'),
								'group_html' => Input::get('html'),
								'group_html_lg' => Input::get('html'),
								'group_username_css' => ($_POST['username_style'] ? Input::get('username_style') : null),
								'mod_cp' => $staff_cp,
								'admin_cp' => $staff_cp,
								'staff' => Input::get('staff'),
								'default_group' => $default,
								'`order`' => Input::get('order'),
								'discord_role_id' => $role_id
							));

							Session::flash('admin_groups', $language->get('admin', 'group_updated_successfully'));
							Redirect::to(URL::build('/panel/core/groups/', 'action=edit&group=' . Output::getClean($_GET['group'])));
							die();

						} catch(Exception $e) {
							$errors[] = $e->getMessage();
						}

					} else {
						foreach($validation->errors() as $error){
							if(strpos($error, 'is required') !== false){
								$errors[] = $language->get('admin', 'group_name_required');
							} else if(strpos($error, 'minimum') !== false){
								switch ($error) {
									case (strpos($error, 'groupname') !== false):
										$errors[] = $language->get('admin', 'group_name_minimum') . '<br />';
										break;
									case (strpos($error, 'discord_role_id') !== false):
										$errors[] = $language->get('admin', 'discord_role_id_length') . '<br />';
										break;
								}
							} else if(strpos($error, 'maximum') !== false){
								switch($error){
									case (strpos($error, 'groupname') !== false):
										$errors[] = $language->get('admin', 'group_name_maximum') . '<br />';
										break;
									case (strpos($error, 'html') !== false):
										$errors[] = $language->get('admin', 'html_maximum') . '<br />';
										break;
									case (strpos($error, 'discord_role_id') !== false):
										$errors[] = $language->get('admin', 'discord_role_id_length') . '<br />';
										break;
								}
							} else if(strpos($error, 'numeric') !== false) {
								$errors[] = $language->get('admin', 'discord_role_id_numeric');
							}
						}
					}
				} else if(Input::get('action') == 'delete'){
					try {
						$default_group = $queries->getWhere('groups', array('default_group', '=', 1));

						if(count($default_group)){
							if($group->id == 2 || $default_group[0]->id == Input::get('id') || $group->admin_cp == 1){
								// Can't delete default group/admin group
								Session::flash('admin_groups_error', $language->get('admin', 'unable_to_delete_group'));
							} else {
								$queries->delete('groups', array('id', '=', Input::get('id')));
								Session::flash('admin_groups', $language->get('admin', 'group_deleted_successfully'));
							}
						}

						Redirect::to(URL::build('/panel/core/groups'));
						die();
					} catch(Exception $e) {
						$errors[] = $e->getMessage();
					}
				}
			} else
				$errors[] = $language->get('general', 'invalid_token');
		}

		$smarty->assign(array(
			'GROUP_ID' => Output::getClean($group->id),
			'NAME' => $language->get('admin', 'name'),
			'GROUP_HTML' => $language->get('admin', 'group_html'),
			'GROUP_HTML_VALUE' => Output::getClean($group->group_html),
			'GROUP_USERNAME_COLOUR' => $language->get('admin', 'group_username_colour'),
			'GROUP_USERNAME_COLOUR_VALUE' => Output::getClean($group->group_username_css),
			'DISCORD_INTEGRATION' => $discord_integration ? true : false,
			'STAFF_GROUP' => $language->get('admin', 'group_staff'),
			'STAFF_GROUP_VALUE' => $group->staff,
			'STAFF_CP' => $language->get('admin', 'can_view_staffcp'),
			'STAFF_CP_VALUE' => $group->admin_cp,
			'DEFAULT_GROUP' => $language->get('admin', 'default_group'),
			'DEFAULT_GROUP_VALUE' => $group->default_group,
			'CANCEL' => $language->get('general', 'cancel'),
			'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
			'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
			'YES' => $language->get('general', 'yes'),
			'NO' => $language->get('general', 'no'),
			'CANCEL_LINK' => URL::build('/panel/core/groups'),
			'GROUP_NAME' => Output::getClean($group->name),
			'GROUP_ORDER' => $language->get('admin', 'group_order'),
			'GROUP_ORDER_VALUE' => $group->order,
		));

		if ($discord_integration) {
			$smarty->assign(array(
				'DISCORD_ROLE_ID' => $language->get('admin', 'discord_role_id'),
				'DISCORD_ROLE_ID_VALUE' => $group->discord_role_id
			));
		}

		$template_file = 'core/groups_edit.tpl';

	} else if($_GET['action'] == 'permissions'){
		if(!isset($_GET['group']) || !is_numeric($_GET['group'])){
			Redirect::to(URL::build('/panel/core/groups'));
			die();
		}

		$group = $queries->getWhere('groups', array('id', '=', $_GET['group']));
		if(!count($group)){
			Redirect::to(URL::build('/panel/core/groups'));
			die();
		}
		$group = $group[0];

		if($group->id == 2 || (($group->id == $user->data()->group_id || in_array($group->id, json_decode($user->data()->secondary_groups))) && !$user->hasPermission('admincp.groups.self'))){
			Redirect::to(URL::build('/panel/core/groups'));
			die();
		}

		if(Input::exists()){
			$errors = array();

			if(Token::check(Input::get('token'))){
				// Token valid
				// Build new JSON object for permissions
				$perms = array();
				if(isset($_POST['permissions']) && count($_POST['permissions'])){
					foreach($_POST['permissions'] as $permission => $value){
						$perms[$permission] = 1;
					}
				}
				$perms_json = json_encode($perms);

				try {
					$queries->update('groups', $group->id, array('permissions' => $perms_json));

					Session::flash('admin_groups', $language->get('admin', 'permissions_updated_successfully'));
					Redirect::to(URL::build('/panel/core/groups/', 'action=edit&group=' . $group->id));
					die();

				} catch(Exception $e) {
					$errors[] = $e->getMessage();
				}
			} else
				$errors[] = $language->get('general', 'invalid_token');
		}

		$smarty->assign(array(
			'PERMISSIONS' => $language->get('admin', 'permissions'),
			'BACK' => $language->get('general', 'back'),
			'BACK_LINK' => URL::build('/panel/core/groups/', 'action=edit&group=' . Output::getClean($group->id)),
			'PERMISSIONS_VALUES' => json_decode($group->permissions, true),
			'ALL_PERMISSIONS' => PermissionHandler::getPermissions(),
			'SELECT_ALL' => $language->get('admin', 'select_all'),
			'DESELECT_ALL' => $language->get('admin', 'deselect_all')
		));

		$template_file = 'core/groups_permissions.tpl';

	}

} else {
	$groups = $queries->orderAll('groups', '`order`', 'ASC');

	$groups_template = array();
	foreach($groups as $group){
		$users = $queries->getWhere('users', array('group_id', '=', $group->id));
		$users = count($users);

		$groups_template[] = array(
			'id' => Output::getClean($group->id),
			'name' => Output::getClean($group->name),
			'edit_link' => URL::build('/panel/core/groups/', 'action=edit&group=' . Output::getClean($group->id)),
			'users' => $users
		);
	}

	$smarty->assign(array(
		'GROUP_ID' => $language->get('admin', 'group_id'),
		'NAME' => $language->get('admin', 'name'),
		'USERS' => $language->get('admin', 'users'),
		'NEW_GROUP' => $language->get('admin', 'new_group'),
		'NEW_GROUP_LINK' => URL::build('/panel/core/groups/', 'action=new'),
		'GROUP_LIST' => $groups_template,
		'EDIT' => $language->get('general', 'edit')
	));

	$template_file = 'core/groups.tpl';
}

if(isset($success))
	$smarty->assign(array(
		'SUCCESS' => $success,
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'GROUPS' => $language->get('admin', 'groups'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'INFO' => $language->get('general', 'info'),
	'ID_INFO' => $language->get('user', 'discord_id_help')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);