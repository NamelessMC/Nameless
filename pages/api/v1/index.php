<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  API version 1.0.2
 *  built for NamelessMC version 1.0.10
 *  last updated for NamelessMC version 1.0.16
 */
 
// Headers
header("Content-Type: application/json; charset=UTF-8");
 
// Get API key
if(!isset($directories[3]) || (isset($directories[3]) && empty($directories[3]))){
	$api_key = null;
} else {
	$api_key = $directories[3];
}

// Ensure API is actually enabled
$is_enabled = $queries->getWhere('settings', array('name', '=', 'use_plugin'));
if($is_enabled[0]->value != '1'){
	die('API is disabled');
}

// Initialise
$api = new NamelessAPI($api_key, $directories, array_merge($api_language, $email_language), $template);

class NamelessAPI {
	// Variables
	private $_validated = false,
			$_db,
			$_language,
			$_template;
	
	// Construct
	public function __construct($api_key = null, $directories, $api_language, $template){
		if($api_key){
			// Set language
			if(!isset($api_language) || empty($api_language)) $this->throwError('Invalid language file');
			$this->_language = $api_language;
			
			// Set template
			$this->_template = $template;
			
			// API key specified
			if($this->validateKey($api_key)){
				// API key valid
				$this->_validated = true;
				$this->handleRequest($directories[4]);
				
			} else $this->throwError('Invalid API key');
			
		} else $this->throwError('Invalid API key');
	}
	
	// Display error message
	private function throwError($message = null){
		if($message){
			die(json_encode(array('error' => true, 'message' => $message)));
		} else {
			die(json_encode(array('error' => true, 'message' => 'Unknown error')));
		}
	}
	
	// Display success message
	private function sendSuccessMessage($message = null){
		if($message){
			die(json_encode(array('success' => true, 'message' => $message)));
		} else {
			die(json_encode(array('success' => true, 'message' => 'Unknown message')));
		}
	}
	
