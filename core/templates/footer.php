<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Generate footer
 */

// Get social media icons if enabled
$social_media_icons = array(
	1 => array(
		'short' => 'fb',
		'long' => 'facebook'
	), 
	2 => array(
		'short' => 'tw',
		'long' => 'twitter'
	), 
	3 => array(
		'short' => 'gp',
		'long' => 'google-plus'
	),
	4 => array(
		'short' => 'gp',
		'long' => 'youtube'
	),
	5 => array(
		'short' => 'em',
		'long' => 'envelope'
	)
);

// Smarty template
// Assign to Smarty variables
$smarty->assign(array(
	'SOCIAL_MEDIA_ICONS' => $social_media_icons, 
	'PAGE_LOAD_TIME' => ((isset($page_loading) && $page_loading == '1') ? true : false),
	'FOOTER_NAVIGATION' => $navigation->returnNav('footer')
));
