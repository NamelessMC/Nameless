<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  User validation
 */
 
if(!isset($_GET['c'])){
	Redirect::to(URL::build('/'));
	die();
} else {
	$check = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
	if(count($check)){
		$queries->update('users', $check[0]->id, array(
			'reset_code' => '',
			'active' => 1
		));
		Session::flash('home', $language->get('user', 'validation_complete'));
		Redirect::to(URL::build('/'));
		die();
	} else {
		Session::flash('home', $language->get('user', 'validation_error'));
		Redirect::to(URL::build('/'));
		die();
	}
}