	// Validate API key
	private function validateKey($api_key = null){
		if($api_key){
			// Check cached key
			if(!is_file('cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache')){
				// Not cached, cache now
				$this->_db = DB::getInstance();
				
				// Retrieve from database
				$correct_key = $this->_db->get('settings', array('name', '=', 'mc_api_key'));
				$correct_key = $correct_key->results();
				$correct_key = htmlspecialchars($correct_key[0]->value);
				
				// Store in cache file
				file_put_contents('cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $correct_key);
				
			} else $correct_key = file_get_contents('cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache');
			
			if($api_key == $correct_key) return true;
		}
		
		return false;
	}
	
	// Handle the API request
	private function handleRequest($action = null){
		// Ensure the API key is valid
		if($this->_validated === true){
			if(!($action)) $this->throwError('Invalid API method');
			switch($action){
				case 'register':
					// Register a user
					$this->registerUser();
				break;
				
				case 'get':
					// Get all information about a user
					$this->getUser();
				break;
				
				case 'setGroup':
					// Set group of a user
					$this->setGroup();
				break;
				
				case 'createReport':
					// Creating a new player report
					$this->createReport();
				break;
				
				case 'getNotifications':
					// Get notifications for user
					$this->getNotifications((isset($_POST['uuid']) ? $_POST['uuid'] : null));
				break;
				
				case 'updateUsername':
					// Update a user's username
					$this->updateUsername();
				break;
				
				case 'checkConnection':
					// Check API connection
					$this->checkConnection();
				break;
				
				default:
					// No method specified
					$this->throwError('Invalid API method');
				break;
			}
			
		} else $this->throwError('Invalid API key');
	}
	
	// Simple connection check
	private function checkConnection(){
		if($this->_validated === true){
			$this->sendSuccessMessage('OK');
		} else $this->throwError('Invalid API key');
	}
	
	// Register a user
	private function registerUser(){
		// Ensure the API key is valid
		if($this->_validated === true){
			if(!isset($_POST) || empty($_POST)){
				$this->throwError('Invalid post contents');
			}
			
			// Validate post request
			if((!isset($_POST['username']) || empty($_POST['username']))
				|| (!isset($_POST['uuid']) || empty($_POST['uuid']))
				|| (!isset($_POST['email']) || empty($_POST['email']))) $this->throwError('Invalid post contents');
			
			// Remove -s from UUID (if present)
			$_POST['uuid'] = str_replace('-', '', $_POST['uuid']);
			
			if(strlen($_POST['username']) > 20) $this->throwError('Invalid username');
			if(strlen($_POST['uuid']) > 32) $this->throwError('Invalid UUID');
			if(strlen($_POST['email']) > 64) $this->throwError('Invalid email address');
			
			// Validate email
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $this->throwError('Invalid email address');
			
			// Ensure user doesn't already exist
			$this->_db = DB::getInstance();
			
			$username= $this->_db->get('users', array('mcname', '=', htmlspecialchars($_POST['username'])));
			if(count($username->results())) $this->throwError('Username already exists');
			
			$uuid = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
			if(count($uuid->results())) $this->throwError('UUID already exists');
			
			$email = $this->_db->get('users', array('email', '=', htmlspecialchars($_POST['email'])));
			if(count($email->results())) $this->throwError('Email already exists');
			
			// Create the user and send them an email
			$this->sendRegistrationEmail($_POST);
			
		} else $this->throwError('Invalid API key');
	}
	
	// Send a registration email to a user
	private function sendRegistrationEmail($post){
		// Ensure API key is valid
		if($this->_validated === true){
			// Are we using PHPMailer or the PHP mail function?
			$this->_db = DB::getInstance();
			
			$mailer = $this->_db->get('settings', array('name', '=', 'phpmailer'));
			$mailer = $mailer->results();
			$mailer = htmlspecialchars($mailer[0]->value);
			
			// Generate random code
			$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
			
			// Get site name
			if(!is_file('cache' . DIRECTORY_SEPARATOR . sha1('sitenamecache') . '.cache')) $sitename = 'Minecraft Server';
			else {
				$sitename = file_get_contents('cache' . DIRECTORY_SEPARATOR . sha1('sitenamecache') . '.cache');
				$sitename = json_decode($sitename);
				$sitename = $sitename->sitename->data;
				$sitename = unserialize($sitename);
			}
			
			if($mailer == '1'){
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
				$mail->addAddress(htmlspecialchars($post['email']), htmlspecialchars($post['username']));
				$mail->Subject = $sitename . ' - ' . $this->_language['register'];
				
				// HTML to display in message
				$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'styles', 'templates', $this->_template, 'email', 'register.html'));
				$html = file_get_contents($path);
				
				$link = 'http://' . $_SERVER['SERVER_NAME'] . '/complete_signup/?c=' . $code;
				
				$html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array($sitename, $this->_language['register'], $this->_language['greeting'], $this->_language['message'], $link, $this->_language['thanks']), $html);
				
				$mail->msgHTML($html);
				$mail->IsHTML(true);
				$mail->Body = $html;
				
				if(!$mail->send()) {
					$this->throwError($mail->ErrorInfo);
				}
				
			} else {
				// PHP mail function
				$siteemail = $this->_db->get('settings', array('name', '=', 'outgoing_email'));
				$siteemail = $siteemail->results();
				$siteemail = htmlspecialchars($siteemail[0]->value);
				
				$to      = htmlspecialchars($post['email']);
				$subject = $sitename . ' - ' . $this->_language['register'];
				
				$message = 	$this->_language['greeting'] . PHP_EOL .
							$this->_language['message'] . PHP_EOL . PHP_EOL . 
							'http://' . $_SERVER['SERVER_NAME'] . '/complete_signup/?c=' . $code . PHP_EOL . PHP_EOL .
							$this->_language['thanks'] . PHP_EOL .
							$sitename;
				
				$headers = 'From: ' . $siteemail . "\r\n" .
					'Reply-To: ' . $siteemail . "\r\n" .
					'X-Mailer: PHP/' . phpversion() . "\r\n" .
					'MIME-Version: 1.0' . "\r\n" . 
					'Content-type: text/plain; charset=UTF-8' . "\r\n";
				
				mail($to, $subject, $message, $headers);
				
			}
			
