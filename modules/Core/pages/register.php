<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Registration page
 */

// Ensure user isn't already logged in
if($user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Set page name for custom scripts
$page = 'register';
 
// Check if registration is enabled
$registration_enabled = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
$registration_enabled = $registration_enabled[0]->value;
 
if(!isset($registration_enabled)){
	// Registration is disabled
	Redirect::to(URL::build('/'));
	die();
}

// Check if Minecraft is enabled
$minecraft = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
$minecraft = $minecraft[0]->value;
 
// Registration page
require('core/integration/uuid.php'); // For UUID stuff
require('core/includes/password.php'); // For password hashing

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$custom_usernames = $custom_usernames[0]->value;

if(isset($_GET['step']) && isset($_SESSION['mcassoc'])){
	// Get site details for MCAssoc
	$mcassoc_site_id = $sitename;
	
	$mcassoc_shared_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
	$mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;
	
	$mcassoc_instance_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
	$mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;
	
	define('MCASSOC', true);
	
	// Initialise
	$mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
	$mcassoc->enableInsecureMode();

	require('core/integration/run_mcassoc.php');
	die();
}

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

if($uuid_linking == '1'){
	// Do we want to verify the user owns the account?
	$account_verification = $queries->getWhere('settings', array('name', '=', 'verify_accounts'));
	$account_verification = $account_verification[0]->value;
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
						//'isvalid' => true,
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
					if(isset($account_verification) && $account_verification == '1'){
						// MCAssoc enabled
						// Get data from database
						$mcassoc_site_id = $sitename;
						
						$mcassoc_shared_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
						$mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;
						
						$mcassoc_instance_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
						$mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;
						
						define('MCASSOC', true);
						
						// Hash password first
						$password = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => 13));
						$_SESSION['password'] = $password;
						unset($_POST['password']);
						
						// Initialise
						$mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
						$mcassoc->enableInsecureMode();
						
						require('core/integration/run_mcassoc.php');
						die();
						
					} else {
						// Disabled
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
							// Generate random code for email
							$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
							
							// Get default language ID before creating user
							$language_id = $queries->getWhere('languages', array('name', '=', LANGUAGE));
							
							if(count($language_id)) $language_id = $language_id[0]->id;
							else $language_id = 1; // fallback to EnglishUK
							
							// Create user
							$user->create(array(
								'username' => $mcname,
								'nickname' => htmlspecialchars(Input::get('username')),
								'uuid' => $uuid,
								'password' => $password,
								'pass_method' => 'default',
								'joined' => $date,
								'group_id' => 1,
								'email' => htmlspecialchars(Input::get('email')),
								'reset_code' => $code,
								'lastip' => htmlspecialchars($ip),
								'last_online' => $date,
								'language_id' => $language_id
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
									$mail->SMTPAuth = true;
									$mail->Username = $GLOBALS['email']['username'];
									$mail->Password = $GLOBALS['email']['password'];
									$mail->setFrom($GLOBALS['email']['username'], $GLOBALS['email']['name']);
									$mail->From = $GLOBALS['email']['username'];
									$mail->FromName = $GLOBALS['email']['name'];
									$mail->addAddress(htmlspecialchars(Input::get('email')), htmlspecialchars(Input::get('username')));
									$mail->Subject = $sitename . ' - ' . $language->get('user', 'register');
									
									// HTML to display in message
									$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', $template, 'email', 'register.html'));
									$html = file_get_contents($path);
									
									$link = 'http://' . $_SERVER['SERVER_NAME'] . URL::build('/validate/', 'c=' . $code);
									
									$html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array($sitename, $language->get('user', 'register'), $language->get('user', 'email_greeting'), $language->get('user', 'email_message'), $link, $language->get('user', 'email_thanks')), $html);
									
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
									$subject = $sitename . ' - ' . $language->get('user', 'register');
									
									$message = 	$language->get('user', 'email_greeting') . PHP_EOL .
												$language->get('user', 'email_message') . PHP_EOL . PHP_EOL . 
												'http://' . $_SERVER['SERVER_NAME'] . URL::build('/validate/', 'c=' . $code) . PHP_EOL . PHP_EOL .
												$language->get('user', 'email_thanks') . PHP_EOL .
												$sitename;
									
									$headers = 'From: ' . $siteemail . "\r\n" .
										'Reply-To: ' . $siteemail . "\r\n" .
										'X-Mailer: PHP/' . phpversion();
									mail($to, $subject, $message, $headers);
								}
							} else {
								// Email verification disabled
								// Redirect straight to verification link
								$url = URL::build('/validate/', 'c=' . $code);
								Redirect::to($url);
								die();
							}
							
							Session::flash('home', '<div class="alert alert-info alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $language->get('user', 'registration_check_email') . '</div>');
							Redirect::to(URL::build('/'));
							die();
						
						} catch(Exception $e){
							die($e->getMessage());
						}
					}
				} else {
					// Errors
					$errors = array();
					foreach($validation->errors() as $validation_error){
						
						if(strpos($validation_error, 'is required') !== false){
							// x is required
							switch($validation_error){
								case (strpos($validation_error, 'username') !== false):
									$errors[] = $language->get('user', 'username_required');
								break;
								case (strpos($validation_error, 'email') !== false):
									$errors[] = $language->get('user', 'email_required');
								break;
								case (strpos($validation_error, 'password') !== false):
									$errors[] = $language->get('user', 'password_required');
								break;
								case (strpos($validation_error, 'mcname') !== false):
									$errors[] = $language->get('user', 'mcname_required');
								break;
								case (strpos($validation_error, 't_and_c') !== false):
									$errors[] = $language->get('user', 'accept_terms');
								break;
							}
							
						} else if(strpos($validation_error, 'minimum') !== false){
							// x must be a minimum of y characters long
							switch($validation_error){
								case (strpos($validation_error, 'username') !== false):
									$errors[] = $language->get('user', 'username_minimum_3');
								break;
								case (strpos($validation_error, 'mcname') !== false):
									$errors[] = $language->get('user', 'mcname_minimum_3');
								break;
								case (strpos($validation_error, 'password') !== false):
									$errors[] = $language->get('user', 'password_minimum_6');
								break;
							}
							
						} else if(strpos($validation_error, 'maximum') !== false){
							// x must be a maximum of y characters long
							switch($validation_error){
								case (strpos($validation_error, 'username') !== false):
									$errors[] = $language->get('user', 'username_maximum_20');
								break;
								case (strpos($validation_error, 'mcname') !== false):
									$errors[] = $language->get('user', 'mcname_maximum_20');
								break;
								case (strpos($validation_error, 'password') !== false):
									$errors[] = $language->get('user', 'password_maximum_30');
								break;
							}
							
						} else if(strpos($validation_error, 'must match') !== false){
							// password must match password again
							$errors[] = $language->get('user', 'passwords_dont_match');
							
						} else if(strpos($validation_error, 'already exists') !== false){
							// already exists
							$errors[] = $language->get('user', 'username_mcname_email_exists');
						} else if(strpos($validation_error, 'not a valid Minecraft account') !== false){
							// Invalid Minecraft username
							$errors[] = $language->get('user', 'invalid_mcname');
							
						} else if(strpos($validation_error, 'Mojang communication error') !== false){
							// Mojang server error
							$errors[] = $language->get('user', 'mcname_lookup_error');
							
						}
					}
				}
			} else {
				// Invalid Minecraft name
				$errors = array($language->get('user', 'invalid_mcname'));
			}
		
		} else {
			// reCAPTCHA failed
			$errors = array($language->get('user', 'invalid_recaptcha'));
		}
		
	} else {
		// Invalid token
		$errors = array($language->get('general', 'invalid_token'));
	}
}

