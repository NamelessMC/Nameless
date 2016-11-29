<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Enable or disable night mode
 */

if(!$user->isLoggedIn() || !$user->canViewACP() || !$user->isAdmLoggedIn()){
	Redirect::to('/');
	die();
}

if($user->data()->night_mode == 1){
	$user->update(array(
		'night_mode' => 0
	));
} else {
	$user->update(array(
		'night_mode' => 1
	));
}

die('OK');