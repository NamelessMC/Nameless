<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel social media page
 */

if(!$user->handlePanelPageLoad('admincp.core.social_media')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'social_media');
$page_title = $language->get('admin', 'social_media');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        // Update database values
        // Youtube URL
        $youtube_url_id = $queries->getWhere('settings', ['name', '=', 'youtube_url']);
        $youtube_url_id = $youtube_url_id[0]->id;

        $queries->update('settings', $youtube_url_id, [
            'value' => Output::getClean(Input::get('youtubeurl'))
        ]);

        // Update cache
        $cache->setCache('social_media');
        $cache->store('youtube', Output::getClean(Input::get('youtubeurl')));

        // Twitter URL
        $twitter_url_id = $queries->getWhere('settings', ['name', '=', 'twitter_url']);
        $twitter_url_id = $twitter_url_id[0]->id;

        $queries->update('settings', $twitter_url_id, [
            'value' => Output::getClean(Input::get('twitterurl'))
        ]);

        $cache->store('twitter', Output::getClean(Input::get('twitterurl')));

        // Twitter dark theme
        $twitter_dark_theme = $queries->getWhere('settings', ['name', '=', 'twitter_style']);
        $twitter_dark_theme = $twitter_dark_theme[0]->id;

        if (isset($_POST['twitter_dark_theme']) && $_POST['twitter_dark_theme'] == 1) $theme = 'dark';
        else $theme = 'light';

        $queries->update('settings', $twitter_dark_theme, [
            'value' => $theme
        ]);

        $cache->store('twitter_theme', $theme);

        // Facebook URL
        $fb_url_id = $queries->getWhere('settings', ['name', '=', 'fb_url']);
        $fb_url_id = $fb_url_id[0]->id;
        $queries->update('settings', $fb_url_id, [
            'value' => Output::getClean(Input::get('fburl'))
        ]);

        $cache->store('facebook', Output::getClean(Input::get('fburl')));

        $success = $language->get('admin', 'social_media_settings_updated');
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success))
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);

if (isset($errors) && count($errors))
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);

// Get values from database
$youtube_url = $queries->getWhere('settings', ['name', '=', 'youtube_url']);
$twitter_url = $queries->getWhere('settings', ['name', '=', 'twitter_url']);
$twitter_style = $queries->getWhere('settings', ['name', '=', 'twitter_style']);
$fb_url = $queries->getWhere('settings', ['name', '=', 'fb_url']);

$smarty->assign([
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
    'FACEBOOK_URL' => $language->get('admin', 'facebook_url'),
    'FACEBOOK_URL_VALUE' => Output::getClean($fb_url[0]->value),
]);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/social_media.tpl', $smarty);
