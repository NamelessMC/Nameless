<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Image uploads
 */

// Initialisation
$page = 'image_uploads';
define('ROOT_PATH', '../..');

require(ROOT_PATH . '/core/init.php');

// Require Bulletproof
require(ROOT_PATH . '/core/includes/bulletproof/bulletproof.php');

// Deal with input
if(Input::exists()){
	// Check token
	if(Token::check(Input::get('token'))){
		// Token valid
		$image = new Bulletproof\Image($_FILES);
		$image->setSize(1, 2097152); // between 1b and 2mb
		$image->setDimension(2000, 2000); // 2k x 2k pixel maximum
		$image->setMime(array('jpg', 'png', 'gif', 'jpeg'));
		
		if(Input::get('type') == 'background'){
			$image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'backgrounds')));
		} else {
			$image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads')));
		}

		if($image['file']){
			$upload = $image->upload();

			if($upload){
				// OK
			} else {
				// echo $image["error"]; 
			}
		}
		
	} else {
		// Invalid token
		if(Input::get('type') == 'background'){
			Session::flash('admin_images', '<div class="alert alert-danger">' . $language->get('invalid_token') . '</div>');
		}
	}
}