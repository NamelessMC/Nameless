<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(!isset($registration_enabled)){
	// Registration is disabled
	Redirect::to('/');
	die();
}
 
// Registration page

require('core/integration/uuid.php'); // For UUID stuff
require('core/includes/password.php'); // For password hashing

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$custom_usernames = $custom_usernames[0]->value;

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

// Use recaptcha?
$recaptcha = $queries->getWhere("settings", array("name", "=", "recaptcha"));
$recaptcha = $recaptcha[0]->value;

// Deal with any input
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Valid token
		// Validate
		$validate = new Validate();
		
		//TODO deal and validate authme stuff. not familiar
		
		$to_validation = array( // Base field validation
			'password' => array(
				'required' => true,
				'min' => 6,
				'max' => 30
			),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'
			),
			'email' => array(
				'required' => true,
				'min' => 4,
				'max' => 64,
				'unique' => 'users'
			),
			't_and_c' => array(
				'required' => true,
				'agree' => true
			)
		);
		
		if($recaptcha === "true"){ // check Recaptcha response
			$to_validation['g-recaptcha-response'] = array(
				'required' => true
			);
		}
		
		if($uuid_linking == '1'){
			if($custom_usernames == "true"){ // validate username and Minecraft name
				$to_validation['mcname'] = array(
					'required' => true,
					'isvalid' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);
				$to_validation['username'] = array(
					'required' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);
				$mcname = htmlspecialchars(Input::get('mcname'));
			} else { // only validate Minecraft name
				$to_validation['username'] = array(
					'required' => true,
					'isvalid' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);
				$mcname = htmlspecialchars(Input::get('username'));
			}
		} else {
			if($custom_usernames == "true"){ // validate username and Minecraft name
				$to_validation['mcname'] = array(
					'required' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);
				$to_validation['username'] = array(
					'required' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);
				$mcname = htmlspecialchars(Input::get('mcname'));
			} else { // only validate Minecraft name
				$to_validation['username'] = array(
					'required' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);
				$mcname = htmlspecialchars(Input::get('username'));
			}
		}
		
		$validation = $validate->check($_POST, $to_validation); // Execute validation
		
		if($validation->passed()){
			if($uuid_linking == '1'){
				$profile = ProfileUtils::getProfile($mcname);
				$result = $profile->getProfileAsArray();
				if(isset($result["uuid"]) && !empty($result['uuid'])){
					$uuid = $result['uuid'];
				} else {
					$uuid = '';
				}
			} else {
				$uuid = '';
			}
		
			$user = new User();
			
			$ip = $user->getIP();
			if(filter_var($ip, FILTER_VALIDATE_IP)){
				// Valid IP
			} else {
				// TODO: Invalid IP, do something else
			}
			
			$password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));
			// Get current unix time
			$date = new DateTime();
			$date = $date->getTimestamp();
			
			try {
				$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
				$user->create(array(
					'username' => htmlspecialchars(Input::get('username')),
					'mcname' => $mcname,
					'uuid' => $uuid,
					'password' => $password,
					'pass_method' => 'default',
					'joined' => $date,
					'group_id' => 1,
					'email' => htmlspecialchars(Input::get('email')),
					'reset_code' => $code,
					'lastip' => htmlspecialchars($ip)
				));
				
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
					$mail->SMTPAuth = true;
					$mail->Username = $GLOBALS['email']['username'];
					$mail->Password = $GLOBALS['email']['password'];
					$mail->setFrom($GLOBALS['email']['username'], $GLOBALS['email']['name']);
					$mail->From = $GLOBALS['email']['username'];
					$mail->FromName = $GLOBALS['email']['name'];
					$mail->addAddress(htmlspecialchars(Input::get('email')), htmlspecialchars(Input::get('username')));
					$mail->Subject = $sitename . ' - ' . $user_language['register'];
					
					// HTML to display in message
					$html = file_get_contents(ROOT_PATH . '\styles\templates\\' . $template . '\email\register.html');
					
					$link = 'http://' . $_SERVER['SERVER_NAME'] . '/validate/?c=' . $code;
					
					$html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array($sitename, $user_language['register'], $email_language['greeting'], $email_language['message'], $link, $email_language['thanks']), $html);
					
					$mail->msgHTML($html);
					$mail->IsHTML(true);
					$mail->Body = $html;
					//$mail->AltBody = 'Click the following link to complete registration: ' . $link;
					
					if(!$mail->send()) {
						echo "Mailer Error: " . $mail->ErrorInfo;
						die();
					} else {
						echo "Message sent!";
					}
				} else {
					// PHP mail function
					$siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
					$siteemail = $siteemail[0]->value;
					
					$to      = Input::get('email');
					$subject = $sitename . ' - ' . $user_language['register'];
					
					$message = 	$email_language['greeting'] . PHP_EOL .
								$email_language['message'] . PHP_EOL . PHP_EOL . 
								'http://' . $_SERVER['SERVER_NAME'] . '/validate/?c=' . $code . PHP_EOL . PHP_EOL .
								$email_language['thanks'] . PHP_EOL .
								$sitename;
					
					/*
					$message = 'Hello, ' . htmlspecialchars(Input::get('username')) . '
								Thanks for registering!
								In order to complete your registration, please click the following link:
								http://' . $_SERVER['SERVER_NAME'] . '/validate/?c=' . $code . '
								Please note that your account will not be accessible until this action is complete.
								
								Thanks,
								' . $sitename . ' staff.';
					*/
					
					$headers = 'From: ' . $siteemail . "\r\n" .
						'Reply-To: ' . $siteemail . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					mail($to, $subject, $message, $headers);
				}
				
				Session::flash('home', '<div class="alert alert-info alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $user_language['registration_check_email'] . '</div>');
				Redirect::to('/');
				die();
			
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			$error = '<div class="alert alert-danger">' . $user_language['registration_error'] . '</div>';
		}
		
	} else {
		// Invalid token
		Session::flash('register', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
	}
}

