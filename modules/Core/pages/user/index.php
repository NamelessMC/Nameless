<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  UserCP overview
 */

// Must be logged in
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Always define page name for navbar
define('PAGE', 'cc_overview');

require('core/templates/cc_navbar.php');
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
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

	$user_details = array(
		$language->get('user', 'username') => $user->data()->username,
		$language->get('admin', 'group') => $user->getGroupName($user->data()->group_id),
		$language->get('admin', 'registered') => date('d M Y, H:i', $user->data()->joined)
	);
	
	// Language values
	$smarty->assign(array(
		'USER_CP' => $language->get('user', 'user_cp'),
		'USER_DETAILS' => $language->get('user', 'user_details'),
		'USER_DETAILS_VALUES' => $user_details,
		'OVERVIEW' => $language->get('user', 'overview')
	));
	
	$smarty->display('custom/templates/' . TEMPLATE . '/user/index.tpl');

    require('core/templates/scripts.php');

	?>
	
  </body>
</html>