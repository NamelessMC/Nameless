<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel API page
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
		if(!$user->hasPermission('admincp.core.api')){
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
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'api');
$page_title = $language->get('admin', 'api');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(!isset($_GET['view'])){
	if(isset($_GET['action']) && $_GET['action'] == 'api_regen'){
		// Regenerate new API key
		// Generate new key
		$new_api_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);

		$plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
		$plugin_api = $plugin_api[0]->id;

		// Update key
		$queries->update('settings', $plugin_api, array(
			'value' => $new_api_key
		));

		// Cache
		file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);

		// Redirect
		Session::flash('api_success', $language->get('admin', 'api_key_regenerated'));
		Redirect::to(URL::build('/panel/core/api'));
		die();
	}

	if(Input::exists()){
		$errors = array();

		if(Token::check(Input::get('token'))){
			$plugin_id = $queries->getWhere('settings', array('name', '=', 'use_api'));
			$plugin_id = $plugin_id[0]->id;
			$queries->update('settings', $plugin_id, array(
				'value' => Input::get('enable_api')
			));

			$legacy_plugin_id = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
			$legacy_plugin_id = $legacy_plugin_id[0]->id;
			$queries->update('settings', $legacy_plugin_id, array(
				'value' => Input::get('enable_legacy_api')
			));

			if(isset($_POST['verification']) && $_POST['verification'] == 'on')
				$verification = 1;
			else
				$verification = 0;

			$verification_id = $queries->getWhere('settings', array('name', '=', 'email_verification'));
			$verification_id = $verification_id[0]->id;
			try {
				$queries->update('settings', $verification_id, array(
					'value' => $verification
				));
			} catch(Exception $e){
				$errors[] = $e->getMessage();
			}

			if(isset($_POST['api_verification']) && $_POST['api_verification'] == 'on')
				$api_verification = 1;
			else
				$api_verification = 0;

			$api_verification_id = $queries->getWhere('settings', array('name', '=', 'api_verification'));
			$api_verification_id = $api_verification_id[0]->id;
			try {
				$queries->update('settings', $api_verification_id, array(
					'value' => $api_verification
				));
			} catch(Exception $e){
				$errors[] = $e->getMessage();
			}

			if(isset($_POST['username_sync']) && $_POST['username_sync'] == 'on')
				$username_sync = 1;
			else
				$username_sync = 0;

			$username_sync_id = $queries->getWhere('settings', array('name', '=', 'username_sync'));
			$username_sync_id = $username_sync_id[0]->id;
			try {
				$queries->update('settings', $username_sync_id, array(
					'value' => $username_sync
				));
			} catch(Exception $e){
				$errors[] = $e->getMessage();
			}

			Session::flash('api_success', $language->get('admin', 'api_settings_updated_successfully'));

			//Log::getInstance()->log(Log::Action('admin/api/change'));
		} else {
			$errors[] = $language->get('general', 'invalid_token');
		}
	}

} else {
	// Group sync
	if(isset($_GET['action']) && $_GET['action'] == 'delete'){
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
			Redirect::to(URL::build('/panel/core/api/', 'view=group_sync'));
			die();
		}

		try {
			$queries->delete('group_sync', array('id', '=', $_GET['id']));
			Session::flash('api_success', $language->get('admin', 'group_sync_rule_deleted_successfully'));

		} catch(Exception $e){
			// Redirect anyway
		}

		Redirect::to(URL::build('/panel/core/api/', 'view=group_sync'));
		die();
	}

	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			if($_POST['action'] == 'create'){
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'ingame_rank_name' => array(
						'required' => true,
						'min' => 2,
						'max' => 64,
						'unique' => 'group_sync'
					),
					'website_group' => array(
						'required' => true
					)
				));

				if($validation->passed()){
					// Primary group?
					if(isset($_POST['primary_group']) && $_POST['primary_group'] == 1)
						$primary = 1;
					else
						$primary = 0;

					$queries->create('group_sync', array(
						'ingame_rank_name' => Output::getClean(Input::get('ingame_rank_name')),
						'website_group_id' => intval(Input::get('website_group')),
						'primary' => $primary
					));

					Session::flash('api_success', $language->get('admin', 'group_sync_rule_created_successfully'));

				} else {
					$errors = array();
					foreach($validation->errors() as $error){
						if(strpos($error, 'ingame_rank') !== false){
							if(strpos($error, 'required') !== false){
								$errors[] = $language->get('admin', 'group_name_required');

							} else if(strpos($error, 'minimum') !== false){
								$errors[] = $language->get('admin', 'group_name_minimum');

							} else if(strpos($error, 'maximum') !== false){
								$errors[] = $language->get('admin', 'ingame_group_maximum');

							} else {
								$errors[] = $language->get('admin', 'ingame_group_already_exists');
							}

						} else {
							$errors[] = $language->get('admin', 'select_website_group');
						}
					}
				}

			} else if($_POST['action'] == 'update'){
				$errors = array();

				if(isset($_POST['ingame_group']) && isset($_POST['website_group']) && isset($_POST['primary_group'])){
					foreach($_POST['ingame_group'] as $key => $ingame_group){
						if(isset($_POST['website_group'][$key]) && isset($_POST['primary_group'][$key])){
							if(strlen(str_replace(' ', '', $ingame_group)) > 1 && strlen(str_replace(' ', '', $ingame_group)) < 65){
								// OK to update
								if($_POST['primary_group'][$key] == 1)
									$primary = 1;
								else
									$primary = 0;

								$group_id = intval($_POST['website_group'][$key]);

								try {
									$queries->update('group_sync', $key, array(
										'ingame_rank_name' => $ingame_group,
										'website_group_id' => $group_id,
										'`primary`' => $primary
									));
								} catch(Exception $e){
									$errors[] = $e->getMessage();
								}

							}
						}
					}
				}

				Session::flash('api_success', $language->get('admin', 'group_sync_rules_updated_successfully'));
			}

		} else
			$errors = array($language->get('general', 'invalid_token'));
	}

}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('api_success'))
	$smarty->assign(array(
		'SUCCESS' => Session::flash('api_success'),
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));

