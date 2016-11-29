<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  UserCP messaging page
 */

// Always define page name for navbar
define('PAGE', 'cc_messaging');

require('core/templates/cc_navbar.php');
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
	$title = $language->get('user', 'user_cp');
	require('core/templates/header.php'); 
	?>
  
  </head>
  <body>
    <?php
	require('core/templates/navbar.php');
	require('core/templates/footer.php');

	// Language values
	$smarty->assign(array(
		'USER_CP' => $language->get('user', 'user_cp'),
		'MESSAGING' => $language->get('user', 'messaging')
	));
	
	$smarty->display('custom/templates/' . TEMPLATE . '/user/messaging.tpl');

    require('core/templates/scripts.php');

	?>
	
  </body>
</html>