<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if($user->isLoggedIn()) { // User must be logged out to view this page
	Redirect::to("/");
	die();
} else {

$siteemail = $queries->getWhere("settings", array("name", "=", "outgoing_email"));
$siteemail = $siteemail[0]->value;

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$check = $queries->getWhere('users', array('username', '=', Input::get('username')));
		if(count($check)){
			$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
			
			$php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
			$php_mailer = $php_mailer[0]->value;
			
			if($php_mailer == '1'){
				// PHP mailer
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
					$mail->addAddress(htmlspecialchars($check[0]->email), htmlspecialchars(Input::get('username')));
					$mail->Subject = $sitename . ' - ' . $user_language['password_reset'];
					
					// HTML to display in message
					$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'styles', 'templates', $template, 'email', 'change_password.html'));
					$html = file_get_contents($path);
					
					$link = 'http://' . $_SERVER['SERVER_NAME'] . '/change_password/?c=' . $code;
					
					$html = str_replace(array('[Sitename]', '[ChangePassword]', '[Greeting]', '[Message]', '[Message2]', '[Link]', '[Thanks]'), array($sitename, $user_language['change_password'], $email_language['greeting'], $user_language['email_body'], $user_language['email_body_2'], $link, $email_language['thanks']), $html);
					
					$mail->msgHTML($html);
					$mail->IsHTML(true);
					$mail->Body = $html;
					//$mail->AltBody = 'Click the following link to change your password: ' . $link;
					
					if(!$mail->send()) {
						echo "Mailer Error: " . $mail->ErrorInfo;
						die();
					} else {
						echo "Message sent!";
					}
			} else {
				// PHP mail function
				$to      = $check[0]->email;
				$subject = $user_language['password_reset'];
				$message = $email_language['greeting'] . '
							
							' . $user_language['email_body'] . '

							http://' . $_SERVER['SERVER_NAME'] . '/change_password/?c=' . $code . '
							
							' . $user_language['email_body_2'] . '
							
							' . $email_language['thanks'] . '
							
							' . $sitename . ' staff.';
				$headers = 'From: ' . $siteemail . "\r\n" .
					'Reply-To: ' . $siteemail . "\r\n" .
					'X-Mailer: PHP/' . phpversion() . "\r\n" .
					'MIME-Version: 1.0' . "\r\n" . 
					'Content-type: text/plain; charset=UTF-8' . "\r\n";

				mail($to, $subject, $message, $headers);
			}
			
			$queries->update('users', $check[0]->id, array(
				'reset_code' => $code
			));
			
			Session::flash('home', '<div class="alert alert-info">' . $user_language['password_email_set'] . '</div>');
			Redirect::to("/");	
		} else {
			Session::flash('error', '<div class="alert alert-info">' . $user_language['username_not_found'] . '</div>');
		}
		
	
	} else {
		Session::flash('error', '<div class="alert alert-info">' . $general_language['error'] . '</div>');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Forgot password? &bull; <?php echo $sitename; ?>">
    <meta name="author" content="<?php echo $sitename; ?>n">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?> 
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['forgot_password'];
	
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
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
	
	<div class="container">
		<form action="" method="post">
		  <h2><?php echo $user_language['forgot_password']; ?></h2>
		<?php
		if(Session::exists('error')){
			echo Session::flash('error');
		}
		?>
		  <input class="form-control" type="text" name="username" id="username" placeholder="<?php echo $user_language['username']; ?>" autocomplete="off">				
		  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		  <br />
		  <center><input class="btn btn-primary" type="submit" value="<?php echo $general_language['submit']; ?>"></center>
		</form>
    </div>
		
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	
   </body>
<?php } ?>
