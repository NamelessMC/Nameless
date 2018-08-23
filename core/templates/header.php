<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Header generation
 */
 
// Set current page URL in session, provided it's not the login page
if(defined('PAGE') && PAGE != 'login' && PAGE != 404){
	if(FRIENDLY_URLS === true){
		$split = explode('?', $_SERVER['REQUEST_URI']);

		if(count($split) > 1)
			$_SESSION['last_page'] = URL::build($split[0], $split[1]);
		else
			$_SESSION['last_page'] = URL::build($split[0]);
	} else 
		$_SESSION['last_page'] = URL::build($_GET['route']);

	if(defined('CONFIG_PATH'))
	    $_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));
}

// Add widgets to Smarty
if(isset($widgets))
	$smarty->assign('WIDGETS', $widgets->getWidgets());
?>
	<!-- Page Title -->
	<title><?php echo $title; ?> &bull; <?php echo SITE_NAME; ?></title>
	
	<meta name="author" content="<?php echo SITE_NAME; ?>">

	<meta name="description" content="<?php if(defined('PAGE_DESCRIPTION') && strlen(PAGE_DESCRIPTION) > 0) echo Output::getClean(PAGE_DESCRIPTION); ?>" />
	<meta name="keywords" content="<?php if(defined('PAGE_KEYWORDS') && strlen(PAGE_KEYWORDS) > 0) echo Output::getClean(PAGE_KEYWORDS); ?>" />

	<meta property="og:title" content="<?php echo $title; ?> &bull; <?php echo SITE_NAME; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo Output::getClean(rtrim(Util::getSelfURL(), '/') . $_SERVER['REQUEST_URI']); ?>" />
	<meta property="og:image" content="<?php echo rtrim(Util::getSelfURL(), '/') . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/img/site_image.png'; ?>" />
	<meta property="og:description" content="<?php if(defined('PAGE_DESCRIPTION') && strlen(PAGE_DESCRIPTION) > 0) echo Output::getClean(PAGE_DESCRIPTION); ?>" />

	<!-- Global CSS -->
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/toastr/toastr.min.css">
	
	<?php if($page == 'admin'){ ?>
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/custom.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/font-awesome.min.css">
	<?php 
	} else {
		foreach($css as $item){
			?>
	<link rel="stylesheet" href="<?php echo $item; ?>">
			<?php
		}
		if(isset($style) && is_array($style)){
			foreach($style as $item){
				echo $item;
			}
		}
	} 
	?>
	
	<?php

	// Background?
	$cache->setCache('backgroundcache');
	$background_image = $cache->retrieve('background_image');
	
	if(!empty($background_image)){
	?>
	<style>
	body {
		background-image: url('<?php echo $background_image; ?>');
		background-repeat: no-repeat;
		background-attachment: fixed;
        background-size: cover;
	}
	</style>
	<?php
	}
	?>