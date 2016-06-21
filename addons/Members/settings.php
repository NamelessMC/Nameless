<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  and Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Settings for the Members addon

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

// Display information first
?>
<h3>Addon: Members List</h3>
Authors: Partydragen and Samerton<br />
Version: 1.2.1<br />
Description: Adds a page where users can check all registered members along with staff groups<br />