<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Ensure user is logged in, and is admin
if($user->isLoggedIn()){
	if($user->canViewACP($user->data()->id)){
		if($user->isAdmLoggedIn()){
			// Can view
		} else {
			Redirect::to('/admin');
			die();
		}
	} else {
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}

if(!isset($_GET["uid"])){
	Redirect::to('/admin/users');
	die();
}

$siteemail = $queries->getWhere("settings", array("name", "=", "outgoing_email"));
$siteemail = $siteemail[0]->value;
$individual = $queries->getWhere("users", array("id", "=", $_GET["uid"]));

if(count($individual)){
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
		$mail->addAddress(htmlspecialchars($individual[0]->email), htmlspecialchars($individual[0]->username));
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
		$to      = $individual[0]->email;
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
	
	$queries->update('users', $individual[0]->id, array(
		'reset_code' => $code
	));
	
	Session::flash('adm-users', '<div class="alert alert-info">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['task_successful'] . '</div>');
	Redirect::to('/admin/users/?user=' . $individual[0]->id);
	die();
}
Redirect::to('/admin/users');
die();