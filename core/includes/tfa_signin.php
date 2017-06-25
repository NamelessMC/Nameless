<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Two Factor Auth signin page
 */
 
// Two Factor Auth signin
$_SESSION['username'] = Input::get('username');
$_SESSION['password'] = Input::get('password');
$_SESSION['remember'] = Input::get('remember');
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> - community login form">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	<?php 
	$title = $language->get('general', 'sign_in');
    require('core/templates/header.php'); 
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>
  
  <body>
	<?php
	// Generate navbar and footer
	require('core/templates/navbar.php');
	require('core/templates/footer.php');
	
	if(Session::exists('tfa_signin')){
		$smarty->assign('ERROR', Session::flash('tfa_signin'));
	}
	
	// Smarty variables
	$smarty->assign(array(
		'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
		'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
		'TOKEN' => Token::get(),
		'SUBMIT' => $language->get('general', 'submit')
	));
	
	// Display template
	$smarty->display('custom/templates/' . TEMPLATE . '/tfa.tpl');

	// Scripts 
	require('core/templates/scripts.php');
    ?>
  </body>
</html>