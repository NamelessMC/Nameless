<?php 
/*
 *	Made by Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Initialise the vote addon
// We've already checked to see if it's enabled

require('addons/Vote/language.php');

// Check cache for link location
$c->setCache('voteaddon');
if($c->isCached('linklocation')){
	$link_location = $c->retrieve('linklocation');
} else {
	$c->store('linklocation', 'navbar');
	$link_location = 'navbar';
}

// Enabled, add links to navbar
switch($link_location){
	case 'navbar':
		$navbar_array[] = array('vote' => $vote_language['vote_icon'] . $vote_language['vote']);
	break;
	
	case 'footer':
		$footer_nav_array['vote'] = $vote_language['vote_icon'] . $vote_language['vote'];
	break;
	
	case 'more':
		$nav_vote_object = new stdClass();
		$nav_vote_object->url = '/vote';
		$nav_vote_object->icon = $vote_language['vote_icon'];
		$nav_vote_object->title = $vote_language['vote'];
	
		$nav_more_dropdown[] = $nav_vote_object;
	break;
	
	case 'none':
	break;
	
	default:
		$navbar_array[] = array('vote' => $vote_language['vote_icon'] . $vote_language['vote']);
	break;
}