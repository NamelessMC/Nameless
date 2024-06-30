<?php
/**
 * Footer initialisation.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Language     $language
 * @var Navigation   $navigation
 * @var TemplateBase $template
 */

// Get social media icons if enabled

$social_media_icons = [];

// Facebook
$social_media = Settings::get('fb_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'fb',
        'long' => 'facebook',
        'link' => Output::getClean($social_media),
        'text' => 'Facebook',
    ];
}

// Twitter
$social_media = Settings::get('twitter_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'tw',
        'long' => 'twitter',
        'link' => Output::getClean($social_media),
        'text' => 'Twitter',
    ];
}

// Youtube
$social_media = Settings::get('youtube_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'gp',
        'long' => 'youtube',
        'link' => Output::getClean($social_media),
        'text' => 'YouTube',
    ];
}

// Assign to template variables
$template->getEngine()->addVariables([
    'SOCIAL_MEDIA_ICONS' => $social_media_icons,
    'PAGE_LOAD_TIME' => Settings::get('page_loading'),
    'FOOTER_NAVIGATION' => $navigation->returnNav('footer'),
    'TERMS_LINK' => URL::build('/terms'),
    'TERMS_TEXT' => $language->get('user', 'terms_and_conditions'),
    'PRIVACY_LINK' => URL::build('/privacy'),
    'PRIVACY_TEXT' => $language->get('general', 'privacy_policy'),
]);
