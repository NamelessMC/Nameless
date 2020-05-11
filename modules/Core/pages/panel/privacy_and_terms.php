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
		if(!$user->hasPermission('admincp.core.terms')){
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
define('PANEL_PAGE', 'privacy_and_terms');
$page_title = $language->get('admin', 'privacy_and_terms');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if(Input::exists()){
	$errors = array();

	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'privacy' => array(
				'required' => true,
				'max' => 100000
			),
			'terms' => array(
				'required' => true,
				'max' => 100000
			)
		));

		if($validation->passed()){
			try {
				$privacy_id = $queries->getWhere('privacy_terms', array('name', '=', 'privacy'));
				if(count($privacy_id)){
					$privacy_id = $privacy_id[0]->id;

					$queries->update('privacy_terms', $privacy_id, array(
						'value' => Input::get('privacy')
					));

				} else {
					$queries->create('privacy_terms', array(
						'name' => 'privacy',
						'value' => Input::get('privacy')
					));
				}

				$terms_id = $queries->getWhere('privacy_terms', array('name', '=', 'terms'));
				if(count($terms_id)){
					$terms_id = $terms_id[0]->id;

					$queries->update('privacy_terms', $terms_id, array(
						'value' => Input::get('terms')
					));

				} else {
					$queries->create('privacy_terms', array(
						'name' => 'terms',
						'value' => Input::get('terms')
					));
				}

				$success = $language->get('admin', 'terms_updated');

			} catch (Exception $e){
				$errors[] = $e->getMessage();
			}

		} else {
			foreach($validation->errors() as $error){
				if(strpos($error, 'terms') !== false){
					$errors[] = $language->get('admin', 'terms_error');
				} else {
					$errors[] = $language->get('admin', 'privacy_policy_error');
				}
			}
		}

	} else
		$errors[] = $language->get('general', 'invalid_token');
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

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

// Get privacy policy + terms
$site_terms = $queries->getWhere('privacy_terms', array('name', '=', 'terms'));
if(!count($site_terms)){
	$site_terms = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
}
$site_terms = $site_terms[0]->value;

$site_privacy = $queries->getWhere('privacy_terms', array('name', '=', 'privacy'));
if(!count($site_privacy)){
	$site_privacy = $queries->getWhere('settings', array('name', '=', 'privacy_policy'));
}
$site_privacy = $site_privacy[0]->value;

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'CONFIGURATION' => $language->get('admin', 'configuration'),
	'PRIVACY_AND_TERMS' => $language->get('admin', 'privacy_and_terms'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'PRIVACY_POLICY' => $language->get('general', 'privacy_policy'),
	'PRIVACY_POLICY_VALUE' => Output::getPurified($site_privacy),
	'TERMS_AND_CONDITIONS' => $language->get('user', 'terms_and_conditions'),
	'TERMS_AND_CONDITIONS_VALUE' => Output::getPurified($site_terms)
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/privacy_and_terms.tpl', $smarty);