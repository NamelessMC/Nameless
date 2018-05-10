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
Log::getInstance()->log(Log::Action('user/logout'));

if($user->isLoggedIn()){
	$user->admLogout();
	$user->logout();
	
	Session::flash('home', $language->get('user', 'successfully_logged_out'));
	Redirect::to(URL::build('/'));
} else {
	Redirect::to(URL::build('/'));
}

die(); // Ensure the script is killed