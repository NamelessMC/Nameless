<?php 
/*
 *	Made by Partydragen, edited by relavis
 *  http://partydragen.com/
 *
 *  License: MIT
 *
 *  Updated by Samerton
 *
 */

// Initialise the bug report addon
// We've already checked to see if it's enabled

require('addons/BugReport/language.php');

if($user->isLoggedIn()){
	// Check cache for link location
	$c->setCache('bugreport');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}

	// Enabled, add links to navbar
	switch($link_location){
		case 'navbar':
			$navbar_array[] = array('bugreport' => $bugreport_language['bug_report_icon'] . $bugreport_language['bug_report']);
		break;
		
		case 'footer':
			$footer_nav_array['bugreport'] = $bugreport_language['bug_report_icon'] . $bugreport_language['bug_report'];
		break;
		
		case 'more':
			$nav_bugreport_object = new stdClass();
			$nav_bugreport_object->url = '/bugreport';
			$nav_bugreport_object->icon = $bugreport_language['bug_report_icon'];
			$nav_bugreport_object->title = $bugreport_language['bug_report'];
		
			$nav_more_dropdown[] = $nav_bugreport_object;
		break;
		
		case 'none':
		break;
		
		default:
			$navbar_array[] = array('bugreport' => $bugreport_language['bug_report_icon'] . $bugreport_language['bug_report']);
		break;
	}
	$custom_mod_sidebar['bugreport'] = array(
		'url' => '/mod/bugreport',
		'title' => $bugreport_language['bug_report']
	);
}