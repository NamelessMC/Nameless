<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Ensure user is logged in, and is admin
if($user->isLoggedIn()){
	if($user->canViewACP($user->data()->id)){
		if($user->isAdmLoggedIn()){
			// Can view
		} else {
			Redirect::to('/admin');
			die();
		}
	} else {
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}
 
// Set page name for sidebar
$adm_page = "update";

// Ensure an update is needed
$update_needed = $queries->getWhere('settings', array('name', '=', 'version_update'));
$update_needed = $update_needed[0]->value;

if($update_needed != 'true'){
	Redirect::to('/admin/update');
	die();
}

// Get the current version
$current_version = $queries->getWhere('settings', array('name', '=', 'version'));
$current_version = $current_version[0]->value;

// Perform the update
require('core/includes/updates/' . str_replace('.', '', $current_version) . '.php');

Redirect::to('/admin/update');