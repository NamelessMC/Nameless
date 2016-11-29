<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Log user out
 */

if($user->isLoggedIn()){
	$user->admLogout();
	$user->logout();
	
	Session::flash('home', $language->get('user', 'successfully_logged_out'));
	Redirect::to('/');
} else {
	Redirect::to('/');
}

die(); // Ensure the script is killed