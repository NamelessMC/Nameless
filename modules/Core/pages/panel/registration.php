<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel registration page
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
		if(!$user->hasPermission('admincp.core.registration')){
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
define('PANEL_PAGE', 'registration');
$page_title = $language->get('admin', 'registration');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if(Input::exists()){
	$errors = array();

	// Check token
	if(Token::check(Input::get('token'))){
		// Valid token
		// Process input
		if(isset($_POST['enable_registration'])){
			// Either enable or disable registration
			$enable_registration_id = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
			$enable_registration_id = $enable_registration_id[0]->id;

			$queries->update('settings', $enable_registration_id, array(
				'value' => Input::get('enable_registration')
			));
		} else {
			// Registration settings
			if(isset($_POST['verification']) && $_POST['verification'] == 'on')
				$verification = 1;
			else
				$verification = 0;

			$verification_id = $queries->getWhere('settings', array('name', '=', 'email_verification'));
			$verification_id = $verification_id[0]->id;

			// reCAPTCHA enabled?
			if(Input::get('enable_recaptcha') == 1){
				$recaptcha = 'true';
			} else {
				$recaptcha = 'false';
			}
			$recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
			$recaptcha_id = $recaptcha_id[0]->id;
			$queries->update('settings', $recaptcha_id, array(
				'value' => $recaptcha
			));

			// Login reCAPTCHA enabled?
			if(Input::get('enable_recaptcha_login') == 1){
				$recaptcha = 'true';
			} else {
				$recaptcha = 'false';
			}
			$recaptcha_login = $queries->getWhere('settings', array('name', '=', 'recaptcha_login'));
			$recaptcha_login = $recaptcha_login[0]->id;
			$queries->update('settings', $recaptcha_login, array(
				'value' => $recaptcha
			));

			// reCAPTCHA key
			$recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
			$recaptcha_id = $recaptcha_id[0]->id;
			$queries->update('settings', $recaptcha_id, array(
				'value' => htmlspecialchars(Input::get('recaptcha'))
			));

			// reCAPTCHA secret key
			$recaptcha_secret_id = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
			$recaptcha_secret_id = $recaptcha_secret_id[0]->id;
			$queries->update('settings', $recaptcha_secret_id, array(
				'value' => htmlspecialchars(Input::get('recaptcha_secret'))
			));

			// Registration disabled message
			$registration_disabled_id = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));
			$registration_disabled_id = $registration_disabled_id[0]->id;
			$queries->update('settings', $registration_disabled_id, array(
				'value' => htmlspecialchars(Input::get('message'))
			));

			try {
				$queries->update('settings', $verification_id, array(
					'value' => $verification
				));
			} catch(Exception $e){
				$errors[] = $e->getMessage();
			}

			// Validation group
			$validation_group_id = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
			$validation_action = $validation_group_id[0]->value;
			$validation_action = json_decode($validation_action, true);
			if(isset($validation_action['action']))
				$validation_action = $validation_action['action'];
			else
				$validation_action = 'promote';
			$validation_group_id = $validation_group_id[0]->id;

			$new_value = json_encode(array('action' => $validation_action, 'group' => $_POST['promote_group']));

			try {
				$queries->update('settings', $validation_group_id, array(
					'value' => $new_value
				));

			} catch(Exception $e){
				$errors[] = $e->getMessage();
			}

			$cache->setCache('validate_action');
			$cache->store('validate_action', array('action' => $validation_action, 'group' => $_POST['promote_group']));
		}

		if(!count($errors)){
			$success = $language->get('admin', 'registration_settings_updated');
		}
	} else {
		// Invalid token
		$errors[] = $language->get('general', 'invalid_token');
	}
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

// Check if registration is enabled
$registration_enabled = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
$registration_enabled = $registration_enabled[0]->value;

if($registration_enabled == 1){
	// Is email verification enabled
	$emails = $queries->getWhere('settings', array('name', '=', 'email_verification'));
	$emails = $emails[0]->value;

	// Recaptcha
	$recaptcha_id = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
	$recaptcha_login = $queries->getWhere('settings', array('name', '=', 'recaptcha_login'));
	$recaptcha_key = $queries->getWhere('settings', array('name', '=', 'recaptcha_key'));
	$recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));
	$registration_disabled_message = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));

	// Validation group
	$validation_group = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
	$validation_group = $validation_group[0]->value;
	$validation_group = json_decode($validation_group, true);
	if(isset($validation_group['group']))
		$validation_group = $validation_group['group'];
	else
		$validation_group = 1;

	$smarty->assign(array(
		'EMAIL_VERIFICATION' => $language->get('admin', 'email_verification'),
		'EMAIL_VERIFICATION_VALUE' => $emails,
		'GOOGLE_RECAPTCHA' => $language->get('admin', 'google_recaptcha'),
		'GOOGLE_RECAPTCHA_VALUE' => $recaptcha_id[0]->value,
		'GOOGLE_RECAPTCHA_LOGIN' => $language->get('admin', 'google_recaptcha_login'),
		'GOOGLE_RECAPTCHA_LOGIN_VALUE' => $recaptcha_login[0]->value,
		'RECAPTCHA_SITE_KEY' => $language->get('admin', 'recaptcha_site_key'),
		'RECAPTCHA_SITE_KEY_VALUE' => Output::getClean($recaptcha_key[0]->value),
		'RECAPTCHA_SECRET_KEY' => $language->get('admin', 'recaptcha_secret_key'),
		'RECAPTCHA_SECRET_KEY_VALUE' => Output::getClean($recaptcha_secret[0]->value),
		'REGISTRATION_DISABLED_MESSAGE' => $language->get('admin', 'registration_disabled_message'),
		'REGISTRATION_DISABLED_MESSAGE_VALUE' => Output::getPurified(Output::getDecoded($registration_disabled_message[0]->value)),
		'VALIDATE_PROMOTE_GROUP' => $language->get('admin', 'validation_promote_group'),
		'VALIDATE_PROMOTE_GROUP_INFO' => $language->get('admin', 'validation_promote_group_info'),
		'INFO' => $language->get('general', 'info'),
		'GROUPS' => $queries->getWhere('groups', array('staff', '=', 0)),
		'VALIDATION_GROUP' => $validation_group
	));
}

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'CONFIGURATION' => $language->get('admin', 'configuration'),
	'REGISTRATION' => $language->get('admin', 'registration'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'ENABLE_REGISTRATION' => $language->get('admin', 'enable_registration'),
	'REGISTRATION_ENABLED' => $registration_enabled
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/registration.tpl', $smarty);
