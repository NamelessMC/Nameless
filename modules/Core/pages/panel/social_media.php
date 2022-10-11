<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel social media page
 */

if (!$user->handlePanelPageLoad('admincp.core.social_media')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'social_media';
$page_title = $language->get('admin', 'social_media');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        // Update database values
        // Youtube URL
        Util::setSetting('youtube_url', Input::get('youtubeurl'));

        // Twitter URL
        Util::setSetting('twitter_url', Input::get('twitterurl'));

        // Twitter dark theme
        if (isset($_POST['twitter_dark_theme']) && $_POST['twitter_dark_theme'] == 1) {
            $theme = 'dark';
        } else {
            $theme = 'light';
        }

        Util::setSetting('twitter_style', $theme);

        // Facebook URL
        Util::setSetting('fb_url', Input::get('fburl'));

        Session::flash('social_success', $language->get('admin', 'social_media_settings_updated'));
        Redirect::to(URL::build('/panel/core/social_media'));
    } else {
        // Invalid token
        $errors[] = $language->get('general', 'invalid_token');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('social_success')) {
    $success = Session::flash('social_success');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// Get values from database
$youtube_url = Util::getSetting('youtube_url');
$twitter_url = Util::getSetting('twitter_url');
$twitter_style = Util::getSetting('twitter_style');
$fb_url = Util::getSetting('fb_url');

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'SOCIAL_MEDIA' => $language->get('admin', 'social_media'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'YOUTUBE_URL' => $language->get('admin', 'youtube_url'),
    'YOUTUBE_URL_VALUE' => Output::getClean($youtube_url),
    'TWITTER_URL' => $language->get('admin', 'twitter_url'),
    'TWITTER_URL_VALUE' => Output::getClean($twitter_url),
    'TWITTER_STYLE' => $language->get('admin', 'twitter_dark_theme'),
    'TWITTER_STYLE_VALUE' => $twitter_style,
    'FACEBOOK_URL' => $language->get('admin', 'facebook_url'),
    'FACEBOOK_URL_VALUE' => Output::getClean($fb_url),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/social_media.tpl', $smarty);
