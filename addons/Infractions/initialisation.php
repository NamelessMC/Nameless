<?php 
/*
 *	Made by Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Initialise the infractions addon
// We've already checked to see if it's enabled

// Require language
require('addons/Infractions/language.php');

// Enabled, add links to navbar
$c->setCache('infractionsaddon');
if($c->isCached('linklocation')){
	$link_location = $c->retrieve('linklocation');
} else {
	$c->store('linklocation', 'footer');
	$link_location = 'footer';
}

switch($link_location){
	case 'navbar':
		$navbar_array[] = array('infractions' => $infractions_language['infractions_icon'] . $infractions_language['infractions']);
	break;
	
	case 'footer':
		$footer_nav_array['infractions'] = $infractions_language['infractions_icon'] . $infractions_language['infractions'];
	break;
	
	case 'more':
		$nav_infractions_object = new stdClass();
		$nav_infractions_object->url = '/infractions';
		$nav_infractions_object->icon = $infractions_language['infractions_icon'];
		$nav_infractions_object->title = $infractions_language['infractions'];
	
		$nav_more_dropdown[] = $nav_infractions_object;
	break;
	
	case 'none':
	break;
	
	default:
		$navbar_array[] = array('infractions' => $infractions_language['infractions_icon'] . $infractions_language['infractions']);
	break;
}