if(!isset($_GET['view'])){
	// Is the API enabled?
	$api_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
	if(count($api_enabled)){
		$api_enabled = $api_enabled[0]->value;
	} else {
		$queries->create('settings', array(
			'name' => 'use_api',
			'value' => 0
		));
		$api_enabled = '0';
	}

	// Is the legacy API enabled?
	$legacy_api_enabled = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
	if(count($legacy_api_enabled)){
		$legacy_api_enabled = $legacy_api_enabled[0]->value;
	} else {
		$queries->create('settings', array(
			'name' => 'use_legacy_api',
			'value' => 0
		));
		$legacy_api_enabled = '0';
	}

	// Get API key
	$plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
	$plugin_api = $plugin_api[0]->value;

	// Is email verification enabled
	$emails = $queries->getWhere('settings', array('name', '=', 'email_verification'));
	$emails = $emails[0]->value;

	// Is API verification enabled?
	$api_verification = $queries->getWhere('settings', array('name', '=', 'api_verification'));
	$api_verification = $api_verification[0]->value;

	// Is the username sync enabled?
	$username_sync = $queries->getWhere('settings', array('name', '=', 'username_sync'));
	$username_sync = $username_sync[0]->value;

	$smarty->assign(array(
		'PARENT_PAGE' => PARENT_PAGE,
		'DASHBOARD' => $language->get('admin', 'dashboard'),
		'CONFIGURATION' => $language->get('admin', 'configuration'),
		'API' => $language->get('admin', 'api'),
		'PAGE' => PANEL_PAGE,
		'API_INFO' => $language->get('admin', 'api_info'),
		'INFO' => $language->get('general', 'info'),
		'ENABLE_API' => $language->get('admin', 'enable_api'),
		'API_ENABLED' => $api_enabled,
		'API_KEY' => $language->get('admin', 'api_key'),
		'API_KEY_VALUE' => Output::getClean($plugin_api),
		'API_KEY_REGEN_URL' => URL::build('/panel/core/api/', 'action=api_regen'),
		'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
		'CONFIRM_API_REGEN' => $language->get('admin', 'confirm_api_regen'),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no'),
		'CHANGE' => $language->get('general', 'change'),
		'API_URL' => $language->get('admin', 'api_url'),
		'API_URL_VALUE' => rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($plugin_api), '', 'non-friendly'), '/'),
		'COPY' => $language->get('admin', 'copy'),
		'ENABLE_LEGACY_API' => $language->get('admin', 'enable_legacy_api'),
		'LEGACY_API_ENABLED' => $legacy_api_enabled,
		'LEGACY_API_INFO' => $language->get('admin', 'legacy_api_info'),
		'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
		'EMAIL_VERIFICATION_VALUE' => $emails,
		'API_VERIFICATION' => $language->get('admin', 'api_verification'),
		'API_VERIFICATION_VALUE' => $api_verification,
		'API_VERIFICATION_INFO' => $language->get('admin', 'api_verification_info'),
		'USERNAME_SYNC' => $language->get('admin', 'enable_username_sync'),
		'USERNAME_SYNC_INFO' => $language->get('admin', 'enable_username_sync_info'),
		'USERNAME_SYNC_VALUE' => $username_sync,
		'TOKEN' => Token::get(),
		'SUBMIT' => $language->get('general', 'submit'),
		'COPIED' => $language->get('general', 'copied'),
		'GROUP_SYNC' => $language->get('admin', 'group_sync'),
		'GROUP_SYNC_LINK' => URL::build('/panel/core/api/', 'view=group_sync')
	));

	$template_file = 'core/api.tpl';

} else {
	// Get all groups
	$groups = $queries->getWhere('groups', array('id', '<>', 0));
	$template_array = array();
	foreach($groups as $group){
		$template_array[] = array(
			'id' => Output::getClean($group->id),
			'name' => Output::getClean($group->name)
		);
	}

	// Get existing group sync configuration
	$group_sync = $queries->getWhere('group_sync', array('id', '<>', 0));
	$template_groups = array();
	foreach($group_sync as $group){
		$template_groups[] = array(
			'id' => Output::getClean($group->id),
			'ingame' => Output::getClean($group->ingame_rank_name),
			'website' => $group->website_group_id,
			'primary' => $group->primary,
			'delete_link' => URL::build('/panel/core/api/', 'view=group_sync&action=delete&id=' . Output::getClean($group->id))
		);
	}

	$smarty->assign(array(
		'PARENT_PAGE' => PARENT_PAGE,
		'DASHBOARD' => $language->get('admin', 'dashboard'),
		'CONFIGURATION' => $language->get('admin', 'configuration'),
		'API' => $language->get('admin', 'api'),
		'PAGE' => PANEL_PAGE,
		'INFO' => $language->get('general', 'info'),
		'GROUP_SYNC_INFO' => $language->get('admin', 'group_sync_info'),
		'BACK' => $language->get('general', 'back'),
		'BACK_LINK' => URL::build('/panel/core/api'),
		'TOKEN' => Token::get(),
		'SUBMIT' => $language->get('general', 'submit'),
		'INGAME_GROUP_NAME' => $language->get('admin', 'ingame_group'),
		'WEBSITE_GROUP' => $language->get('admin', 'website_group'),
		'SET_AS_PRIMARY_GROUP' => $language->get('admin', 'set_as_primary_group'),
		'SET_AS_PRIMARY_GROUP_INFO' => $language->get('admin', 'set_as_primary_group_info'),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no'),
		'GROUPS' => $template_array,
		'GROUP_SYNC_VALUES' => $template_groups,
		'DELETE' => $language->get('general', 'delete'),
		'NEW_RULE' => $language->get('admin', 'new_rule'),
		'EXISTING_RULES' => $language->get('admin', 'existing_rules')
	));

	$template_file = 'core/api_group_sync.tpl';

}

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);