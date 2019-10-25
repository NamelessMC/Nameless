<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
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
define('PAGE', 'register');
$page_title = $language->get('general', 'register');

// Check if Minecraft is enabled
$minecraft = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
$minecraft = $minecraft[0]->value;

if($minecraft == '1') {
    // Check if AuthMe is enabled
    $authme_enabled = $queries->getWhere('settings', array('name', '=', 'authme'));
    $authme_enabled = $authme_enabled[0]->value;

    if ($authme_enabled == '1') {
        // Authme connector
        require(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Core', 'pages', 'authme_connector.php')));
        die();
    }
}

require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Check if registration is enabled
$registration_enabled = $queries->getWhere('settings', array('name', '=', 'registration_enabled'));
$registration_enabled = $registration_enabled[0]->value;
 
if($registration_enabled == 0){
	// Registration is disabled, display a message
	$template->addCSSFiles(array(
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => array()
	));

	$template->addJSFiles(array(
		(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array()
	));

	// Get registration disabled message and assign to Smarty variable
	$registration_disabled_message = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));
	if(count($registration_disabled_message)){
		$message = Output::getPurified(htmlspecialchars_decode($registration_disabled_message[0]->value));
	} else {
		$message = 'Registration is currently disabled.';
	}

	$smarty->assign(array(
		'REGISTRATION_DISABLED' => $message,
		'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account')
	));

	// Load modules + template
	Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

	$page_load = microtime(true) - $start;
	define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	$template->onPageLoad();

	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	// Display template
	$template->displayTemplate('registration_disabled.tpl', $smarty);

    die();
}
 
// Registration page
require(ROOT_PATH . '/core/integration/uuid.php'); // For UUID stuff
require(ROOT_PATH . '/core/includes/password.php'); // For password hashing

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$custom_usernames = $custom_usernames[0]->value;

if(isset($_GET['step']) && isset($_SESSION['mcassoc'])){
	// Get site details for MCAssoc
	$mcassoc_site_id = SITE_NAME;
	
	$mcassoc_shared_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_key'));
	$mcassoc_shared_secret = $mcassoc_shared_secret[0]->value;
	
	$mcassoc_instance_secret = $queries->getWhere('settings', array('name', '=', 'mcassoc_instance'));
	$mcassoc_instance_secret = $mcassoc_instance_secret[0]->value;
	
	define('MCASSOC', true);
	
	// Initialise
	$mcassoc = new MCAssoc($mcassoc_shared_secret, $mcassoc_site_id, $mcassoc_instance_secret);
	$mcassoc->enableInsecureMode();

	require(ROOT_PATH . '/core/integration/run_mcassoc.php');
	die();
}

// Is UUID linking enabled?
if($minecraft == '1') {
    $uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
    $uuid_linking = $uuid_linking[0]->value;

    if ($uuid_linking == '1') {
        // Do we want to verify the user owns the account?
        $account_verification = $queries->getWhere('settings', array('name', '=', 'verify_accounts'));
        $account_verification = $account_verification[0]->value;
    }
} else
    $uuid_linking = '0';

// Use recaptcha?
$recaptcha = $queries->getWhere("settings", array("name", "=", "recaptcha"));
$recaptcha = $recaptcha[0]->value;

$recaptcha_key = $queries->getWhere("settings", array("name", "=", "recaptcha_key"));
$recaptcha_secret = $queries->getWhere('settings', array('name', '=', 'recaptcha_secret'));

// Is email verification enabled?
$email_verification = $queries->getWhere('settings', array('name', '=', 'email_verification'));
$email_verification = $email_verification[0]->value;

