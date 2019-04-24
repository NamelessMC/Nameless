<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Version 2.0.0 API
 *  API version 1.0.5
 */

// Headers
header("Content-Type: application/json; charset=UTF-8");

$page_title = 'api';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

// Ensure API is actually enabled
$is_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
if ($is_enabled[0]->value != '1') {
    die('API is disabled');
}

// Initialise
$api = new Nameless2API($route, $language, TEMPLATE);

class Nameless2API
{
    // Variables
    private $_validated = false,
        $_db,
        $_language,
        $_template;

    // Construct
    public function __construct($route, $api_language, $template)
    {
        try {
            $explode = explode('/', $route);

            for ($i = count($explode) - 1; $i >= 0; $i--) {
                if (strlen($explode[$i]) == 32) {
                    if ($this->validateKey($explode[$i])) {
                        $api_key = $explode[$i];
                        break;
                    }
                }
            }

            // Set language
            if (!isset($api_language) || empty($api_language)) $this->throwError(2, 'Invalid language file');
            $this->_language = $api_language;

            if (isset($api_key)) {
                // Set template
                $this->_template = $template;

                // API key specified
                $this->_validated = true;
                $request = explode('/', $route);
                $request = $request[count($request) - 1];
                $this->handleRequest($request);

            } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
        } catch(Exception $e){
            $this->throwError($e->getMessage());
        }
    }

    // Display error message
    private function throwError($code = null, $message = null)
    {
        if ($code && $message) {
            die(json_encode(array('error' => true, 'code' => $code, 'message' => $message), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error')), JSON_PRETTY_PRINT));
        }
    }

    // Return an array
    private function returnArray($arr = null)
    {
        if (!$arr)
            $arr = array();

        $arr['error'] = false;
        die(json_encode($arr, JSON_PRETTY_PRINT));
    }

    // Validate API key
    private function validateKey($api_key = null)
    {
        if ($api_key) {
            // Check cached key
            if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache')) {
                // Not cached, cache now
                $this->_db = DB::getInstance();

                // Retrieve from database
                $correct_key = $this->_db->get('settings', array('name', '=', 'mc_api_key'));
                $correct_key = $correct_key->results();
                $correct_key = htmlspecialchars($correct_key[0]->value);

                // Store in cache file
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $correct_key);

            } else $correct_key = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache');

            if ($api_key == $correct_key) return true;
        }

        return false;
    }

