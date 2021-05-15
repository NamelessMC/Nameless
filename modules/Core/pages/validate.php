<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
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
	$user = new User($_GET['c'], 'reset_code');
	if($user->data()){
        // API verification
        $api_verification = $queries->getWhere('settings', array('name', '=', 'api_verification'));
        $api_verification = $api_verification[0]->value;

        if($api_verification == '1')
            $reset_code = $user->data()->reset_code;
        else
            $reset_code = null;

		$queries->update('users', $user->data()->id, array(
			'reset_code' => $reset_code,
			'active' => 1
		));

        HookHandler::executeEvent('validateUser', array(
            'event' => 'validateUser',
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayname(),
            'uuid' => Output::getClean($user->data()->uuid),
            'content' => str_replace('{x}', $user->getDisplayname(), $language->get('user', 'user_x_has_validated')),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'language' => $language
        ));

        Discord::updateDiscordRoles($user, [$user->getMainGroup()->id], [], $language, false);

		Session::flash('home', $language->get('user', 'validation_complete'));
		Redirect::to(URL::build('/'));
		die();
	} else {
		Session::flash('home_error', $language->get('user', 'validation_error'));
		Redirect::to(URL::build('/'));
		die();
	}
}
