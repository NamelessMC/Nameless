<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr7
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

		// Discord integration is enabled
		if ($queries->getWhere('settings', array('name', '=', 'discord_integration'))[0]->value == '1') {
			// They have a valid discord Id
			if ($user->data()->discord_id != null && $user->data()->discord_id != 010) {
				$group_discord_id = $queries->getWhere('groups', array('id', '=', $user->data()->group_id))[0]->discord_role_id;

				if ($group_discord_id != null) {
					$bot_url = BOT_URL;
					$api_key = $queries->getWhere('settings', array('name', '=', 'mc_api_key'))[0]->value;
					$api_url = rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($api_key), '', 'non-friendly'), '/');
					$full_url = $bot_url . '/roleChange?id=' . $user->data()->discord_id . '&guild_id=' . $queries->getWhere('settings', array('name', '=', 'discord'))[0]->value . '&ole=' . $group_discord_id;
					file_get_contents($full_url);
				} 
			}
		}

		Session::flash('home', $language->get('user', 'validation_complete'));
		Redirect::to(URL::build('/'));
		die();
	} else {
		Session::flash('home_error', $language->get('user', 'validation_error'));
		Redirect::to(URL::build('/'));
		die();
	}
}
