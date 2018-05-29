<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Home page
 */

// Always define page name
define('PAGE', 'index');
?>
<!DOCTYPE html>
<html<?php if(defined('HTML_CLASS')) echo ' class="' . HTML_CLASS . '"'; ?> lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('general', 'home');

	require(ROOT_PATH . '/core/templates/header.php');
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>

  </head>
  <body>
    <?php
	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	if(Session::exists('home')){
	    $smarty->assign('HOME_SESSION_FLASH', Session::flash('home'));
    }
    if(Session::exists('home_error')){
        $smarty->assign('HOME_SESSION_ERROR_FLASH', Session::flash('home_error'));
    }


	if(isset($front_page_modules)){
		foreach($front_page_modules as $module){
			require(ROOT_PATH . '/' . $module);
		}
	}
	
	// Assign to Smarty variables
	$smarty->assign('SOCIAL', $language->get('general', 'social'));

	// Display template
	$smarty->display(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/index.tpl');

	require(ROOT_PATH . '/core/templates/scripts.php');

	?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	
  </body>
</html>