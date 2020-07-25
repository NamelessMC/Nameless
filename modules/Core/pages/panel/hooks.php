<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel hooks page
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
		if(!$user->hasPermission('admincp.core.hooks')){
			require_once(ROOT_PATH . '/404.php');
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
define('PANEL_PAGE', 'hooks');
$page_title = $language->get('admin', 'hooks');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(!isset($_GET['action'])){
	// View all hooks
	
	$hooks_query = $queries->orderAll('hooks', 'id', 'ASC');
	$hooks_array = array();
	if(count($hooks_query)){
		foreach($hooks_query as $hook){
			$hooks_array[] = array(
				'url' => Output::getClean($hook->url),
				'edit_link' => URL::build('/panel/core/hooks/', 'action=edit&id=' . Output::getClean($hook->id)),
				'delete_link' => URL::build('/panel/core/hooks/', 'action=delete&id=' . Output::getClean($hook->id))
			);
		}
	}
	
	$smarty->assign(array(
		'HOOKS_INFO' => $language->get('admin', 'hooks_info'),
		'NEW_HOOK' => $language->get('admin', 'new_hook'),
		'NEW_HOOK_LINK' => URL::build('/panel/core/hooks/', 'action=new'),
		'HOOKS_LIST' => $hooks_array,
		'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
		'CONFIRM_DELETE_HOOK' => $language->get('admin', 'delete_hook'),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no')
	));
	
	$template_file = 'core/hooks.tpl';
} else {
	switch($_GET['action']){
		case 'new':
			// Create new hook
			if(Input::exists()){
				$errors = array();
				if(Token::check(Input::get('token'))){
					// Validate input
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'hook_url' => array(
							'required' => true,
							'min' => 10,
							'max' => 2048
						)
					));
								
					if($validation->passed()){
						$events = array();
						if(isset($_POST['events']) && count($_POST['events'])){
							foreach($_POST['events'] as $event => $value){
								$events[] = $event;
							}
							
							// Save to database
							$queries->create('hooks', array(
								'action' => Output::getClean($_POST['hook_type']),
								'url' => Output::getClean($_POST['hook_url']),
								'events' => json_encode($events)
							));
							
							$cache->setCache('hooks');
							if($cache->isCached('hooks')){
								$cache->erase('hooks');
							}
							
							Redirect::to(URL::build('/panel/core/hooks'));
							die();
						} else {
							$errors[] = $language->get('admin', 'invalid_hook_events');
						}
					} else {
						$errors[] = $language->get('admin', 'invalid_hook_url');
					}
				} else {
					// Invalid token
					$errors[] = $language->get('general', 'invalid_token');
				}
			}
			
			$smarty->assign(array(
				'CREATING_NEW_HOOK' => $language->get('admin', 'creating_new_hook'),
				'HOOK_URL' => $language->get('admin', 'hook_url'),
				'HOOK_TYPE' => $language->get('admin', 'hook_type'),
				'HOOK_EVENTS' => $language->get('admin', 'hook_events'),
				'BACK' => $language->get('general', 'back'),
				'BACK_LINK' => URL::build('/panel/core/hooks'),
				'ALL_HOOKS' => HookHandler::getHooks(),
			));
			
			$template_file = 'core/hooks_new.tpl';
		break;
		case 'edit':
			// Edit hook
			if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
				// Check the hook ID is valid
				Redirect::to(URL::build('/panel/forms'));
				die();
			}

			// Does the hook exist?
			$hook = $queries->getWhere('hooks', array('id', '=', $_GET['id']));
			if(!count($hook)){
				// No, it doesn't exist
				Redirect::to(URL::build('/panel/core/hooks'));
				die();
			}
			$hook = $hook[0];
			
			if(Input::exists()){
				$errors = array();
				if(Token::check(Input::get('token'))){
					// Validate input
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'hook_url' => array(
							'required' => true,
							'min' => 10,
							'max' => 2048
						)
					));
								
					if($validation->passed()){
						$events = array();
						if(isset($_POST['events']) && count($_POST['events'])){
							foreach($_POST['events'] as $event => $value){
								$events[] = $event;
							}
							
							// Save to database
							$queries->update('hooks', $hook->id, array(
								'action' => Output::getClean($_POST['hook_type']),
								'url' => Output::getClean($_POST['hook_url']),
								'events' => json_encode($events)
							));
							
							$cache->setCache('hooks');
							if($cache->isCached('hooks')){
								$cache->erase('hooks');
							}
							Redirect::to(URL::build('/panel/core/hooks'));
							die();
						} else {
							$errors[] = $language->get('admin', 'invalid_hook_events');
						}
					} else {
						$errors[] = $language->get('admin', 'invalid_hook_url');
					}
				} else {
					// Invalid token
					$errors[] = $language->get('general', 'invalid_token');
				}
			}
			
			$smarty->assign(array(
				'EDITING_HOOK' => $language->get('admin', 'editing_hook'),
				'HOOK_URL' => $language->get('admin', 'hook_url'),
				'HOOK_URL_VALUE' => Output::getClean($hook->url),
				'HOOK_TYPE' => $language->get('admin', 'hook_type'),
				'HOOK_TYPE_VALUE' => Output::getClean($hook->action),
				'HOOK_EVENTS' => $language->get('admin', 'hook_events'),
				'BACK' => $language->get('general', 'back'),
				'BACK_LINK' => URL::build('/panel/core/hooks'),
				'ALL_HOOKS' => HookHandler::getHooks(),
				'ENABLED_HOOKS' => json_decode($hook->events, true)
			));
			
			$template_file = 'core/hooks_edit.tpl';
		break;
		case 'delete':
			// Delete Form
			if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
				Redirect::to(URL::build('/panel/core/hooks'));
				die();
			}
			
			try {
				$queries->delete('hooks', array('id', '=', $_GET['id']));
				
				$cache->setCache('hooks');
				if($cache->isCached('hooks')){
					$cache->erase('hooks');
				}
			} catch(Exception $e){
				die($e->getMessage());
			}

			Session::flash('staff_forms', $forms_language->get('forms', 'form_deleted_successfully'));
			Redirect::to(URL::build('/panel/core/hooks'));
			die();
		break;
		default:
			Redirect::to(URL::build('/panel/core/hooks'));
			die();
		break;
	}
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('admin_hooks'))
	$success = Session::flash('admin_hooks');

if(Session::exists('admin_pages_error'))
	$errors[] = Session::flash('admin_pages_error');

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
	'CONFIGURATION' => $language->get('admin', 'configuration'),
	'HOOKS' => $language->get('admin', 'hooks'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);