<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
if(!isset($_GET['c'])){
	Redirect::to('/');
	die();
} else {
	$check = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
	if(count($check)){
		$queries->update('users', $check[0]->id, array(
			'reset_code' => '',
			'active' => 1
		));
		Session::flash('home', '<div class="alert alert-info">' . $user_language['validation_complete'] . '</div>');
		Redirect::to('/');
		die();
	} else {
		Session::flash('home', '<div class="alert alert-danger">' . $user_language['validation_error'] . '</div>');
		Redirect::to('/');
		die();
	}
}
?>