<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  Modified by Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Initialise the members addon
// We've already checked to see if it's enabled

require('addons/Members/language.php');

// Check cache for link location
$c->setCache('membersaddon');
if($c->isCached('linklocation')){
	$link_location = $c->retrieve('linklocation');
} else {
	$c->store('linklocation', 'navbar');
	$link_location = 'navbar';
}

// Enabled, add links to navbar
switch($link_location){
	case 'navbar':
		$navbar_array[] = array('members' => $members_language['members_icon'] . $members_language['members']);
	break;
	
	case 'footer':
		$footer_nav_array['members'] = $members_language['members_icon'] . $members_language['members'];
	break;
	
	case 'more':
		$nav_members_object = new stdClass();
		$nav_members_object->url = '/members';
		$nav_members_object->icon = $members_language['members_icon'];
		$nav_members_object->title = $members_language['members'];
	
		$nav_more_dropdown[] = $nav_members_object;
	break;
	
	case 'none':
	break;
	
	default:
		$navbar_array[] = array('members' => $members_language['members_icon'] . $members_language['members']);
	break;
}