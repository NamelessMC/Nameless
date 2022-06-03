<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Generate footer
 */

// Get social media icons if enabled

$social_media_icons = [];

// Facebook
$social_media = Util::getSetting('fb_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'fb',
        'long' => 'facebook',
        'link' => Output::getClean($social_media),
        'text' => 'Facebook'
    ];
}

// Twitter
$social_media = Util::getSetting('twitter_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'tw',
        'long' => 'twitter',
        'link' => Output::getClean($social_media),
        'text' => 'Twitter'
    ];
}

// Youtube
$social_media = Util::getSetting('youtube_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'gp',
        'long' => 'youtube',
        'link' => Output::getClean($social_media),
        'text' => 'YouTube'
    ];
}

// Smarty template
// Assign to Smarty variables
$smarty->assign([
    'SOCIAL_MEDIA_ICONS' => $social_media_icons,
    'PAGE_LOAD_TIME' => Util::getSetting('page_loading'),
    'FOOTER_NAVIGATION' => $navigation->returnNav('footer')
]);

// Terms
$smarty->assign('TERMS_LINK', URL::build('/terms'));
$smarty->assign('TERMS_TEXT', $language->get('user', 'terms_and_conditions'));

// Privacy
$smarty->assign('PRIVACY_LINK', URL::build('/privacy'));
$smarty->assign('PRIVACY_TEXT', $language->get('general', 'privacy_policy'));
