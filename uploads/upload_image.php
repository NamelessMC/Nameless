<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Image upload handler
 */
 
define('ROOT_PATH', realpath(__DIR__ . '/..'));
$page = 'image_upload';

require('../core/init.php');

if($user->isLoggedIn()){
	// Require Bulletproof
	require('../core/includes/bulletproof/bulletproof.php');
	
	$image = new Bulletproof\Image($_FILES);
	
	$image->setSize(1000, 2 * 1048576)
		  ->setMime(array('jpeg', 'png', 'gif'))
		  ->setLocation(ROOT_PATH . '/uploads/images/' . $user->data()->id, 0777);
		  
	if($image['upload']){
		$upload = $image->upload();
		
		if(!$upload)
			$message = Output::getClean($image['error']);

		// CKEDITOR
		$funcNum = $_GET['CKEditorFuncNum'] ;

		$CKEditor = $_GET['CKEditor'] ;

		$langCode = $_GET['langCode'] ;

		$url = ((defined('CONFIG_PATH')) ? CONFIG_PATH : '' . '/uploads/images/' . $user->data()->id . '/' . $image->getName() . '.' . $image->getMime());
		
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";

	}
	
} else
	die('You are not logged in!');