<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Panel images page
 */

// Can the user view the panel?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if(!$user->isAdmLoggedIn()){
		// Needs to authenticate
		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if(!$user->hasPermission('admincp.styles.images')){
			require_once(ROOT_PATH . '/403.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'layout');
define('PANEL_PAGE', 'images');
$page_title = $language->get('admin', 'images');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Reset background
if(isset($_GET['action'])){
	if($_GET['action'] == 'reset_bg'){
		$cache->setCache('backgroundcache');
		$cache->store('background_image', '');

		Session::flash('panel_images_success', $language->get('admin', 'background_reset_successfully'));
		Redirect::to(URL::build('/panel/core/images'));
		die();

	} else if($_GET['action'] == 'reset_banner'){
		$cache->setCache('backgroundcache');
		$cache->store('banner_image', '');

		Session::flash('panel_images_success', $language->get('admin', 'template_banner_reset_successfully'));
		Redirect::to(URL::build('/panel/core/images'));
		die();
	}
}

// Deal with input
if(Input::exists()){
	// Check token
	if(Token::check(Input::get('token'))){
		// Valid token
		$cache->setCache('backgroundcache');

		if(isset($_POST['bg'])){
			$cache->store('background_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/backgrounds/' . Input::get('bg'));

			Session::flash('panel_images_success', $language->get('admin', 'background_updated_successfully'));

		} else if(isset($_POST['banner'])){
			$cache->store('banner_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/template_banners/' . Input::get('banner'));

			Session::flash('panel_images_success', $language->get('admin', 'template_banner_updated_successfully'));

		}

		Redirect::to(URL::build('/panel/core/images'));
		die();

	} else {
		// Invalid token
		$errors = array($language->get('general', 'invalid_token'));
	}
}


// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(Session::exists('panel_images_success'))
	$success = Session::flash('panel_images_success');

if(isset($success))
	$smarty->assign(array(
		'SUCCESS' => $success,
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors
	));

// Get background from cache
$cache->setCache('backgroundcache');
$background_image = $cache->retrieve('background_image');

if($background_image == ''){
	$bg_img = $language->get('general', 'none');
} else {
	$bg_img = Output::getClean($background_image);
}

// Get background from cache
if(!$cache->isCached('banner_image')){
	$cache->store('banner_image', (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/homepage_bg_trimmed.jpg');
	$banner_image = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/homepage_bg_trimmed.jpg';
} else {
	$banner_image = $cache->retrieve('banner_image');
}

if($banner_image == ''){
	$banner_img = $language->get('general', 'none');
} else {
	$banner_img = Output::getClean($banner_image);
}

$image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'backgrounds'));
$images = scandir($image_path);
$template_images = array();

// Only display jpeg, png, jpg, gif
$allowed_exts = array('gif', 'png', 'jpg', 'jpeg');
$n = 1;

foreach($images as $image){
	$ext = pathinfo($image, PATHINFO_EXTENSION);
	if(!in_array($ext, $allowed_exts)){
		continue;
	}
	$template_images[] = array(
		'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/backgrounds/' . $image,
		'value' => $image,
		'selected' => ($background_image == (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/backgrounds/' . $image),
		'n' => $n
	);
	$n++;
}

$image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'template_banners'));
$images = scandir($image_path);
$template_banner_images = array();

$n = 1;

foreach($images as $image){
	$ext = pathinfo($image, PATHINFO_EXTENSION);
	if(!in_array($ext, $allowed_exts)){
		continue;
	}
	$template_banner_images[] = array(
		'src' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/' . $image,
		'value' => $image,
		'selected' => ($banner_image == (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/uploads/template_banners/' . $image),
		'n' => $n
	);
	$n++;
}

if(!is_writable(ROOT_PATH . '/uploads/backgrounds')){
	$smarty->assign('BACKGROUNDS_NOT_WRITABLE', $language->get('admin', 'background_directory_not_writable'));
}

if(!is_writable(ROOT_PATH . '/uploads/template_banners')){
	$smarty->assign('TEMPLATE_BANNERS_DIRECTORY_NOT_WRITABLE', $language->get('admin', 'template_banners_directory_not_writable'));
}

$smarty->assign(array(
	'PARENT_PAGE' => PARENT_PAGE,
	'DASHBOARD' => $language->get('admin', 'dashboard'),
	'LAYOUT' => $language->get('admin', 'layout'),
	'IMAGES' => $language->get('admin', 'images'),
	'PAGE' => PANEL_PAGE,
	'TOKEN' => Token::get(),
	'SUBMIT' => $language->get('general', 'submit'),
	'UPLOAD_NEW_IMAGE' => $language->get('admin', 'upload_new_image'),
	'UPLOAD_PATH' => (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/includes/image_upload.php',
	'CLOSE' => $language->get('general', 'close'),
	'BACKGROUND_IMAGE' => str_replace('{x}', $bg_img, $language->get('admin', 'background_image_x')),
	'RESET' => $language->get('admin', 'reset_background'),
	'RESET_LINK' => URL::build('/panel/core/images/', 'action=reset_bg'),
	'RESET_BANNER' => $language->get('admin', 'reset_banner'),
	'RESET_BANNER_LINK' => URL::build('/panel/core/images/', 'action=reset_banner'),
	'BACKGROUND_IMAGES_ARRAY' => $template_images,
	'BANNER_IMAGES_ARRAY' => $template_banner_images,
	'BANNER_IMAGE' => str_replace('{x}', $banner_img, $language->get('admin', 'banner_image_x')),
	'ERRORS_TITLE' => $language->get('general', 'error')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/images.tpl', $smarty);