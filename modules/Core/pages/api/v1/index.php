<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Version 1.0.16 legacy API
 */

/*
 *  API version 1.0.2
 *  built for NamelessMC version 1.0.10
 *  last updated for NamelessMC version 1.0.16
 *  legacy update for NamelessMC version 2.0.0 pre-release 3
 */

// Headers
header("Content-Type: application/json; charset=UTF-8");

// Ensure API is actually enabled
$is_enabled = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
if($is_enabled[0]->value != '1'){
    die('API is disabled');
}

// Initialise
$api = new NamelessAPI($route, $language, TEMPLATE);

class NamelessAPI {
    // Variables
    private $_validated = false,
        $_db,
        $_language,
        $_template;

    // Construct
    public function __construct($route, $api_language, $template){
        $explode = explode('/', $route);

        for($i = count($explode) - 1; $i >= 0; $i--){
            if(strlen($explode[$i]) == 32){
                if($this->validateKey($explode[$i])){
                    $api_key = $explode[$i];
                    break;
                }
            }
        }

        if(isset($api_key)){
            // Set language
            if(!isset($api_language) || empty($api_language)) $this->throwError('Invalid language file');
            $this->_language = $api_language;

            // Set template
            $this->_template = $template;

            // API key specified
            $this->_validated = true;
            $request = explode('/', $route);
            $request = $request[count($request) - 1];
            $this->handleRequest($request);

        } else $this->throwError('Invalid API key');
    }

    // Display error message
    private function throwError($message = null){
        if($message){
            die(json_encode(array('error' => true, 'message' => $message), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('error' => true, 'message' => 'Unknown error'), JSON_PRETTY_PRINT));
        }
    }

    // Display success message
    private function sendSuccessMessage($message = null){
        if($message){
            die(json_encode(array('success' => true, 'message' => $message), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('success' => true, 'message' => 'Unknown message'), JSON_PRETTY_PRINT));
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

            $username= $this->_db->get('users', array('username', '=', htmlspecialchars($_POST['username'])));
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

            $php_mailer = $this->_db->get('settings', array('name', '=', 'phpmailer'))->results();
            $php_mailer = $php_mailer[0]->value;

            // Generate random code
            $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

            try {
                // Insert user into database
                $this->_db->insert('users', array(
                    'username' => htmlspecialchars($post['username']),
                    'nickname' => htmlspecialchars($post['username']),
                    'uuid' => htmlspecialchars($post['uuid']),
                    'email' => htmlspecialchars($post['email']),
                    'password' => md5($code), // temp until user defines it themselves
                    'joined' => date('U'),
                    'last_online' => date('U'),
                    'group_id' => 1,
                    'lastip' => 'Unknown',
                    'reset_code' => $code,
                    'last_online' => date('U')
                ));

                $user_id = $this->_db->lastid();

                $user = new User();

                HookHandler::executeEvent('registerUser', array(
                    'event' => 'registerUser',
                    'user_id' => $user_id,
                    'username' => Output::getClean($_POST['username']),
                    'content' => str_replace('{x}', Output::getClean(Input::get('username')), $this->_language->get('user', 'user_x_has_registered')),
                    'avatar_url' => $user->getAvatar($user_id, null, 128, true),
                    'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean($_POST['username'])), '/'),
                    'language' => $this->_language
                ));

                if($php_mailer == '1'){
                    // PHP Mailer
                    // HTML to display in message
                    $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', $this->_template, 'email', 'api_register.html'));
                    $html = file_get_contents($path);

                    $link = 'http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . URL::build('/complete_signup/', 'c=' . $code);

                    $html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array(SITE_NAME, $this->_language->get('general', 'register'), $this->_language->get('user', 'email_greeting'), $this->_language->get('user', 'email_message'), $link, $this->_language->get('user', 'email_thanks')), $html);

                    $email = array(
                        'to' => array('email' => Output::getClean(Input::get('email')), 'name' => Output::getClean(Input::get('username'))),
                        'subject' => SITE_NAME . ' - ' . $this->_language->get('general', 'register'),
                        'message' => $html
                    );

                    $sent = Email::send($email, 'mailer');

                    if(isset($sent['error'])){
                        // Error, log it
                        $this->_db->insert('email_errors', array(
                            'type' => 4, // 4 = API registration
                            'content' => $sent['error'],
                            'at' => date('U'),
                            'user_id' => $user_id
                        ));
                    }

                } else {
                    // PHP mail function
                    $siteemail = $this->_db->get('settings', array('name', '=', 'outgoing_email'))->results();
                    $siteemail = $siteemail[0]->value;

                    $to      = Input::get('email');
                    $subject = SITE_NAME . ' - ' . $this->_language->get('general', 'register');

                    $message = 	$this->_language->get('user', 'email_greeting') . PHP_EOL .
                        $this->_language->get('user', 'email_message') . PHP_EOL . PHP_EOL .
                        'http' . ((defined('FORCE_SSL') && FORCE_SSL === true) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . URL::build('/complete_signup/', 'c=' . $code) . PHP_EOL . PHP_EOL .
                        $this->_language->get('user', 'email_thanks') . PHP_EOL .
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

                    if(isset($sent['error'])){
                        // Error, log it
                        $this->_db->insert('email_errors', array(
                            'type' => 4, // 4 = API registration
                            'content' => $sent['error'],
                            'at' => date('U'),
                            'user_id' => $user_id
                        ));
                    }

                }

            } catch(Exception $e) {
                $this->throwError('Unable to create account');
            }

            // Success
            $this->sendSuccessMessage('Registered successfully, please check your emails for further instructions');

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
                $user = $this->_db->get('users', array('username', '=', htmlspecialchars($_POST['username'])));
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
                $user = $this->_db->get('users', array('username', '=', htmlspecialchars($_POST['username'])));
                if(!count($user->results())) $this->throwError('That user doesn\'t exist.');
            }

            // User exists
            $user = $user->results();
            $user = $user[0];

            $return = array(
                'username' => htmlspecialchars($user->username),
                'displayname' => htmlspecialchars($user->nickname),
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
                    'link' => null,
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
                                    'url' => URL::build('/mod/reports/', 'report=' . $report_id),
                                    'content' => 'New report submitted',
                                    'content_short' => 'New report submitted',
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
                $field = 'username';

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
            $user = $this->_db->get('users', array((strlen($_POST['id']) <= 16 ? 'username' : 'uuid'), '=', htmlspecialchars($_POST['id'])));

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
                            'nickname' => htmlspecialchars($_POST['new_username']),
                            'username' => htmlspecialchars($_POST['new_username'])
                        ));
                    } catch(Exception $e){
                        $this->throwError('Unable to update username');
                    }

                } else {
                    // Displaynames are separate, just update Minecraft username
                    try {
                        // Update the user's Minecraft username
                        $this->_db->update('users', $user, array(
                            'username' => htmlspecialchars($_POST['new_username'])
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