if(isset($errors)) $smarty->assign('REGISTRATION_ERROR', $errors);

// Are custom usernames enabled?
if($custom_usernames !== 'false') $smarty->assign('NICKNAMES', true);

if($minecraft == 1){
	$smarty->assign('MINECRAFT', true);
}

if($recaptcha == 'true'){
	$smarty->assign('RECAPTCHA', Output::getClean($recaptcha_key[0]->value)); 
}

// Assign Smarty variables
$smarty->assign(array(
	'NICKNAME' => $language->get('user', 'username'),
	'MINECRAFT_USERNAME' => $language->get('user', 'minecraft_username'),
	'EMAIL' => $language->get('user', 'email_address'),
	'PASSWORD' => $language->get('user', 'password'),
	'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
	'I_AGREE' => $language->get('user', 'i_agree'),
	'AGREE_TO_TERMS' => str_replace('{x}', URL::build('/terms'), $language->get('user', 'agree_t_and_c')),
	'REGISTER' => $language->get('general', 'register'),
	'LOG_IN' => $language->get('general', 'sign_in'),
	'LOGIN_URL' => URL::build('/login'),
	'TOKEN' => Token::generate(),
	'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account')
));
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> - registration form">
	
    <!-- Site Properties -->
	<?php 
	$title = $language->get('general', 'register');
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

// Registration template
$smarty->display('custom/templates/' . TEMPLATE . '/register.tpl');
?>
<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="t_and_c_m_Label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title" id="t_and_c_m_Label"><?php echo $language->get('user', 'terms_and_conditions'); ?></h4>
			</div>
			<div class="modal-body">
			  <?php 
			  $t_and_c = $queries->getWhere("settings", array("name", "=", "t_and_c"));
			  echo Output::getPurified(htmlspecialchars_decode($t_and_c[0]->value));
			  $t_and_c = $queries->getWhere("settings", array("name", "=", "t_and_c_site"));
			  echo Output::getPurified(htmlspecialchars_decode($t_and_c[0]->value));
			  ?>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $language->get('general', 'close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
// Scripts 
require('core/templates/scripts.php');
 
if($recaptcha === "true"){
?>

	<script src="https://www.google.com/recaptcha/api.js"></script>
<?php 
}
?>
  </body>
</html>