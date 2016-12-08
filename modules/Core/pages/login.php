<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Login page
 */

// Set page name variable
$page = 'login';
 
// Requirements
require('core/includes/password.php'); // For password hashing

// Ensure user isn't already logged in
if($user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$custom_usernames = $custom_usernames[0]->value;

// Deal with input
if(Input::exists()){
	// Check form token
	if(Token::check(Input::get('token'))){
		// Valid token
		// Initialise validation
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
			'password' => array('required' => true)
		));
		
		// Check if validation passed
		if($validation->passed()){
			// Validation passed
			// Initialise user class
			$user = new User();
			
			// Did the user check 'remember me'?
			$remember = (Input::get('remember') == 1) ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			// Successful login?
			if($login){
				// Yes
				Session::flash('home', $language->get('user', 'successful_signin'));
				Redirect::to(URL::build('/'));
				die();
			} else {
				// No, output error
				$return_error = array($language->get('user', 'incorrect_details'));
			}
		} else {
			// Validation failed
			$return_error = array();
			foreach($validation->errors() as $error){
				if(strpos($error, 'is required') !== false){
					if(strpos($error, 'username') !== false){
						// Empty username field
						$return_error[] = $language->get('user', 'must_input_username');
					} else if(strpos($error, 'password') !== false){
						// Empty password field
						$return_error[] = $language->get('user', 'must_input_password');
					}
				}
				if(strpos($error, 'active') !== false){
					// Account hasn't been activated
					$return_error[] = $language->get('user', 'inactive_account');
				}
				if(strpos($error, 'banned') !== false){
					// Account is banned
					$return_error[] = $language->get('user', 'account_banned');
				}
			}
		}
	} else {
		// Invalid token
		$return_error = array($language->get('general', 'invalid_token'));
	}
}
?>
<!DOCTYPE html>
<html lang="en">
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

	// Sign in template
	// Generate content
	$smarty->assign(array(
		'USERNAME' => (($custom_usernames == 'false') ? $language->get('user', 'minecraft_username') : $language->get('user', 'username')),
		'USERNAME_INPUT' => Output::getClean(Input::get('username')),
		'PASSWORD' => $language->get('user', 'password'),
		'REMEMBER_ME' => $language->get('user', 'remember_me'),
		'FORGOT_PASSWORD_URL' => URL::build('/forgot_password'),
		'FORGOT_PASSWORD' => $language->get('user', 'forgot_password'),
		'FORM_TOKEN' => Token::generate(),
		'SIGN_IN' => $language->get('general', 'sign_in'),
		'REGISTER_URL' => URL::build('/register'),
		'REGISTER' => $language->get('general', 'register'),
		'ERROR' => (isset($return_error) ? $return_error : array())
	));
	
	$form_content = '
	<div class="form-group">
		<input type="text" name="username" id="username" autocomplete="off" value="' . Output::getClean(Input::get('username')) . '" class="form-control input-lg" placeholder="';
	if($custom_usernames == 'false'){ $form_content .= $language->get('user', 'minecraft_username'); } else { $form_content .= $language->get('user', 'username'); }
	$form_content .= '" tabindex="1">
	</div>' . PHP_EOL . 
	'<div class="form-group">
		<input type="password" name="password" id="password" class="form-control input-lg" placeholder="' . $language->get('user', 'password') . '" tabindex="2">
	</div>' . PHP_EOL . 
	'<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="form-group">
				<label for="remember">
					<input type="checkbox" name="remember" id="remember"> ' . $language->get('user', 'remember_me') . '
				</label>				
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<span class="pull-right"><a class="btn btn-sm btn-primary" href="' . URL::build('/forgot_password') . '">' . $language->get('user', 'forgot_password') . '</a></span>
		</div>
	</div>';

	$submit = '<input type="submit" value="' . $language->get('general', 'sign_in') . '" class="btn btn-primary btn-block btn-lg">';

	$register_url = URL::build('/register');

	// Smarty variables
	$smarty->assign('SIGNIN', $language->get('general', 'sign_in'));
	$smarty->assign('REGISTER_URL', $register_url);
	$smarty->assign('REGISTER', $language->get('general', 'register'));
	$smarty->assign('FORM_CONTENT', $form_content);
	$smarty->assign('FORM_SUBMIT', $submit);

	if(isset($return_error)){
		$smarty->assign('SESSION_FLASH', $return_error);
	} else {
		$smarty->assign('SESSION_FLASH', '');
	}
	
	// Display template
	$smarty->display('custom/templates/' . TEMPLATE . '/login.tpl');

	// Scripts 
	require('core/templates/scripts.php');
	?>
  </body>
</html>