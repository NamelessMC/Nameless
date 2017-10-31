<?php
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

use \RobThree\Auth\TwoFactorAuth;
 
// Sign in page
require('core/includes/password.php'); // Include the password compatibility functions

if($user->isLoggedIn()){
	Redirect::to('/');
	die();
}

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$custom_usernames = $custom_usernames[0]->value;

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		if(isset($_SESSION['remember'])){
			$_POST['remember'] = $_SESSION['remember'];
			$_POST['username'] = $_SESSION['username'];
			$_POST['password'] = $_SESSION['password'];
			
			unset($_SESSION['remember']);
			unset($_SESSION['username']);
			unset($_SESSION['password']);
		}
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
			'password' => array('required' => true)
		));
		
		if($validation->passed()){
			// Authenticator
			$user_query = $queries->getWhere('users', array('username', '=', htmlspecialchars(Input::get('username'))));
			if(count($user_query)){
				if($user_query[0]->tfa_enabled == 1 && $user_query[0]->tfa_complete == 1){
					if(!isset($_POST['tfa_code'])){
						if($user_query[0]->tfa_type == 0){
							// Generate code
							$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
							
							$queries->update('users', $user_query[0]->id, array(
								'tfa_secret' => $code . ':' . date('U')
							));
							
							// Send the email
							$php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
							$php_mailer = $php_mailer[0]->value;
							
							if($php_mailer == '1'){
								// PHP Mailer
								require('core/includes/phpmailer/PHPMailerAutoload.php');
								require('core/email.php');
								
								$mail = new PHPMailer;
								$mail->IsSMTP(); 
								$mail->SMTPDebug = 0;
								$mail->Debugoutput = 'html';
								$mail->Host = $GLOBALS['email']['host'];
								$mail->Port = $GLOBALS['email']['port'];
								$mail->SMTPSecure = $GLOBALS['email']['secure'];
								$mail->SMTPAuth = $GLOBALS['email']['smtp_auth'];
								$mail->Username = $GLOBALS['email']['username'];
								$mail->Password = $GLOBALS['email']['password'];
								$mail->setFrom($GLOBALS['email']['username'], $GLOBALS['email']['name']);
								$mail->From = $GLOBALS['email']['username'];
								$mail->FromName = $GLOBALS['email']['name'];
								$mail->addAddress(htmlspecialchars($user_query[0]->email), htmlspecialchars($user_query[0]->username));
								$mail->Subject = $sitename . ' - ' . $user_language['two_factor_authentication'];
								
								// HTML to display in message
								$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'styles', 'templates', $template, 'email', 'tfa.html'));
								$html = file_get_contents($path);
								
								$link = 'http://' . $_SERVER['SERVER_NAME'] . '/validate/?c=' . $code;
								
								$html = str_replace(array('[Sitename]', '[tfa]', '[Greeting]', '[Message]', '[Code]', '[Thanks]'), array($sitename, $user_language['two_factor_authentication'], $email_language['greeting'], $user_language['tfa_email_contents'], $code, $email_language['thanks']), $html);
								
								$mail->msgHTML($html);
								$mail->IsHTML(true);
								$mail->Body = $html;
								
								if(!$mail->send()) {
									echo "Mailer Error: " . $mail->ErrorInfo;
									die();
								}
								
							} else {
								// PHP mail function
								$siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
								$siteemail = $siteemail[0]->value;
								
								$to      = $user_query[0]->email;
								$subject = $sitename . ' - ' . $user_language['two_factor_authentication'];
								
								$message = 	$email_language['greeting'] . PHP_EOL .
											$user_language['tfa_email_contents'] . PHP_EOL . PHP_EOL . 
											$code . PHP_EOL . PHP_EOL .
											$email_language['thanks'] . PHP_EOL .
											$sitename;
								
								$headers = 'From: ' . $siteemail . "\r\n" .
									'Reply-To: ' . $siteemail . "\r\n" .
									'X-Mailer: PHP/' . phpversion() . "\r\n" .
									'MIME-Version: 1.0' . "\r\n" . 
									'Content-type: text/plain; charset=UTF-8' . "\r\n";
								
								mail($to, $subject, $message, $headers);
							}
							
						}
						
						// Ask user to input code now
						require('core/includes/tfa_signin.php');
						die();
						
					} else {
						// Verify code
						if($user_query[0]->tfa_type == 1){
							// App
							$tfa = new TwoFactorAuth('NamelessMC');
						
							if($tfa->verifyCode($user_query[0]->tfa_secret, $_POST['tfa_code']) !== true){
								Session::flash('tfa_signin', '<div class="alert alert-danger">' . $user_language['invalid_tfa'] . '</div>');
								require('core/includes/tfa_signin.php');
								die();
							}
							
						} else {
							// Email
							// Get the code
							$code = $user_query[0]->tfa_secret;
							$code = explode(':', $code);
							
							// Check it hasn't expired
							if($code[1] < strtotime("-10 minutes")){
								// Expired
								Session::flash('signin', '<div class="alert alert-danger">' . $user_language['invalid_tfa'] . '</div>');
								Redirect::to('/signin');
								die();
							}
							
							// Check code matches
							if($code[0] !== $_POST['tfa_code']){
								Session::flash('tfa_signin', '<div class="alert alert-danger">' . $user_language['invalid_tfa'] . '</div>');
								require('core/includes/tfa_signin.php');
								die();
							}
						}
					}
				}
			}
			
			$user = new User();
			
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			if($login){
				Session::flash('home', '<div class="alert alert-info">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $user_language['successful_signin'] . '</div>');
				Redirect::to('/');
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

$submit = '<button type="submit" class="btn btn-success btn-block btn-lg">' . $user_language['sign_in'] . '</button>';

// Smarty variables
$smarty->assign('SIGNIN', $user_language['sign_in']);
$smarty->assign('REGISTER', $user_language['register']);
$smarty->assign('FORM_CONTENT', $form_content);
$smarty->assign('FORM_SUBMIT', $submit);

if(Session::exists('signin')) $return_error = Session::flash('signin');

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
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['sign_in'];
	
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
