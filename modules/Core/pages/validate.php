<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  User validation
 */

$page = 'validate';
define('PAGE', 'validate');
$page_title = $language->get('general', 'register');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(!isset($_GET['c'])){
	Redirect::to(URL::build('/'));
	die();
} else {
	$check = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
	if(count($check)){
        // API verification
        $api_verification = $queries->getWhere('settings', array('name', '=', 'api_verification'));
        $api_verification = $api_verification[0]->value;

        if($api_verification == '1')
            $reset_code = $check[0]->reset_code;
        else
            $reset_code = null;

		$queries->update('users', $check[0]->id, array(
			'reset_code' => $reset_code,
			'active' => 1
		));

		HookHandler::executeEvent('validateUser', array(
			'event' => 'validateUser',
			'user_id' => $check[0]->id,
			'username' => Output::getClean($check[0]->username),
			'uuid' => Output::getClean($check[0]->uuid),
			'language' => $language
		));

		Session::flash('home', $language->get('user', 'validation_complete'));
		Redirect::to(URL::build('/'));
		die();
	} else {
		Session::flash('home', $language->get('user', 'validation_error'));
		Redirect::to(URL::build('/'));
		die();
	}
}