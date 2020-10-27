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
	if(count($user->data())){
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
			'language' => $language
		));

		// Discord integration is enabled
		$discord_integration = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
		$discord_integration = $discord_integration[0]->value;
		if ($discord_integration == '1') {
			// They have a valid discord Id
			if ($user->data()->discord_id != null && $user->data()->discord_id != 010) {
				$group_discord_id = $queries->getWhere('groups', array('id', '=', $user->getTopGroup()->id));
				$group_discord_id = $group_discord_id[0]->discord_role_id;

				if ($group_discord_id != null) {
					$api_key = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
					$api_key = $api_key[0]->value;
					$api_url = rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($api_key), '', 'non-friendly'), '/');
					$guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
					$url = '/roleChange?id=' . $user->data()->discord_id . '&guild_id=' . $guild_id[0]->value . '&role=' . $group_discord_id . '&api_url='. $api_url;
					$result = Discord::discordBotRequest($url);
					// Purposely ignored checking for errors, but rather add a log instead
					if ($result != 'success') {
						Log::getInstance()->log(Log::action('discord/upon_validation_error'), 'Request error: ' . $result, $user->data()->id);
					}
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