    // Handle the API request
    private function handleRequest($action = null){
        // Ensure the API key is valid
        if($this->_validated === true){
            if(!($action)) $this->throwError(3, $this->_language->get('api', 'invalid_api-method'));
            switch($action){
                case 'info':
                    // Return website info
                    $this->returnInfo();
                    break;

                case 'getAnnouncements':
                    // Get announcements
                    $this->getAnnouncements();
                    break;

                case 'register':
                    // Register user
                    $this->registerUser();
                    break;

                case 'setGroup':
                    // Set a user's group
                    $this->setGroup();
                    break;

                case 'createReport':
                    // Create a new report
                    $this->createReport();
                    break;

                case 'updateUsername':
                    // Update a username
                    $this->updateUsername();
                    break;

                case 'serverInfo':
                    // Post server info
                    $this->serverInfo();
                    break;

                case 'userInfo':
                    // Get user info
                    $this->getUserInfo();
                    break;

                case 'getNotifications':
                    // Get user notifications
                    $this->getNotifications();
                    break;

                case 'validateUser':
                    // Validate a user
                    $this->validateUser();
                    break;

                case 'listUsers':
                    // List registered usernames + their uuids
                    $this->listUsers();
                    break;

                /*
                case 'log':
                    $this->log();
                    break;
                */

                default:
                    $this->throwError(3, $this->_language->get('api', 'invalid_api_method'));
                    break;
            }

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Return info about the Nameless installation
    private function returnInfo(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            // Get version, update info and modules from database
            $this->_db = DB::getInstance();

            $version_query = $this->_db->query('SELECT `name`, `value` FROM nl2_settings WHERE `name` = ? OR `name` = ? OR `name` = ? OR `name` = ?', array('nameless_version', 'version_checked', 'version_update', 'new_version'));
            if ($version_query->count())
                $version_query = $version_query->results();

            $site_id = $this->_db->get('settings', array('name', '=', 'unique_id'));
            if(!$site_id->count())
                $this->throwError(4, $this->_language->get('api', 'no_unique_site_id'));

            $site_id = $site_id->results();
            $site_id = $site_id[0]->value;

            $ret = array();
            foreach ($version_query as $item) {
                if($item->name == 'nameless_version') {
                    $ret[$item->name] = $item->value;
                    $current_version = $item->value;
                } else if($item->name == 'version_update')
                    $version_update = $item->value;
                else if($item->name == 'version_checked')
                    $version_checked = (int) $item->value;
                else
                    $new_version = $item->value;
            }

            if(isset($version_checked) && isset($version_update) && isset($current_version)){
                if($version_update == 'false') {
                    if($version_checked < strtotime('-1 hour')){
                        // Check for update now
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/nl_core/nl2/stats.php?uid=' . $site_id . '&version=' . $current_version);

                        $update_check = curl_exec($ch);

                        if (curl_error($ch)) {
                            $this->throwError(15, curl_error($ch));
                        } else {
                            if ($update_check == 'Failed') {
                                $this->throwError(5, $this->_language->get('api', 'unable_to_check_for_updates'));
                            }
                        }

                        curl_close($ch);

                        if ($update_check == 'None') {
                            $ret['version_update'] = array('update' => false, 'version' => 'none', 'urgent' => false);
                        } else {
                            $update_check = json_decode($update_check);

                            if (isset($update_check->urgent) && $update_check->urgent == 'true')
                                $version_urgent = 'urgent';
                            else
                                $version_urgent = 'true';

                            // Update database values to say we need a version update
                            $this->_db->createQuery('UPDATE nl2_settings SET `value`=\'' . $version_urgent . '\' WHERE `name` = \'version_update\'', array());
                            $this->_db->createQuery('UPDATE nl2_settings SET `value`= ' . date('U') . ' WHERE `name` = \'version_checked\'', array());
                            $this->_db->createQuery('UPDATE nl2_settings SET `value`= ? WHERE `name` = \'new_version\'', array($update_check->new_version));

                            $ret['version_update'] = array('update' => true, 'version' => $update_check->new_version, 'urgent' => ($version_urgent == 'urgent'));
                        }
                    } else {
                        $ret['version_update'] = array('update' => false, 'version' => 'none', 'urgent' => false);
                    }
                } else {
                    $ret['version_update'] = array('update' => true, 'version' => (isset($new_version) ? Output::getClean($new_version) : 'unknown'), 'urgent' => ($version_update == 'urgent'));
                }
            } else
                $ret['version_update'] = array('update' => false, 'version' => 'none', 'urgent' => false);

            $modules_query = $this->_db->get('modules', array('enabled', '=', 1));
            $ret_modules = array();
            if($modules_query->count()) {
                $modules_query = $modules_query->results();
                foreach($modules_query as $module){
                    $ret_modules[] = $module->name;
                }
            }
            $ret['modules'] = $ret_modules;

            if(count($ret)){
                $this->returnArray($ret);
            }

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Return latest announcements
    private function getAnnouncements(){
        // Ensure the API key is valid
        if($this->_validated === true){
            // TODO
            $this->returnArray(array('announcements' => array()));
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Register a user
    private function registerUser(){
        // Check POST fields
        if(!isset($_POST) || empty($_POST)){
            $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
        }

        // Validate post request
        if((!isset($_POST['username']) || empty($_POST['username']))
            || (!isset($_POST['uuid']) || empty($_POST['uuid']))
            || (!isset($_POST['email']) || empty($_POST['email']))) $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));

        // Remove -s from UUID (if present)
        $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);

        if(strlen($_POST['username']) > 20) $this->throwError(8, $this->_language->get('api', 'invalid_username'));
        if(strlen($_POST['uuid']) > 32) $this->throwError(9, $this->_language->get('api', 'invalid_uuid'));
        if(strlen($_POST['email']) > 64) $this->throwError(7, $this->_language->get('api', 'invalid_email_address'));

        // Validate email
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $this->throwError(7, $this->_language->get('api', 'invalid_email_address'));

        // Ensure user doesn't already exist
        $this->_db = DB::getInstance();

        $username= $this->_db->get('users', array('username', '=', htmlspecialchars($_POST['username'])));
        if(count($username->results())) $this->throwError(11, $this->_language->get('api', 'username_already_exists'));

        $uuid = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
        if(count($uuid->results())) $this->throwError(12, $this->_language->get('api', 'uuid_already_exists'));

        $email = $this->_db->get('users', array('email', '=', htmlspecialchars($_POST['email'])));
        if(count($email->results())) $this->throwError(10, $this->_language->get('api', 'email_already_exists'));

        // Registration email enabled?
        $registration_email = $this->_db->get('settings', array('name', '=', 'email_verification'));
        if($registration_email->count()) $registration_email = $registration_email->first()->value;
        else $registration_email = 1;

        if($registration_email == 1){
            // Send email
            $this->sendRegistrationEmail($_POST['username'], $_POST['uuid'], $_POST['email']);

        } else {
            // Register user + send link
            $code = $this->createUser($_POST['username'], $_POST['uuid'], $_POST['email']);

            $this->returnArray(array('message' => $this->_language->get('api', 'finish_registration_link'), 'link' => rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . $code['code'])));

        }
    }

    // Create user
    private function createUser($username, $uuid, $email, $code = null){
        // Ensure API key is valid
        if($this->_validated === true) {
            try {
                // Get default group ID
                if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache')) {
                    // Not cached, cache now
                    $this->_db = DB::getInstance();

                    // Retrieve from database
                    $default_group = $this->_db->get('groups', array('default_group', '=', 1));
                    if(!$default_group->count())
                        $default_group = 1;
                    else {
                        $default_group = $default_group->results();
                        $default_group = $default_group[0]->id;
                    }

                    $to_cache = array(
                        'default_group' => array(
                            'time' => date('U'),
                            'expire' => 0,
                            'data' => serialize($default_group)
                        )
                    );

                    // Store in cache file
                    file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache', json_encode($to_cache));

                } else {
                    $default_group = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache');
                    $default_group = json_decode($default_group);
                    $default_group = unserialize($default_group->default_group->data);
                }

                if (!$code)
                    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

                $this->_db->insert('users', array(
                    'username' => Output::getClean($username),
                    'nickname' => Output::getClean($username),
                    'uuid' => Output::getClean($uuid),
                    'email' => Output::getClean($email),
                    'password' => md5($code), // temp code
                    'joined' => date('U'),
                    'group_id' => $default_group,
                    'lastip' => 'Unknown',
                    'reset_code' => $code,
	                'last_online' => date('U')
                ));

                $user_id = $this->_db->lastid();

                $user = new User();
                HookHandler::executeEvent('registerUser', array(
                    'event' => 'registerUser',
                    'user_id' => $user_id,
                    'username' => Output::getClean($username),
                    'content' => str_replace('{x}', Output::getClean($username), $this->_language->get('user', 'user_x_has_registered')),
                    'avatar_url' => $user->getAvatar($user_id, null, 128, true),
                    'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean($username)), '/'),
                    'language' => $this->_language
                ));

                return array('user_id' => $user_id, 'code' => $code);

            } catch (Exception $e) {
                $this->throwError(13, $this->_language->get('api', 'unable_to_create_account'));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Send registration email
    private function sendRegistrationEmail($username, $uuid, $email){
        // Ensure API key is valid
        if($this->_validated === true){
            // Are we using PHPMailer or the PHP mail function?
            $this->_db = DB::getInstance();

            $mailer = $this->_db->get('settings', array('name', '=', 'phpmailer'));
            $mailer = $mailer->first()->value;

            // Generate random code
            $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

            // Create user
            $user_id = $this->createUser($username, $uuid, $email, $code);
            $user_id = $user_id['user_id'];

            // Get link + template
            $link =  Util::getSelfURL() . ltrim(URL::build('/complete_signup/', 'c=' . $code), '/');
            $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', 'register.html'));
            $html = file_get_contents($path);

            $html = str_replace(array('[Sitename]', '[Register]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'), array(SITE_NAME, $this->_language->get('general', 'register'), $this->_language->get('user', 'email_greeting'), $this->_language->get('user', 'email_message'), $link, $this->_language->get('user', 'email_thanks')), $html);

            if($mailer == '1'){
                // PHP Mailer
                $email = array(
                    'to' => array('email' => Output::getClean($email), 'name' => Output::getClean($username)),
                    'subject' => SITE_NAME . ' - ' . $this->_language->get('general', 'register'),
                    'message' => $html
                );

                $sent = Email::send($email, 'mailer');

                if(isset($sent['error'])){
                    // Error, log it
                    $this->_db->insert('email_errors', array(
                        'type' => 4, // 4 = API registration email
                        'content' => $sent['error'],
                        'at' => date('U'),
                        'user_id' => $user_id
                    ));

                    $this->throwError(14, $this->_language->get('api', 'unable_to_send_registration_email'));
                }

            } else {
                // PHP mail function
                $siteemail = $this->_db->get('settings', array('name', '=', 'outgoing_email'));
                $siteemail = $siteemail->first()->value;

                $to      = $email;
                $subject = SITE_NAME . ' - ' . $this->_language->get('general', 'register');

                $headers = 'From: ' . $siteemail . "\r\n" .
                    'Reply-To: ' . $siteemail . "\r\n" .
                    'X-Mailer: PHP/' . phpversion() . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=UTF-8' . "\r\n";

                $email = array(
                    'to' => $to,
                    'subject' => $subject,
                    'message' => $html,
                    'headers' => $headers
                );

                $sent = Email::send($email, 'php');

                if(isset($sent['error'])){
                    // Error, log it
                    $this->_db->insert('email_errors', array(
                        'type' => 4,
                        'content' => $sent['error'],
                        'at' => date('U'),
                        'user_id' => $user_id
                    ));

                    $this->throwError(14, $this->_language->get('api', 'unable_to_send_registration_email'));
                }
            }

            $user = new User();
            HookHandler::executeEvent('registerUser', array(
                'event' => 'registerUser',
                'user_id' => $user_id,
                'username' => Output::getClean($username),
                'content' => str_replace('{x}', Output::getClean($username), $this->_language->get('user', 'user_x_has_registered')),
                'avatar_url' => $user->getAvatar($user_id, null, 128, true),
                'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean($username)), '/'),
                'language' => $this->_language
            ));

            $this->returnArray(array('message' => $this->_language->get('api', 'finish_registration_email')));

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Get user info
    private function getUserInfo(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            if(isset($_GET['uuid']))
                $query = str_replace('-', '', $_GET['uuid']);
            else if(isset($_GET['username']))
                $query = $_GET['username'];
            else
                $this->throwError(26, $this->_language->get('api', 'invalid_get_contents'));

            // Ensure the user exists
            $this->_db = DB::getInstance();

            // Check UUID
            $user = $this->_db->query('SELECT nl2_users.username, nl2_users.nickname as displayname, nl2_users.uuid, nl2_users.group_id, nl2_users.joined as registered, nl2_users.isbanned as banned, nl2_users.active as validated, nl2_groups.name as group_name FROM nl2_users LEFT JOIN nl2_groups ON nl2_users.group_id = nl2_groups.id WHERE nl2_users.username = ? OR nl2_users.uuid = ?', array($query, $query));

            if(!$user->count()){
                $this->returnArray(array('exists' => false));
            }
            $user = $user->first();
            $user->exists = true;
            $user->banned = ($user->banned) ? true : false;
            $user->validated = ($user->validated) ? true : false;

            $this->returnArray((array)$user);

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Set a user's group
    private function setGroup(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            if(!isset($_POST) || empty($_POST) || !isset($_POST['uuid']) || empty($_POST['uuid']) || !isset($_POST['group_id']) || !is_numeric($_POST['group_id'])){
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }

            // Remove -s from UUID (if present)
            if(isset($_POST['uuid'])) $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);

            $this->_db = DB::getInstance();

            // Ensure user exists
            $user = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
            if(!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));

            $user = $user->first()->id;

            // Ensure group exists
            $group = $this->_db->get('groups', array('id', '=', $_POST['group_id']));
            if(!$group->count()) $this->throwError(17, $this->_language->get('api', 'unable_to_find_group'));

            $group = $group->first()->id;

            try {
                $this->_db->update('users', $user, array(
                    'group_id' => $group
                ));
            } catch(Exception $e){
                $this->throwError(18, $this->_language->get('api', 'unable_to_update_group'));
            }

            $this->returnArray(array('message' => $this->_language->get('api', 'group_updated')));

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Create a report
    private function createReport(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            if(!isset($_POST) || empty($_POST)){
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }

            // Validate post request
            // Player UUID, report content, reported player UUID and reported player username required
            if((!isset($_POST['reporter_uuid']) || empty($_POST['reporter_uuid']))
                || (!isset($_POST['reported_username']) || empty($_POST['reported_username']))
                || (!isset($_POST['reported_uuid']) || empty($_POST['reported_uuid']))
                || (!isset($_POST['content']) || empty($_POST['content']))){
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }

            // Remove -s from UUID (if present)
            $_POST['reporter_uuid'] = str_replace('-', '', $_POST['reporter_uuid']);
            $_POST['reported_uuid'] = str_replace('-', '', $_POST['reported_uuid']);

            // Ensure UUIDs/usernames/content are correct lengths
            if(strlen($_POST['reported_username']) > 20) $this->throwError(8, $this->_language->get('api', 'invalid_username'));
            if(strlen($_POST['reported_uuid']) > 32 || strlen($_POST['reporter_uuid']) > 32) $this->throwError(9, $this->_language->get('api', 'invalid_uuid'));
            if(strlen($_POST['content']) > 255) $this->throwError(19, $this->_language->get('api', 'report_content_too_long'));

            // Ensure user reporting has website account, and has not been banned
            $this->_db = DB::getInstance();
            $user_reporting = $this->_db->get('users', array('uuid', '=', Output::getClean($_POST['reporter_uuid'])));

            if(!$user_reporting->count()) $this->throwError(20, $this->_language->get('api', 'you_must_register_to_report'));
            else $user_reporting = $user_reporting->first();

            if($user_reporting->isbanned == 1) $this->throwError(21, $this->_language->get('api', 'you_have_been_banned_from_website'));

            // Ensure user has not already reported the same player, and the report is open
            $user_reports = $this->_db->get('reports', array('reporter_id', '=', $user_reporting->id));
            if(count($user_reports->results())){
                foreach($user_reports->results() as $report){
                    if($report->reported_uuid == $_POST['reported_uuid']){
                        if($report->status == 0) $this->throwError(22, $this->_language->get('api', 'you_have_open_report_already'));
                    }
                }
            }

            // See if reported user exists
            $user_reported = $this->_db->get('users', array('uuid', '=', Output::getClean($_POST['reported_uuid'])));

            if(!$user_reported->count()) $user_reported = 0;
            else $user_reported = $user_reported->first()->id;

            // Create report
            try {
                $report = new Report();

                // Create report
                $report->create(array(
                    'type' => 0,
                    'reporter_id' => $user_reporting->id,
                    'reported_id' => $user_reported,
                    'date_reported' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s'),
                    'report_reason' => Output::getClean($_POST['content']),
                    'updated_by' => $user_reporting->id
                ));

                // Success
                $this->returnArray(array('message' => $this->_language->get('api', 'report_created')));

            } catch(Exception $e){
                $this->throwError(23, $this->_language->get('api', 'unable_to_create_report'));
            }

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Get notifications for a user
    private function getNotifications(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            if(!isset($_GET['uuid']))
                $this->throwError(26, $this->_language->get('api', 'invalid_get_contents'));

            // Remove -s from UUID (if present)
            $query = str_replace('-', '', $_GET['uuid']);

            // Ensure the user exists
            $this->_db = DB::getInstance();

            // Check UUID
            $user = $this->_db->query('SELECT id FROM nl2_users WHERE uuid = ?', array($query));

            if(!$user->count()){
                $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
            }
            $user = $user->first()->id;

            $return = array('notifications' => array());

            // Get unread alerts
            $alerts = $this->_db->query('SELECT id, type, url, content_short FROM nl2_alerts WHERE user_id = ? AND `read` = 0', array($user));
            if($alerts->count()){
                foreach($alerts->results() as $result){
                    $return['notifications'][] = array(
                        'type' => $result->type,
                        'message_short' => $result->content_short,
                        'message' => ($result->content) ? strip_tags($result->content) : $result->content_short,
                        'url' => rtrim(Util::getSelfURL(), '/') . URL::build('/user/alerts/', 'view=' . $result->id)
                    );
                }
            }

            // Get unread messages
            $messages = $this->_db->query('SELECT nl2_private_messages.id, nl2_private_messages.title FROM nl2_private_messages WHERE nl2_private_messages.id IN (SELECT nl2_private_messages_users.pm_id as id FROM nl2_private_messages_users WHERE user_id = ? AND `read` = 0)', array($user));

            if($messages->count()){
                foreach($messages->results() as $result){
                    $return['notifications'][] = array(
                        'type' => 'message',
                        'url' => Util::getSelfURL() . ltrim(URL::build('/user/messaging/', 'action=view&message=' . $result->id), '/'),
                        'message_short' => $result->title,
                        'message' => $result->title
                    );
                }
            }

            $this->returnArray($return);

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Update a username, given a UUID
    private function updateUsername(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            if(!isset($_POST) || empty($_POST) || !isset($_POST['uuid']) || empty($_POST['uuid']) || !isset($_POST['username']) || empty($_POST['username']) || strlen($_POST['username']) < 2 || strlen($_POST['username']) > 16){
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }

            // Remove -s from UUID (if present)
            if(isset($_POST['uuid'])) $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);

            $this->_db = DB::getInstance();

            // Ensure user exists
            $user = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
            if(!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));

            $user = $user->first()->id;

            // Update just Minecraft username, or displayname too?
            $displaynames = $this->_db->get('settings', array('name', '=', 'displaynames'));
            if(!$displaynames->count()) $displaynames = 'false';
            else $displaynames = $displaynames->first()->value;

            $fields = array('username' => Output::getClean($_POST['username']));

            if($displaynames == 'false')
                $fields['nickname'] = Output::getClean($_POST['username']);

            try {
                $this->_db->update('users', $user, $fields);

            } catch(Exception $e){
                $this->throwError(24, $this->_language->get('api', 'unable_to_update_username'));
            }

            $this->returnArray(array('message' => $this->_language->get('api', 'username_updated')));

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Post server info
    private function serverInfo(){
        // Ensure the API key is valid
        if($this->_validated === true) {
            if(!isset($_POST) || empty($_POST) || !isset($_POST['info'])){
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }

            $info = json_decode($_POST['info'], true);
            if(!isset($info['server-id']) || !isset($info['max-memory']) || !isset($info['free-memory']) || !isset($info['allocated-memory']) || !isset($info['tps']) || !isset($info['players']))
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));

            $this->_db = DB::getInstance();

            // Ensure server exists
            $server_query = $this->_db->get('mc_servers', array('id', '=', $info['server-id']));
            if(!$server_query->count()) $this->throwError(27, $this->_language->get('api', 'invalid_server_id'));

            try {
                $this->_db->insert('query_results', array(
                    'server_id' => $info['server-id'],
                    'queried_at' => date('U'),
                    'players_online' => count($info['players']),
                    'extra' => $_POST['info']
                ));

                if(file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache')) {
                    $query_cache = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache');
                    $query_cache = json_decode($query_cache);
                    if(isset($query_cache->query_interval))
                        $query_interval = unserialize($query_cache->query_interval->data);
                    else
                        $query_interval = 10;

                    $to_cache = array(
                        'query_interval' => array(
                            'time' => date('U'),
                            'expire' => 0,
                            'data' => serialize($query_interval)
                        ),
                        'last_query' => array(
                            'time' => date('U'),
                            'expire' => 0,
                            'data' => serialize(date('U'))
                        )
                    );

                    // Store in cache file
                    file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache', json_encode($to_cache));
                }

            } catch(Exception $e){
                $this->throwError(25, $this->_language->get('api', 'unable_to_update_server_info'));
            }

            // Update usernames
            try {
                $update_usernames = $this->_db->get('settings', array('name', '=', 'username_sync'))->results();
                $update_usernames = $update_usernames[0]->value;

                if($update_usernames == '1'){
                    if(count($info['players'])){
                        // Update just Minecraft username, or displayname too?
                        $displaynames = $this->_db->get('settings', array('name', '=', 'displaynames'));
                        if(!$displaynames->count()) $displaynames = 'false';
                        else $displaynames = $displaynames->first()->value;

                        foreach($info['players'] as $uuid => $player){
                            $user = new User();
                            if($user->find($uuid, 'uuid')){
                                if($player['name'] != $user->data()->username){
                                    // Update username
                                    if($displaynames == 'false') {
                                        $user->update(array(
                                            'username' => Output::getClean($player['name']),
                                            'nickname' => Output::getClean($player['name'])
                                        ), $user->data()->id);
                                    } else {
                                        $user->update(array(
                                            'username' => Output::getClean($player['name'])
                                        ), $user->data()->id);
                                    }
                                }
                            }
                        }
                    }
                }
            } catch(Exception $e){
                $this->throwError(25, $this->_language->get('api', 'unable_to_update_server_info'));
            }

            // Group sync
	        try {
            	$group_sync = $this->_db->get('group_sync', array('id', '<>', 0));

            	if($group_sync->count()){
            		$group_sync = $group_sync->results();
            		$group_sync_updates = array();
            		foreach($group_sync as $item){
            			$group_sync_updates[strtolower($item->ingame_rank_name)] = array(
            				'website' => $item->website_group_id,
				            'primary' => $item->primary
			            );
		            }

		            if(count($info['players'])){
			            foreach($info['players'] as $uuid => $player){
				            $user = new User();
				            if($user->find($uuid, 'uuid')){
				            	if($user->data()->id != 1){
				            		// Can't update root user
						            $rank = strtolower($player['rank']);

						            if(array_key_exists($rank, $group_sync_updates) && $user->data()->group_id != $group_sync_updates[$rank]['website']){
						            	$new_rank = $group_sync_updates[$rank];

						            	if($new_rank['primary']){
						            		$user->update(array(
						            			'group_id' => $new_rank['website']
								            ), $user->data()->id);
							            } else {
						            		if($user->data()->secondary_groups)
						            		    $secondary = json_decode($user->data()->secondary_groups, true);
						            		else
						            			$secondary = array();

						            		if(!in_array($new_rank['website'], $secondary)){
									            $secondary[] = $new_rank['website'];

									            $user->update(array(
									            	'secondary_groups' => json_encode($secondary)
									            ), $user->data()->id);
								            }
							            }
						            }
					            }
				            }
			            }
		            }
	            }

	        } catch(Exception $e){
		        $this->throwError(25, $this->_language->get('api', 'unable_to_update_server_info'));
	        }

            $this->returnArray(array('message' => $this->_language->get('api', 'server_info_updated')));

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    // Validate user
    private function validateUser(){
        // Ensure the API key is valid
        if($this->_validated === true){
            if(!isset($_POST) || empty($_POST) || !isset($_POST['uuid']) || !isset($_POST['code'])){
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }

	        $this->_db = DB::getInstance();
            $user_query = $this->_db->get('users', array('uuid', '=', str_replace('-', '', $_POST['uuid'])));
            if($user_query->count()){
                $user_query = $user_query->first();

                if($user_query->reset_code == $_POST['code']){
                    $this->_db->update('users', $user_query->id, array(
                        'reset_code' => '',
                        'active' => 1
                    ));

                    try {
	                    HookHandler::executeEvent('validateUser', array(
		                    'event' => 'validateUser',
		                    'user_id' => $user_query->id,
		                    'username' => Output::getClean($user_query->username),
		                    'language' => $this->_language
	                    ));
                    } catch(Exception $e){
                    	// Error
                    }

	                $this->returnArray(array('message' => $this->_language->get('api', 'server_info_updated')));

                    $this->returnArray(array('message' => $this->_language->get('api', 'account_validated')));

                } else
                    $this->throwError(28, $this->_language->get('api', 'invalid_code'));

            } else
                $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
        }
    }

    // List registered users
	private function listUsers(){
		// Ensure the API key is valid
		if($this->_validated === true){
			$this->_db = DB::getInstance();

			$users = $this->_db->query('SELECT username, uuid, isbanned AS banned, active FROM nl2_users')->results();

			$this->returnArray(array('users' => $users));
		}
	}

    /*
    private function log(){
        //Ensures the API key is valid
        if($this->_validated === true){
            if(!isset($_POST) || empty($_POST) || !isset($_POST['action']) || !isset($_POST['uuid']) || !isset($_POST['userIP'])){
                $this->throwError();
            }
            $user_query = $this->_db->get('users', array('uuid', '=', str_replace('-', '', $_POST['uuid'])));
            if($user_query->count()){
                $user_query = $user_query->first();

                //TODO: Check if user have the permission
                
                //Check if it is an action
                if(Log::Action(Output::getClean($_POST['action'])) !== null){

                    //Log the action
                    Log::getInstance()->log(Log::Action(Output::getClean($_POST['action'])), (isset($_POST['info'])?Output::getClean($_POST['info']):null, $user_query->id, Output::getClean($_POST['userIP']));
                }else{
                    $this->throwError();
                }
            } else{
                $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
            }
        }
    }
    */
}
