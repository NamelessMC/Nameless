<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(!isset($registration_enabled)){
	// Registration is disabled
	Session::flash('home', '<div class="alert alert-info">' . $user_language['registration_disabled'] . '</div>');
	Redirect::to('/');
	die();
}
 
// Registration page

require('core/integration/uuid.php'); // For UUID stuff
require('core/includes/password.php'); // For password hashing
require('core/includes/validate_date.php'); // For date validation

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$custom_usernames = $custom_usernames[0]->value;

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

// Is mcassoc enabled?
$account_association = $queries->getWhere('settings', array('name', '=', 'use_mcassoc'));
$account_association = $account_association[0]->value;

if(isset($_GET['step']) && isset($_SESSION['mcassoc'])){
	// Get site ID
	$mcassoc_site_id = $queries->getWhere('settings', array('name', '=', 'sitename'));
	$mcassoc_site_id = $mcassoc_site_id[0]->value;
	
	$mcassoc_shared_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
	$mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;
	
	$mcassoc_instance_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
	$mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;
	
	// Initialise
	define('MCASSOC', true);
	
	$mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
	$mcassoc->enableInsecureMode();

	require('core/integration/run_mcassoc.php');
	die();
}

// Use recaptcha?
$recaptcha = $queries->getWhere("settings", array("name", "=", "recaptcha"));
$recaptcha = $recaptcha[0]->value;

$recaptcha_key = $queries->getWhere("settings", array("name", "=", "recaptcha_key"));
$recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));

// Is email verification enabled?
$email_verification = $queries->getWhere('settings', array('name', '=', 'email_verification'));
$email_verification = $email_verification[0]->value;

