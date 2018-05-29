<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Admin update execute page
 */

// Can the user view the AdminCP?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else {
		// Check the user has re-authenticated
		if(!$user->isAdmLoggedIn()){
			// They haven't, do so now
			Redirect::to(URL::build('/admin/auth'));
			die();
            /*} else if(!$user->hasPermission('admincp.update')){
                // Can't view this page
                require(ROOT_PATH . '/404.php');
                die();*/
        }
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
$page = 'admin';
$admin_page = 'update_execute';

// Ensure an update is needed
$update_needed = $queries->getWhere('settings', array('name', '=', 'version_update'));
$update_needed = $update_needed[0]->value;

if($update_needed != 'true' && $update_needed != 'urgent'){
	Redirect::to(URL::build('/admin/update'));
	die();
}

// Get the current version
$current_version = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
$current_version = $current_version[0]->value;

// Perform the update
if(is_file('core/includes/updates/' . str_replace('.', '', $current_version) . '.php'))
	require(ROOT_PATH . '/core/includes/updates/' . str_replace('.', '', $current_version) . '.php');

Redirect::to(URL::build('/admin/update'));
die();