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
	
	// Assign to Smarty variables
	$smarty->assign('SOCIAL', $language->get('general', 'social'));
	?>

	<?php $smarty->assign('TWITTER', '<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/worldscapemc"  data-widget-id="386882684909133824">Tweets</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>'); ?>
	
	<?php $smarty->display('custom/templates/' . TEMPLATE . '/index.tpl'); ?>

    <?php require('core/templates/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	
  </body>
</html>