// Deal with any input
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Valid token
		
		if($recaptcha == 'true'){
			// Check reCAPCTHA
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			
			$post_data = 'secret=' . $recaptcha_secret[0]->value . '&response=' . Input::get('g-recaptcha-response');
			
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);
			
			$result = json_decode($result, true);
		} else {
			// reCAPTCHA is disabled
			$result = array(
				'success' => 'true'
			);
		}
		
		if(isset($result['success']) && $result['success'] == 'true'){
			// Validate
			$validate = new Validate();
			
			$to_validation = array( // Base field validation
				'password' => array(
					'required' => true,
					'min' => 6,
					'max' => 30
				),
				'password_again' => array(
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
				),
				'location' => array(
					'max' => 128
				)
			);
			
			if($recaptcha === "true"){ // check Recaptcha response
				$to_validation['g-recaptcha-response'] = array(
					'required' => true
				);
			}
			
			// Validate date of birth
			if(Input::get('birthday') && (!validateDate(Input::get('birthday')) || strtotime(Input::get('birthday')) > strtotime('now'))){
				// Invalid
				$error = '<div class="alert alert-danger">' . $user_language['invalid_date_of_birth'] . '</div>';
			} else {
				// Valid date of birth
				if($uuid_linking == '1'){
					if($custom_usernames == "true"){ // validate username and Minecraft name
						$to_validation['mcname'] = array(
							'required' => true,
							//'isvalid' => true,
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
						
						// Perform validation on Minecraft name
						$profile = ProfileUtils::getProfile(str_replace(' ', '%20', $mcname));
						$mcname_result = $profile->getProfileAsArray();
						
						if(isset($mcname_result['username']) && !empty($mcname_result['username'])){
							// Valid
						} else {
							// Invalid
							$invalid_mcname = true;
						}
						
					} else { // only validate Minecraft name
						$to_validation['username'] = array(
							'required' => true,
							'min' => 3,
							'max' => 20,
							'unique' => 'users'
						);
						$mcname = htmlspecialchars(Input::get('username'));
						
						// Perform validation on Minecraft name
						$profile = ProfileUtils::getProfile(str_replace(' ', '%20', $mcname));
						$mcname_result = $profile->getProfileAsArray();
						
						if(isset($mcname_result['username']) && !empty($mcname_result['username'])){
							// Valid
						} else {
							// Invalid
							$invalid_mcname = true;
						}
						
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
				
				// Check to see if the Minecraft username was valid
				if(!isset($invalid_mcname)){
					// Valid, continue with validation
					$validation = $validate->check($_POST, $to_validation); // Execute validation
					
					if($validation->passed()){
						if($uuid_linking == '1'){
							if(!isset($mcname_result)){
								$profile = ProfileUtils::getProfile(str_replace(' ', '%20', $mcname));
								$mcname_result = $profile->getProfileAsArray();
							}
							if(isset($mcname_result["uuid"]) && !empty($mcname_result['uuid'])){
								$uuid = $mcname_result['uuid'];
							} else {
								$uuid = '';
							}
						} else {
							$uuid = '';
						}
					
						// Minecraft user account association
						if(isset($account_association) && $account_association == '1'){
							// MCAssoc enabled
							// Get data from database
							$mcassoc_site_id = $queries->getWhere('settings', array('name', '=', 'sitename'));
							$mcassoc_site_id = $mcassoc_site_id[0]->value;
							
							$mcassoc_shared_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
							$mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;
							
							$mcassoc_instance_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
							$mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;
							
							// Initialise
							define('MCASSOC', true);
							
							$mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
							$mcassoc->enableInsecureMode();
							
							require('core/integration/run_mcassoc.php');
							die();
							
						} else {
							// MCAssoc disabled
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
									'lastip' => htmlspecialchars($ip),
									'last_online' => $date,
									'birthday' => date('Y-m-d', strtotime(str_replace('-', '/', htmlspecialchars(Input::get('birthday'))))),
									'location' => htmlspecialchars(Input::get('location'))
								));
								
								if($email_verification == '1'){
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
										$mail->addAddress(htmlspecialchars(Input::get('email')), htmlspecialchars(Input::get('username')));
										$mail->Subject = $sitename . ' - ' . $user_language['register'];
										
										// HTML to display in message
										$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'styles', 'templates', $template, 'email', 'register.html'));
										$html = file_get_contents($path);
										
										$link = 'http://' . $_SERVER['SERVER_NAME'] . '/validate/?c=' . $code;
										
										$html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array($sitename, $user_language['register'], $email_language['greeting'], $email_language['message'], $link, $email_language['thanks']), $html);
										
										$mail->msgHTML($html);
										$mail->IsHTML(true);
										$mail->Body = $html;
										
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
										
										$headers = 'From: ' . $siteemail . "\r\n" .
											'Reply-To: ' . $siteemail . "\r\n" .
											'X-Mailer: PHP/' . phpversion() . "\r\n" .
											'MIME-Version: 1.0' . "\r\n" . 
											'Content-type: text/plain; charset=UTF-8' . "\r\n";
										
										mail($to, $subject, $message, $headers);
									}
								} else {
									// Email verification disabled
									// Redirect straight to verification link
									echo '<script>window.location.replace("/validate/?c=' . $code . '");</script>';
									die();
								}
								
								Session::flash('home', '<div class="alert alert-info alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $user_language['registration_check_email'] . '</div>');
								Redirect::to('/');
								die();
							
							} catch(Exception $e){
								die($e->getMessage());
							}
						}
					} else {
						// Errors
						$error = '<div class="alert alert-danger">';
						foreach($validation->errors() as $validation_error){
							
							if(strpos($validation_error, 'is required') !== false){
								// x is required
								switch($validation_error){
									case (strpos($validation_error, 'username') !== false):
										$error .= $user_language['username_required'] . '<br />';
									break;
									case (strpos($validation_error, 'email') !== false):
										$error .= $user_language['email_required'] . '<br />';
									break;
									case (strpos($validation_error, 'password') !== false):
										$error .= $user_language['password_required'] . '<br />';
									break;
									case (strpos($validation_error, 'mcname') !== false):
										$error .= $user_language['mcname_required'] . '<br />';
									break;
									case (strpos($validation_error, 't_and_c') !== false):
										$error .= $user_language['accept_terms'] . '<br />';
									break;
									case (strpos($validation_error, 'location') !== false):
										$error .= $user_language['location_required'] . '<br />';
									break;
								}
								
							} else if(strpos($validation_error, 'minimum') !== false){
								// x must be a minimum of y characters long
								switch($validation_error){
									case (strpos($validation_error, 'username') !== false):
										$error .= $user_language['username_minimum_3'] . '<br />';
									break;
									case (strpos($validation_error, 'mcname') !== false):
										$error .= $user_language['mcname_minimum_3'] . '<br />';
									break;
									case (strpos($validation_error, 'password') !== false):
										$error .= $user_language['password_minimum_6'] . '<br />';
									break;
									case (strpos($validation_error, 'location') !== false):
										$error .= $user_language['location_minimum_2'] . '<br />';
									break;
								}
								
							} else if(strpos($validation_error, 'maximum') !== false){
								// x must be a maximum of y characters long
								switch($validation_error){
									case (strpos($validation_error, 'username') !== false):
										$error .= $user_language['username_maximum_20'] . '<br />';
									break;
									case (strpos($validation_error, 'mcname') !== false):
										$error .= $user_language['mcname_maximum_20'] . '<br />';
									break;
									case (strpos($validation_error, 'password') !== false):
										$error .= $user_language['password_maximum_30'] . '<br />';
									break;
									case (strpos($validation_error, 'location') !== false):
										$error .= $user_language['location_maximum_128'] . '<br />';
									break;
								}
								
							} else if(strpos($validation_error, 'must match') !== false){
								// password must match password again
								$error .= $user_language['passwords_dont_match'] . '<br />';
								
							} else if(strpos($validation_error, 'already exists') !== false){
								// already exists
								$error .= $user_language['username_mcname_email_exists'] . '<br />';
							} else if(strpos($validation_error, 'not a valid Minecraft account') !== false){
								// Invalid Minecraft username
								$error .= $user_language['invalid_mcname'] . '<br />';
								
							} else if(strpos($validation_error, 'Mojang communication error') !== false){
								// Mojang server error
								$error .= $user_language['mcname_lookup_error'] . '<br />';
								
							}
						}
						$error .= '</div>';
						//$error = '<div class="alert alert-danger">' . $user_language['registration_error'] . '</div>';
					}
				} else {
					// Invalid Minecraft name
					$error = '<div class="alert alert-danger">' . $user_language['invalid_mcname'] . '</div>';
				}
			}
		
		} else {
			// reCAPTCHA failed
			$error = '<div class="alert alert-danger">' . $user_language['invalid_recaptcha'] . '</div>';
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
			<input type="text" class="form-control input-lg datepicker" name="birthday" id="birthday" placeholder="' . $user_language['date_of_birth'] . '" tabindex="4">
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="form-group">
			<input type="text" name="location" id="location" class="form-control input-lg" placeholder="' . $user_language['location'] . '" tabindex="5">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="form-group">
			<input type="password" name="password" id="password" class="form-control input-lg" placeholder="' . $user_language['password'] . '" tabindex="6">
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="form-group">
			<input type="password" name="password_again" id="password_again" class="form-control input-lg" placeholder="' . $user_language['confirm_password'] . '" tabindex="7">
		</div>
	</div>
</div>
';
// Recaptcha
if($recaptcha === "true"){
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
	<div class="col-xs-12 col-md-6">
	  <button type="submit" class="btn btn-primary btn-block btn-lg">
		' . $user_language['register'] . '
	  </button>
	</div>
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
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $user_language['register'];
	
	require('core/includes/template/generate.php');
	?>
	
	<link href="/core/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
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
	<script src="/core/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

	<script>
	$('.datepicker').datepicker({
		orientation: 'bottom'
	});
	
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
