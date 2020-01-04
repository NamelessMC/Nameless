<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel social media page
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
		if(!$user->hasPermission('admincp.core.social_media')){
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
define('PANEL_PAGE', 'social_media');
$page_title = $language->get('admin', 'social_media');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if(Input::exists()){
	$errors = array();

	if(Token::check(Input::get('token'))){
		// Update database values
		// Youtube URL
		$youtube_url_id = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
		$youtube_url_id = $youtube_url_id[0]->id;

		$queries->update('settings', $youtube_url_id, array(
			'value' => Output::getClean(Input::get('youtubeurl'))
		));

		// Update cache
		$cache->setCache('social_media');
		$cache->store('youtube', Output::getClean(Input::get('youtubeurl')));

		// Twitter URL
		$twitter_url_id = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
		$twitter_url_id = $twitter_url_id[0]->id;

		$queries->update('settings', $twitter_url_id, array(
			'value' => Output::getClean(Input::get('twitterurl'))
		));

		$cache->store('twitter', Output::getClean(Input::get('twitterurl')));

		// Twitter dark theme
		$twitter_dark_theme = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
		$twitter_dark_theme = $twitter_dark_theme[0]->id;

		if(isset($_POST['twitter_dark_theme']) && $_POST['twitter_dark_theme'] == 1) $theme = 'dark';
		else $theme = 'light';

		$queries->update('settings', $twitter_dark_theme, array(
			'value' => $theme
		));

		$cache->store('twitter_theme', $theme);

		// Discord ID
		$discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
		$discord_id = $discord_id[0]->id;

		$queries->update('settings', $discord_id, array(
			'value' => Output::getClean(Input::get('discordid'))
		));

		$cache->store('discord', Output::getClean(Input::get('discordid')));

		// Google Plus URL
		$gplus_url_id = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
		$gplus_url_id = $gplus_url_id[0]->id;

		$queries->update('settings', $gplus_url_id, array(
			'value' => Output::getClean(Input::get('gplusurl'))
		));

		$cache->store('google_plus', Output::getClean(Input::get('gplusurl')));

		// Facebook URL
		$fb_url_id = $queries->getWhere('settings', array('name', '=', 'fb_url'));
		$fb_url_id = $fb_url_id[0]->id;
		$queries->update('settings', $fb_url_id, array(
			'value' => Output::getClean(Input::get('fburl'))
		));

		$cache->store('facebook', Output::getClean(Input::get('fburl')));

		// Discord hook
		$discord_url_id = $queries->getWhere('settings', array('name', '=', 'discord_url'));
		$discord_url_id = $discord_url_id[0]->id;

		$queries->update('settings', $discord_url_id, array(
			'value' => Output::getClean(Input::get('discord_url'))
		));

		$discord_hooks_id = $queries->getWhere('settings', array('name', '=', 'discord_hooks'));
		$discord_hooks_id = $discord_hooks_id[0]->id;

		if(isset($_POST['discord_hooks']))
			$hooks = $_POST['discord_hooks'];
		else
			$hooks = array();

		$queries->update('settings', $discord_hooks_id, array(
			'value' => json_encode($hooks)
		));

		$cache->setCache('discord_hook');
		$cache->store('events', $_POST['discord_hooks']);
		$cache->store('url', $_POST['discord_url']);

		$success = $language->get('admin', 'social_media_settings_updated');

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

// Get values from database
$youtube_url = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
$twitter_url = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
$twitter_style = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
$discord = $queries->getWhere('settings', array('name', '=', 'discord'));
$gplus_url = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
$fb_url = $queries->getWhere('settings', array('name', '=', 'fb_url'));
$discord_url = $queries->getWhere('settings', array('name', '=', 'discord_url'));
$discord_hooks = $queries->getWhere('settings', array('name', '=', 'discord_hooks'));
$discord_hooks = json_decode($discord_hooks[0]->value, true);

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'CONFIGURATION' => $language->get('admin', 'configuration'),
	'SOCIAL_MEDIA' => $language->get('admin', 'social_media'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'YOUTUBE_URL' => $language->get('admin', 'youtube_url'),
	'YOUTUBE_URL_VALUE' => Output::getClean($youtube_url[0]->value),
	'TWITTER_URL' => $language->get('admin', 'twitter_url'),
	'TWITTER_URL_VALUE' => Output::getClean($twitter_url[0]->value),
	'TWITTER_STYLE' => $language->get('admin', 'twitter_dark_theme'),
	'TWITTER_STYLE_VALUE' => $twitter_style[0]->value,
	'DISCORD_SERVER_ID' => $language->get('admin', 'discord_id'),
	'DISCORD_SERVER_ID_VALUE' => Output::getClean($discord[0]->value),
	'GOOGLE_PLUS_URL' => $language->get('admin', 'google_plus_url'),
	'GOOGLE_PLUS_URL_VALUE' => Output::getClean($gplus_url[0]->value),
	'FACEBOOK_URL' => $language->get('admin', 'facebook_url'),
	'FACEBOOK_URL_VALUE' => Output::getClean($fb_url[0]->value),
	'DISCORD_HOOKS' => $language->get('admin', 'discord_hooks'),
	'DISCORD_HOOKS_INFO' => $language->get('admin', 'discord_hooks_info'),
	'INFO' => $language->get('general', 'info'),
	'DISCORD_HOOK_URL' => $language->get('admin', 'discord_hook_url'),
	'DISCORD_HOOK_URL_VALUE' => Output::getClean($discord_url[0]->value),
	'DISCORD_HOOK_EVENTS' => $language->get('admin', 'discord_hook_events'),
	'DISCORD_ALL_HOOKS' => HookHandler::getHooks(),
	'DISCORD_ENABLED_HOOKS' => $discord_hooks
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/social_media.tpl', $smarty);