// API verification
$api_verification = $queries->getWhere('settings', array('name', '=', 'api_verification'));
$api_verification = $api_verification[0]->value;

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

			// Minecraft username?
			if(MINECRAFT){
				if($custom_usernames == 'true'){
					// Nickname enabled
					$to_validation['username'] = array(
						'required' => true,
						'min' => 3,
						'max' => 20,
						'unique' => 'users'
					);
					$to_validation['nickname'] = array(
						'required' => true,
						'min' => 3,
						'max' => 20,
						'unique' => 'users'
					);

					$nickname = Output::getClean(Input::get('nickname'));
					$username = Output::getClean(Input::get('username'));

				} else {
					$to_validation['username'] = array(
						'required' => true,
						'min' => 3,
						'max' => 20,
						'unique' => 'users'
					);

					$nickname = Output::getClean(Input::get('username'));
					$username = Output::getClean(Input::get('username'));

				}

			} else {
				// Just check username
				$to_validation['username'] = array(
					'required' => true,
					'min' => 3,
					'max' => 20,
					'unique' => 'users'
				);

				$nickname = Output::getClean(Input::get('username'));
				$username = Output::getClean(Input::get('username'));

			}
				// Valid, continue with validation
				$validation = $validate->check($_POST, $to_validation); // Execute validation
				
				if($validation->passed()){
					if(MINECRAFT && $uuid_linking == 1){
						// Perform validation on Minecraft name
						$profile = ProfileUtils::getProfile(str_replace(' ', '%20', $username));

						$mcname_result = $profile ? $profile->getProfileAsArray() : array();

						if(isset($mcname_result['username']) && !empty($mcname_result['username']) && isset($mcname_result['uuid']) && !empty($mcname_result['uuid'])){
							// Valid
							$uuid = Output::getClean($mcname_result['uuid']);

							// Ensure UUID is unique
							$uuid_query = $queries->getWhere('users', array('uuid', '=', $uuid));
							if(count($uuid_query)){
								$uuid_error = $language->get('user', 'uuid_already_exists');
							}

						} else {
							// Invalid
							$invalid_mcname = true;
						}
					}

					// Check to see if the Minecraft username was valid
					if(!isset($invalid_mcname)){
						if(!isset($uuid))
							$uuid = '';

						if(!isset($uuid_error)){
	                        // Minecraft user account association
	                        if (isset($account_verification) && $account_verification == '1') {
	                            // MCAssoc enabled
	                            // Get data from database
	                            $mcassoc_site_id = SITE_NAME;

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

	                            require(ROOT_PATH . '/core/integration/run_mcassoc.php');
	                            die();

	                        } else {
	                            // Disabled
	                            $user = new User();

	                            $ip = $user->getIP();
	                            if (filter_var($ip, FILTER_VALIDATE_IP)) {
	                                // Valid IP
	                            } else {
	                                // TODO: Invalid IP, do something else
	                            }

	                            $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));
	                            // Get current unix time
	                            $date = new DateTime();
	                            $date = $date->getTimestamp();

	                            try {
	                                if ($api_verification == '1') {
	                                    // Generate shorter code for API validation
	                                    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
	                                    $active = 1;
	                                } else {
	                                    // Generate random code for email
	                                    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
	                                    $active = 0;
	                                }

	                                // Get default language ID before creating user
	                                $language_id = $queries->getWhere('languages', array('name', '=', LANGUAGE));

	                                if (count($language_id)) $language_id = $language_id[0]->id;
	                                else $language_id = 1; // fallback to EnglishUK

	                                // Get default group ID
	                                $cache->setCache('default_group');
	                                if ($cache->isCached('default_group')) {
	                                    $default_group = $cache->retrieve('default_group');
	                                } else {
	                                    $default_group = $queries->getWhere('groups', array('default_group', '=', 1));
	                                    $default_group = $default_group[0]->id;

	                                    $cache->store('default_group', $default_group);
	                                }

	                                // Create user
	                                $user->create(array(
	                                    'username' => $username,
	                                    'nickname' => $nickname,
	                                    'uuid' => $uuid,
	                                    'password' => $password,
	                                    'pass_method' => 'default',
	                                    'joined' => $date,
	                                    'group_id' => $default_group,
	                                    'email' => Output::getClean(Input::get('email')),
	                                    'reset_code' => $code,
	                                    'lastip' => Output::getClean($ip),
	                                    'last_online' => $date,
	                                    'language_id' => $language_id,
	                                    'active' => $active
	                                ));

	                                // Get user ID
	                                $user_id = $queries->getLastId();

	                                Log::getInstance()->log(Log::Action('user/register'), "", $user_id);

	                                if ($api_verification != '1' && $email_verification == '1') {
	                                    $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
	                                    $php_mailer = $php_mailer[0]->value;

	                                    if ($php_mailer == '1') {
	                                        // PHP Mailer
	                                        // HTML to display in message
	                                        $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', 'register.html'));
	                                        $html = file_get_contents($path);

	                                        $link = 'http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . URL::build('/validate/', 'c=' . $code);

	                                        $html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array(SITE_NAME, $language->get('user', 'validate_account'), $language->get('user', 'email_greeting'), $language->get('user', 'email_message'), $link, $language->get('user', 'email_thanks')), $html);

	                                        $email = array(
	                                            'to' => array('email' => Output::getClean(Input::get('email')), 'name' => Output::getClean(Input::get('username'))),
	                                            'subject' => SITE_NAME . ' - ' . $language->get('user', 'validate_account'),
	                                            'message' => $html
	                                        );

	                                        $sent = Email::send($email, 'mailer');

	                                        if (isset($sent['error'])) {
	                                            // Error, log it
	                                            $queries->create('email_errors', array(
	                                                'type' => 1, // 1 = registration
	                                                'content' => $sent['error'],
	                                                'at' => date('U'),
	                                                'user_id' => $user_id
	                                            ));
	                                        }

	                                    } else {
	                                        // PHP mail function
	                                        $siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
	                                        $siteemail = $siteemail[0]->value;

	                                        $to = Input::get('email');
	                                        $subject = SITE_NAME . ' - ' . $language->get('user', 'validate_account');

	                                        $message = $language->get('user', 'email_greeting') . PHP_EOL .
	                                            $language->get('user', 'email_message') . PHP_EOL . PHP_EOL .
	                                            'http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . URL::build('/validate/', 'c=' . $code) . PHP_EOL . PHP_EOL .
	                                            $language->get('user', 'email_thanks') . PHP_EOL .
	                                            SITE_NAME;

	                                        $headers = 'From: ' . $siteemail . "\r\n" .
	                                            'Reply-To: ' . $siteemail . "\r\n" .
	                                            'X-Mailer: PHP/' . phpversion() . "\r\n" .
	                                            'MIME-Version: 1.0' . "\r\n" .
	                                            'Content-type: text/html; charset=UTF-8' . "\r\n";

	                                        $email = array(
	                                            'to' => $to,
	                                            'subject' => $subject,
	                                            'message' => $message,
	                                            'headers' => $headers
	                                        );

	                                        $sent = Email::send($email, 'php');

	                                        if (isset($sent['error'])) {
	                                            // Error, log it
	                                            $queries->create('email_errors', array(
	                                                'type' => 1, // 1 = registration
	                                                'content' => $sent['error'],
	                                                'at' => date('U'),
	                                                'user_id' => $user_id
	                                            ));
	                                        }

	                                    }
	                                } else if($api_verification != '1') {
	                                    // Email verification disabled
	                                    HookHandler::executeEvent('registerUser', array(
	                                        'event' => 'registerUser',
	                                        'user_id' => $user_id,
	                                        'username' => Output::getClean(Input::get('username')),
	                                        'uuid' => $uuid,
	                                        'content' => str_replace('{x}', Output::getClean(Input::get('username')), $language->get('user', 'user_x_has_registered')),
	                                        'avatar_url' => $user->getAvatar($user_id, null, 128, true),
	                                        'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean(Input::get('username'))), '/'),
	                                        'language' => $language
	                                    ));

	                                    // Redirect straight to verification link
	                                    $url = URL::build('/validate/', 'c=' . $code);
	                                    Redirect::to($url);
	                                    die();
	                                }

	                                HookHandler::executeEvent('registerUser', array(
	                                    'event' => 'registerUser',
	                                    'user_id' => $user_id,
	                                    'username' => Output::getClean(Input::get('username')),
	                                    'uuid' => $uuid,
	                                    'content' => str_replace('{x}', Output::getClean(Input::get('username')), $language->get('user', 'user_x_has_registered')),
	                                    'avatar_url' => $user->getAvatar($user_id, null, 128, true),
	                                    'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean(Input::get('username'))), '/'),
	                                    'language' => $language
	                                ));

		                            if ($api_verification != '1')
			                            Session::flash('home', $language->get('user', 'registration_check_email'));
		                            else
			                            Session::flash('home', $language->get('user', 'validation_complete'));

	                                Redirect::to(URL::build('/'));
	                                die();

	                            } catch (Exception $e) {
	                                die($e->getMessage());
	                            }
	                        }
	                    } else {
						    $errors = array($uuid_error);
	                    }

					} else {
						// Invalid Minecraft name
						$errors = array($language->get('user', 'invalid_mcname'));
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
                            if(!in_array($language->get('user', 'username_mcname_email_exists'), $errors))
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
	'NICKNAME' => ($custom_usernames == 'false' && !MINECRAFT) ? $language->get('user', 'username') : $language->get('user', 'nickname'),
	'MINECRAFT_USERNAME' => $language->get('user', 'minecraft_username'),
	'EMAIL' => $language->get('user', 'email_address'),
	'PASSWORD' => $language->get('user', 'password'),
	'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
	'I_AGREE' => $language->get('user', 'i_agree'),
	'AGREE_TO_TERMS' => str_replace('{x}', URL::build('/terms'), $language->get('user', 'agree_t_and_c')),
	'REGISTER' => $language->get('general', 'register'),
	'LOG_IN' => $language->get('general', 'sign_in'),
	'LOGIN_URL' => URL::build('/login'),
	'TOKEN' => Token::get(),
	'CREATE_AN_ACCOUNT' => $language->get('user', 'create_an_account'),
	'ALREADY_REGISTERED' => $language->get('general', 'already_registered')
));

if($recaptcha === "true"){
	$template->addJSFiles(array(
		'https://www.google.com/recaptcha/api.js' => array()
	));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('register.tpl', $smarty);