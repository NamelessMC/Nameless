<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  and Samerton
 *  http://worldscapemc.co.uk
 *
 *  and MuhsinunC
 *  http://muhsinunc.ml/
 *
 *  License: MIT
 */

// Settings for the CP2 Web Interface addon

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
<h3>Addon: Bungee Admin Tools Web Interface</h3>
Authors: <a href="http://muhsinunc.ml/">MuhsinunC</a>, <a href="https://simonorj.com/">SimonOrJ</a>, <a href="http://partydragen.com/">Partydragen</a>, and <a href="http://worldscapemc.co.uk">Samerton</a><br />
Version: 1.0.0<br />
Description: Adds an online browser to explore Bungee Admin Tools Infractions<br />

<br>