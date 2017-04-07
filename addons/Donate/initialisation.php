<?php 
/*
 *	Made by Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Initialise the donate addon
// We've already checked to see if it's enabled

// Require language
require('addons/Donate/language.php');

// Enabled, add links to navbar
// Check cache for link location
$c->setCache('donateaddon');
if($c->isCached('linklocation')){
	$link_location = $c->retrieve('linklocation');
} else {
	$c->store('linklocation', 'navbar');
	$link_location = 'navbar';
}

// Enabled, add links to navbar
switch($link_location){
	case 'navbar':
		$navbar_array[] = array('donate' => $donate_language['donate_icon'] . $donate_language['donate']);
	break;
	
	case 'footer':
		$footer_nav_array['donate'] = $donate_language['donate_icon'] . $donate_language['donate'];
	break;
	
	case 'more':
		$nav_donate_object = new stdClass();
		$nav_donate_object->url = '/donate';
		$nav_donate_object->icon = $donate_language['donate_icon'];
		$nav_donate_object->title = $donate_language['donate'];
		
		$nav_more_dropdown[] = $nav_donate_object;
	break;
	
	case 'none':
	break;
	
	default:
		$navbar_array[] = array('donate' => $donate_language['donate_icon'] . $donate_language['donate']);
	break;
}

// Custom CSS
$custom_css[] = '<link href="' . PATH . 'core/assets/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" rel="stylesheet">';