if(isset($error)){
	$smarty->assign('REGISTRATION_ERROR', $error);
} else {
	$smarty->assign('REGISTRATION_ERROR', '');
}

// Generate code for page
$form_content = '
<div class="form-group">
	<input type="text" name="username" id="username" autocomplete="off" value="' . escape(Input::get('username')) . '" class="form-control input-lg" placeholder="';
if($custom_usernames == "false"){ $form_content .= $user_language['minecraft_username']; } else { $form_content .= $user_language['username']; }
$form_content .= '" tabindex="1">
</div>
';
// Custom usernames?
if($custom_usernames !== "false"){
$form_content .= '
<div class="form-group">
	<input type="text" name="mcname" id="mcname" autocomplete="off" class="form-control input-lg" placeholder="' . $user_language['minecraft_username'] . '" tabindex="2">
</div>';
}
// Continue
$form_content .= '
<div class="form-group">
	<input type="email" name="email" id="email" value="' . escape(Input::get('email')) . '" class="form-control input-lg" placeholder="' . $user_language['email_address'] . '" tabindex="3">
</div>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="form-group">
			<input type="password" name="password" id="password" class="form-control input-lg" placeholder="' . $user_language['password'] . '" tabindex="4">
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="form-group">
			<input type="password" name="password_again" id="password_again" class="form-control input-lg" placeholder="' . $user_language['confirm_password'] . '" tabindex="5">
		</div>
	</div>
</div>
';
// Recaptcha
if($recaptcha === "true"){
	$recaptcha_key = $queries->getWhere("settings", array("name", "=", "recaptcha_key"));
	$form_content .= '
	<center>
		<div class="g-recaptcha" data-sitekey="' .  htmlspecialchars($recaptcha_key[0]->value) . '"></div>
	</center>
	<br />
	';
}
// Continue
$form_content .= '
<div class="row">
	<div class="col-xs-4 col-sm-3 col-md-3">
		<span class="button-checkbox">
			<button type="button" class="btn" data-color="info" tabindex="7"> ' . $user_language['i_agree'] . '</button>
			<input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
		</span>
	</div>
	<div class="col-xs-8 col-sm-9 col-md-9">
		' . $user_language['agree_t_and_c'] . '
	</div>
