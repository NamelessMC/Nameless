<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  License: MIT
 *
 *  Updated by Samerton
 *
 */

// Initialise the ban appeal addon
// We've already checked to see if it's enabled

require('addons/BanAppeal/language.php');

if($user->isLoggedIn()){
	// Check cache for link location
	$c->setCache('banappeal');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}

	// Enabled, add links to navbar
	switch($link_location){
		case 'navbar':
			$navbar_array[] = array('banappeal' => $banappeal_language['ban_appeal_icon'] . $banappeal_language['ban_appeal']);
		break;
		
		case 'footer':
			$footer_nav_array['banappeal'] = $banappeal_language['ban_appeal_icon'] . $banappeal_language['ban_appeal'];
		break;
		
		case 'more':
			$nav_banappeal_object = new stdClass();
			$nav_banappeal_object->url = '/banappeal';
			$nav_banappeal_object->icon = $banappeal_language['ban_appeal_icon'];
			$nav_banappeal_object->title = $banappeal_language['ban_appeal'];
		
			$nav_more_dropdown[] = $nav_banappeal_object;
		break;
		
		case 'none':
		break;
		
		default:
			$navbar_array[] = array('banappeal' => $banappeal_language['ban_appeal_icon'] . $banappeal_language['ban_appeal']);
		break;
	}
	$custom_mod_sidebar['banappeal'] = array(
		'url' => '/mod/banappeal',
		'title' => $banappeal_language['ban_appeal']
	);
}