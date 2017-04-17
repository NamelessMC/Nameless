<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Initialise the staff application module
// We've already checked to see if it's enabled

if($user->isLoggedIn()){
	// Check cache for link location
	$c->setCache('staffapps');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}

	// Enabled, add links to navbar
	switch($link_location){
		case 'navbar':
			$navbar_array[] = array('apply' => $navbar_language['staff_apps_icon'] . $navbar_language['staff_apps']);
		break;
		
		case 'footer':
			$footer_nav_array['apply'] = $navbar_language['staff_apps_icon'] . $navbar_language['staff_apps'];
		break;
		
		case 'more':
			$nav_app_object = new stdClass();
			$nav_app_object->url = '/apply';
			$nav_app_object->icon = $navbar_language['staff_apps_icon'];
			$nav_app_object->title = $navbar_language['staff_apps'];

			$nav_more_dropdown[] = $nav_app_object;
		break;
		
		case 'none':
		break;
		
		default:
			$navbar_array[] = array('apply' => $navbar_language['staff_apps_icon'] . $navbar_language['staff_apps']);
		break;
	}
}