			try {
				// Insert user into database
				$this->_db->insert('users', array(
					'username' => htmlspecialchars($post['username']),
					'mcname' => htmlspecialchars($post['username']),
					'uuid' => htmlspecialchars($post['uuid']),
					'email' => htmlspecialchars($post['email']),
					'password' => md5($code), // temp until user defines it themselves
					'joined' => date('U'),
					'group_id' => 1,
					'lastip' => 'Unknown',
					'reset_code' => $code
				));
				
				// Success
				$this->sendSuccessMessage('Registered successfully, please check your emails for further instructions');
			} catch(Exception $e){
				$this->throwError('Unable to create account');
			}
			
		} else $this->throwError('Invalid API key');
	}
	
	// Set a user's group
	private function setGroup(){
		// Ensure the API key is valid
		if($this->_validated === true){
			if(!isset($_POST) || empty($_POST)){
				$this->throwError('Invalid post contents');
			}
			
			// Validate post request
			// Either username or UUID
			if((!isset($_POST['uuid']) || empty($_POST['uuid'])) && (!isset($_POST['username']) || empty($_POST['username']))){
				$this->throwError('Invalid post contents');
			}
			
			// Remove -s from UUID (if present)
			if(isset($_POST['uuid'])) $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);
			
			// Ensure the user exists
			$this->_db = DB::getInstance();
			if(isset($_POST['uuid'])){
				$user = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
				if(!count($user->results())) $this->throwError('That user doesn\'t exist');
			} else {
				$user = $this->_db->get('users', array('mcname', '=', htmlspecialchars($_POST['username'])));
				if(!count($user->results())) $this->throwError('That user doesn\'t exist');
			}
			
			// User exists
			$user = $user->results();
			$user = $user[0]->id;
			
			// Ensure group exists, and is numeric
			if(!isset($_POST['group_id']) || !is_numeric($_POST['group_id'])) $this->throwError('Invalid post contents');
			
			$group = $this->_db->get('groups', array('id', '=', $_POST['group_id']));
			if(!count($group->results())) $this->throwError('That group doesn\'t exist');
			
			try {
				// Update the user's group
				$this->_db->update('users', $user, array(
					'group_id' => $_POST['group_id']
				));
			} catch(Exception $e){
				$this->throwError('Unable to change group');
			}
			
			// Success
			$this->sendSuccessMessage('Group changed successfully');

		} else $this->throwError('Invalid API key');
	}
	
	// Does a user exist?
	private function getUser(){
		// Ensure the API key is valid
		if($this->_validated === true){
			if(!isset($_POST) || empty($_POST)){
				$this->throwError('Invalid post contents');
			}
			
			// Validate post request
			// Either username or UUID
			if((!isset($_POST['uuid']) || empty($_POST['uuid'])) && (!isset($_POST['username']) || empty($_POST['username']))){
				$this->throwError('Invalid post contents');
			}
			
			// Remove -s from UUID (if present)
			if(isset($_POST['uuid'])) $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);
			
			// Ensure the user exists
			$this->_db = DB::getInstance();
			if(isset($_POST['uuid'])){
				$user = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
				if(!count($user->results())) $this->throwError('That user doesn\'t exist.');
			} else {
				$user = $this->_db->get('users', array('mcname', '=', htmlspecialchars($_POST['username'])));
				if(!count($user->results())) $this->throwError('That user doesn\'t exist.');
			}
			
			// User exists
			$user = $user->results();
			$user = $user[0];
			
			$return = array(
				'username' => htmlspecialchars($user->mcname),
				'displayname' => htmlspecialchars($user->username),
				'uuid' => htmlspecialchars($user->uuid),
				'group_id' => $user->group_id,
				'registered' => $user->joined,
				'banned' => $user->isbanned,
				'validated' => $user->active,
				'reputation' => $user->reputation
			);
			
			// Success
			$this->sendSuccessMessage(json_encode($return));
			
		} else $this->throwError('Invalid API key');
	}
	
	// Create a new report
	private function createReport(){
		// Ensure the API key is valid
		if($this->_validated === true){
			if(!isset($_POST) || empty($_POST)){
				$this->throwError('Invalid post contents');
			}
			
			// Validate post request
			// Player UUID, report content, reported player UUID and reported player username required
			if((!isset($_POST['reporter_uuid']) || empty($_POST['reporter_uuid'])) 
				|| (!isset($_POST['reported_username']) || empty($_POST['reported_username']))
			    || (!isset($_POST['reported_uuid']) || empty($_POST['reported_uuid']))
				|| (!isset($_POST['content']) || empty($_POST['content']))){
				$this->throwError('Invalid post contents');
			}
			
			// Remove -s from UUID (if present)
			$_POST['reporter_uuid'] = str_replace('-', '', $_POST['reporter_uuid']);
			$_POST['reported_uuid'] = str_replace('-', '', $_POST['reported_uuid']);
			
			// Ensure UUIDs/usernames/content are correct lengths
			if(strlen($_POST['reported_username']) > 20) $this->throwError('Invalid username');
			if(strlen($_POST['reported_uuid']) > 32 || strlen($_POST['reporter_uuid']) > 32) $this->throwError('Invalid UUID');
			if(strlen($_POST['content']) >= 255) $this->throwError('Please ensure the report content is less than 255 characters long.');

			// Ensure user reporting has website account, and has not been banned
			$this->_db = DB::getInstance();
			$user_reporting = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['reporter_uuid'])));
			
			if(!count($user_reporting->results())) $this->throwError('You must have a website account to report players.');
			else $user_reporting = $user_reporting->results();
			
			if($user_reporting[0]->isbanned == 1) $this->throwError('You have been banned from the website.');
			
			// Ensure user has not already reported the same player, and the report is open
			$user_reports = $this->_db->get('reports', array('reporter_id', '=', $user_reporting[0]->id));
			if(count($user_reports->results())){
				foreach($user_reports->results() as $report){
					if($report->reported_uuid == $_POST['reported_uuid']){
						if($report->status == 0) $this->throwError('You still have a report open regarding that player.');
					}
				}
			}
			
			// Create report
			try {
				$this->_db->insert('reports', array(
					'type' => 1,
					'reporter_id' => $user_reporting[0]->id,
					'reported_id' => 0,
					'status' => 0,
					'date_reported' => date('Y-m-d H:i:s'),
					'date_updated' => date('Y-m-d H:i:s'),
					'report_reason' => htmlspecialchars($_POST['content']),
					'updated_by' => $user_reporting[0]->id,
					'reported_post' => 0,
					'reported_post_topic' => 0,
					'reported_mcname' => htmlspecialchars($_POST['reported_username']),
					'reported_uuid' => htmlspecialchars($_POST['reported_uuid'])
				));
				
				// Alert moderators
				$report_id = $this->_db->lastid();

				// Alert for moderators
				$mod_groups = $this->_db->get('groups', array('mod_cp', '=', 1));
				if(count($mod_groups->results())){
					foreach($mod_groups->results() as $mod_group){
						$mod_users = $this->_db->get('users', array('group_id', '=', $mod_group->id));
						if(count($mod_users->results())){
							foreach($mod_users->results() as $individual){
								$this->_db->insert('alerts', array(
									'user_id' => $individual->id,
									'type' => 'Report',
									'url' => '/mod/reports/?rid=' . $report_id,
									'content' => 'New report submitted',
									'created' => date('U')
								));
							}
						}
					}
				}
				
				// Success
				$this->sendSuccessMessage('Report created successfully');
			} catch(Exception $e){
				$this->throwError('Unable to create report');
			}
			
		} else $this->throwError('Invalid API key');
	}

	// Get number of notifications (alerts + private messages) for a user, based on username or UUID
	private function getNotifications($id = null){
		// Ensure the API key is valid
		if($this->_validated === true){
			// Ensure a UUID is set
			if(!$id){
				$this->throwError('Invalid username/UUID');
			}
			
			// UUID or username?
			if(strlen($id) <= 16){
				// Username
				$field = 'mcname';
				
			} else {
				// Remove - from UUID
				$id = str_replace('-', '', $id);
				
				// Ensure valid UUID was passed
				if(strlen($id) > 32 || strlen($id) > 32) $this->throwError('Invalid UUID');
				
				$field = 'uuid';
			}
			
			// Get user from database
			$this->_db = DB::getInstance();
			$user = $this->_db->get('users', array($field, '=', htmlspecialchars($id)));
			
			if($user->count()){
				// Get notifications
				$user = $user->results();
				$user = $user[0];
				
				// Alerts
				$alerts = $this->_db->get('alerts', array('user_id', '=', $user->id));
				
				$alerts_count = 0;
				
				if($alerts->count()){
					$alerts = $alerts->results();
					
					foreach($alerts as $alert){
						if($alert->read == 0) $alerts_count++;
					}
				}
				
				$alerts = null;
				
				// Private messages
				$pms = $this->_db->get('private_messages_users', array('user_id', '=', $user->id));
				
				$pms_count = 0;
				
				if($pms->count()){
					$pms = $pms->results();
					
					foreach($pms as $pm){
						if($pm->read == 0) $pms_count++;
					}
				}
				
				$pms = null;
				
				$this->sendSuccessMessage(json_encode(array('alerts' => $alerts_count, 'messages' => $pms_count)));
				
			} else
				$this->throwError('Can\'t find user with that username or UUID!');
			
		} else $this->throwError('Invalid API key');
	}
	
	// Update a user's username
	private function updateUsername(){
		// Ensure the API key is valid
		if($this->_validated === true){
			// Check post contents
			// Required: UUID/old username, new username
			if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['new_username']) || empty($_POST['new_username']))
				$this->throwError('Invalid post contents');
			
			// Remove - from ID, if any
			$_POST['id'] = str_replace('-', '', $_POST['id']);
			
			// Validate post content
			if(strlen($_POST['id']) > 32) $this->throwError('Invalid UUID');
			else if(strlen($_POST['id']) < 2) $this->throwError('Invalid username/UUID');
			
			if(strlen($_POST['new_username']) < 2) $this->throwError('Invalid new username provided');
			else if(strlen($_POST['new_username']) > 16) $this->throwError('Invalid new username provided');
			
			// Check for user specified
			$this->_db = DB::getInstance();
			$user = $this->_db->get('users', array((strlen($_POST['id']) <= 16 ? 'mcname' : 'uuid'), '=', htmlspecialchars($_POST['id'])));
			
			if($user->count()){
				$user = $user->first();
				$user = $user->id;
				
				// Update just Minecraft username, or displayname too?
				$displaynames = $this->_db->get('settings', array('name', '=', 'displaynames'));
				if(!$displaynames->count()) $this->throwError('Unable to obtain settings from database');
				
				$displaynames = $displaynames->first();
				
				if($displaynames->value == 'false'){
					// Displaynames disabled
					try {
						// Update the user's displayname and Minecraft username
						$this->_db->update('users', $user, array(
							'username' => htmlspecialchars($_POST['new_username']),
							'mcname' => htmlspecialchars($_POST['new_username'])
						));
					} catch(Exception $e){
						$this->throwError('Unable to update username');
					}
					
				} else {
					// Displaynames are separate, just update Minecraft username
					try {
						// Update the user's Minecraft username
						$this->_db->update('users', $user, array(
							'mcname' => htmlspecialchars($_POST['new_username'])
						));
					} catch(Exception $e){
						$this->throwError('Unable to update username');
					}
					
				}
				
				$this->sendSuccessMessage('Username updated successfully');
				
			} else $this->throwError('Can\'t find user with that username or UUID!');
			
		} else $this->throwError('Invalid API key');
	}
	
}
