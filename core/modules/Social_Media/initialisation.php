<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Initialise the Social Media module
// We've already checked to see if it's enabled

// Get the enabled social media icons
$youtube_url = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
$twitter_url = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
$gplus_url = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
$fb_url = $queries->getWhere('settings', array('name', '=', 'fb_url'));

// String to contain icons
$social_media_icons = '';

// Youtube
if($youtube_url[0]->value !== 'null'){
	// Enabled
	$social_media_icons .= '<a href="' . htmlspecialchars($youtube_url[0]->value) . '"><i id="social" class="fa fa-youtube-square fa-3x social-gp"></i></a>';
}

// Twitter
if($twitter_url[0]->value !== 'null'){
	// Enabled
	$social_media_icons .= '<a href="' . htmlspecialchars($twitter_url[0]->value) . '"><i id="social" class="fa fa-twitter-square fa-3x social-tw"></i></a>';
	$use_twitter_feed = true;
	
	// Dark theme?
	$twitter_style = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
	if($twitter_style[0]->value == 'dark') $twitter_theme_dark = true;
}

// Google Plus
if($gplus_url[0]->value !== 'null'){
	// Enabled
	$social_media_icons .= '<a href="' . htmlspecialchars($gplus_url[0]->value) . '"><i id="social" class="fa fa-google-plus-square fa-3x social-gp"></i></a>';
}

// Facebook
if($fb_url[0]->value !== 'null'){
	// Enabled
	$social_media_icons .= '<a href="' . htmlspecialchars($fb_url[0]->value) . '"><i id="social" class="fa fa-facebook-square fa-3x social-fb"></i></a>';
}