<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *
 *  License: MIT
 */

// Always define page name
define('PAGE', 'index');
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('general', 'home');
	require('core/templates/header.php'); 
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>
  
  </head>
  <body>
    <?php 
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	
	if(isset($front_page_modules)){
		foreach($front_page_modules as $module){
			require($module);
		}
	}
	
	// Twitter feed?
	$cache->setCache('social_media');
	$twitter = $cache->retrieve('twitter');
	
	if($twitter){
		$theme = $cache->retrieve('twitter_theme');
		
		$twitter = '<a class="twitter-timeline" ' . (($theme == 'dark') ? 'data-theme="dark" ' : '') . ' data-height="600" href="' . Output::getClean($twitter) . '">Tweets</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
	}
	
	// Assign to Smarty variables
	$smarty->assign(array(
		'SOCIAL' => $language->get('general', 'social'),
		'TWITTER' => (isset($twitter) ? $twitter : false)
	));
	
	// Display template
	$smarty->display('custom/templates/' . TEMPLATE . '/index.tpl');

	require('core/templates/scripts.php');
	?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	
  </body>
</html>