</div>
';

$form_submit = '
<div class="row">
	<input type="hidden" name="token" value="' .  Token::generate() . '">
	<div class="col-xs-12 col-md-6"><input type="submit" value="' . $user_language['register'] . '" class="btn btn-primary btn-block btn-lg" tabindex="8"></div>
	<div class="col-xs-12 col-md-6"><a href="/signin" class="btn btn-success btn-block btn-lg">' . $user_language['sign_in'] . '</a></div>
</div>
';

// Session messages
if(Session::exists('register')){
	$smarty->assign('SESSION_FLASH', Session::flash('register'));
} else {
	$smarty->assign('SESSION_FLASH', '');
}

$smarty->assign('CREATE_AN_ACCOUNT', $user_language['create_an_account']);
$smarty->assign('FORM_CONTENT', $form_content);
$smarty->assign('FORM_SUBMIT', $form_submit);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> registration form">
    <meta name="author" content="Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo $user_language['register']; ?></title>
	
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

// Registration template
$smarty->display('styles/templates/' . $template . '/register.tpl');

// HTML Purifier
require_once('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
$config->set('URI.DisableExternalResources', false);
$config->set('URI.DisableResources', false);
$config->set('HTML.Allowed', 'u,p,b,i,a,s');
$config->set('HTML.AllowedAttributes', 'target, href');
$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
$purifier = new HTMLPurifier($config);
?>
<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="t_and_c_m_Label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="t_and_c_m_Label"><?php echo $user_language['terms_and_conditions']; ?></h4>
			</div>
			<div class="modal-body">
				<?php 
				$t_and_c = $queries->getWhere("settings", array("name", "=", "t_and_c"));
				echo $purifier->purify(htmlspecialchars_decode($t_and_c[0]->value));
				$t_and_c = $queries->getWhere("settings", array("name", "=", "t_and_c_site"));
				echo $purifier->purify(htmlspecialchars_decode($t_and_c[0]->value));
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $general_language['close']; ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
// Footer
require('core/includes/template/footer.php');
$smarty->display('styles/templates/' . $template . '/footer.tpl');

// Scripts 
require('core/includes/template/scripts.php');
 
if($recaptcha === "true"){
?>

	<script src="https://www.google.com/recaptcha/api.js"></script>
<?php 
}
?>
	<script>
	$(function () {
		$('.button-checkbox').each(function () {
			// Settings
			var $widget = $(this),
				$button = $widget.find('button'),
				$checkbox = $widget.find('input:checkbox'),
				color = $button.data('color'),
				settings = {
					on: {
						icon: 'glyphicon glyphicon-check'
					},
					off: {
						icon: 'glyphicon glyphicon-unchecked'
					}
				};
			// Event Handlers
			$button.on('click', function () {
				$checkbox.prop('checked', !$checkbox.is(':checked'));
				$checkbox.triggerHandler('change');
				updateDisplay();
			});
			$checkbox.on('change', function () {
				updateDisplay();
			});
			// Actions
			function updateDisplay() {
				var isChecked = $checkbox.is(':checked');
				// Set the button's state
				$button.data('state', (isChecked) ? "on" : "off");
				// Set the button's icon
				$button.find('.state-icon')
					.removeClass()
					.addClass('state-icon ' + settings[$button.data('state')].icon);
				// Update the button's color
				if (isChecked) {
					$button
						.removeClass('btn-default')
						.addClass('btn-' + color + ' active');
				}
				else {
					$button
						.removeClass('btn-' + color + ' active')
						.addClass('btn-default');
				}
			}
			// Initialisation
			function init() {
				updateDisplay();
				// Inject the icon if applicable
				if ($button.find('.state-icon').length == 0) {
					$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
				}
			}
			init();
		});
	});
	</script>
  </body>
</html>
