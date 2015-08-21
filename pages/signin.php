<?php
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Sign in page
require('core/includes/password.php'); // Include the password compatibility functions

if($user->isLoggedIn()){
	Redirect::to('/');
	die();
}

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$custom_usernames = $custom_usernames[0]->value;

if(Input::exists()) {
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
			'password' => array('required' => true)
		));
		
		if($validation->passed()){
			$user = new User();
			
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			if($login){
				Session::flash('home', '<div class="alert alert-info">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $user_language['successful_signin'] . '</div>');
				Redirect::to("/");
				die();
			} else {
				$return_error = '<div class="alert alert-danger">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $user_language['incorrect_details'] . '</div>';
			}
		} else {
			$return_error = '<div class="alert alert-danger">';
			foreach($validation->errors() as $error){
				if(strpos($error, 'is required') !== false){
					if(strpos($error, 'username') !== false){
						$return_error .= $user_language['must_input_username'] . '<br />';
					} else if(strpos($error, 'password') !== false){
						$return_error .= $user_language['must_input_password'] . '<br />';
					}
				}
				if(strpos($error, 'active') !== false){
					$return_error .= $user_language['inactive_account'] . '<br />';
				}
				if(strpos($error, 'banned') !== false){
					$return_error .= $user_language['account_banned'] . '<br />';
				}
			}
			$return_error .= '</div>';
		}
	} else {
		// Invalid token
		$return_error = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
	}
}

// Generate code for page
$form_content = '
<div class="form-group">
	<input type="text" name="username" id="username" autocomplete="off" value="' . escape(Input::get('username')) . '" class="form-control input-lg" placeholder="';
if($custom_usernames == "false"){ $form_content .= $user_language['minecraft_username']; } else { $form_content .= $user_language['username']; }
$form_content .= '" tabindex="1">
</div>' . PHP_EOL . 
'<div class="form-group">
    <input type="password" name="password" id="password" class="form-control input-lg" placeholder="' . $user_language['password'] . '" tabindex="2">
</div>' . PHP_EOL . 
'<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="form-group">
			<label for="remember">
				<input type="checkbox" name="remember" id="remember"> ' . $user_language['remember_me'] . '
			</label>				
		</div>
	</div>
	<div class="col-xs-12 col-md-6">
		<span class="pull-right"><a class="btn btn-sm btn-primary" href="/forgot_password">' . $user_language['forgot_password'] . '</a></span>
	</div>
</div>' . PHP_EOL . 
'<input type="hidden" name="token" value="' .  Token::generate() . '">';

$submit = '<input type="submit" value="' . $user_language['sign_in'] . '" class="btn btn-primary btn-block btn-lg">';

// Smarty variables
$smarty->assign('SIGNIN', $user_language['sign_in']);
$smarty->assign('REGISTER', $user_language['register']);
$smarty->assign('FORM_CONTENT', $form_content);
$smarty->assign('FORM_SUBMIT', $submit);

if(isset($return_error)){
	$smarty->assign('SESSION_FLASH', $return_error);
} else {
	$smarty->assign('SESSION_FLASH', '');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> sign in page">
    <meta name="author" content="Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo $user_language['sign_in']; ?></title>
	
	<?php
	// Generate header and navbar content
	require('core/includes/template/generate.php');
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
// Navbar
$smarty->display('styles/templates/' . $template . '/navbar.tpl');

// Sign in template
$smarty->display('styles/templates/' . $template . '/signin.tpl');

// Footer
require('core/includes/template/footer.php');
$smarty->display('styles/templates/' . $template . '/footer.tpl');

// Scripts 
require('core/includes/template/scripts.php');
?>
  </body>
</html>