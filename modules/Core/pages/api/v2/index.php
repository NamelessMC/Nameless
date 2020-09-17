<?php
/*
 *	Made by Samerton
 *  Additions by Aberdeener
 * 
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
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
$api = new Nameless2API($route, $language, $endpoints);

class Nameless2API {

    private 
        $_validated = false,
        $_db,
        $_language,
        $_endpoints;

    public function isValidated() {
        return $this->_validated;
    }

    public function getDb() {
        return $this->_db;
    }

    public function getLanguage() {
        return $this->_language;
    }

    public function __construct($route, $api_language, $endpoints) {
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
            $this->_db = DB::getInstance();
            $this->_endpoints = $endpoints;

            if (isset($api_key)) {
                // API key specified
                $this->_validated = true;
                $request = explode('/', $route);
                $request = $request[count($request) - 1];

                if ($this->_endpoints->handle($request, $this) == false) {
                    $this->throwError(3, $this->_language->get('api', 'invalid_api_method'));
                }   
            } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
        } catch(Exception $e) {
            $this->throwError($e->getMessage());
        }
    }

    // API functions

    /**
     * Inserts new user information to database
     * 
     * For internal API use only
     * 
     * @see Nameless2API::register()
     * 
     * @param string $username The username of the new user to create
     * @param string $uuid (optional) The Minecraft UUID of the new user
     * @param string $email The email of the new user
     * @param string $code The reset token/temp password of the new user
     * 
     * @return string JSON Array
     */
    // TODO: Finish MC Integration checks etc
    private function createUser($username, $uuid, $email, $code = null) {
        if ($this->_validated === true) {
            try {
                // Get default group ID
                if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache')) {
                    // Not cached, cache now
                    // Retrieve from database
                    $default_group = $this->_db->get('groups', array('default_group', '=', 1));
                    if (!$default_group->count())
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

                $user_id = $this->_db->lastId();

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

                $this->returnArray(array('user_id' => $user_id, 'code' => $code));

            } catch (Exception $e) {
                $this->throwError(13, $this->_language->get('api', 'unable_to_create_account'));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Sends verification email upon new registration
     * 
     * For internal API use only
     * 
     * @see Nameless2API::register()
     * 
     * @param string $username The username of the new user to create
     * @param string $uuid (optional) The Minecraft UUID of the new user
     * @param string $email The email of the new user
     * 
     * @return string JSON Array
     */
    // TODO: Finish MC Integration checks etc
    private function sendRegistrationEmail($username, $uuid, $email) {
        if ($this->_validated === true) {
            // Are we using PHPMailer or the PHP mail function?
            $mailer = $this->_db->get('settings', array('name', '=', 'phpmailer'));
            $mailer = $mailer->first()->value;

            // Generate random code
            $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

            // Create user
            $user_id = $this->createUser($username, $uuid, $email, $code);
            $user_id = $user_id['user_id'];

            // Get link + template
            $link =  Util::getSelfURL() . ltrim(URL::build('/complete_signup/', 'c=' . $code), '/');

            $html = Email::formatEmail('register', $this->_language);

            if ($mailer) {
                // PHP Mailer
                $email = array(
                    'to' => array('email' => Output::getClean($email), 'name' => Output::getClean($username)),
                    'subject' => SITE_NAME . ' - ' . $this->_language->get('emails', 'register_subject'),
                    'message' => str_replace('[Link]', $link, $html)
                );

                $sent = Email::send($email, 'mailer');

                if (isset($sent['error'])) {
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
                $subject = SITE_NAME . ' - ' . $this->_language->get('emails', 'register_subject');

                $headers = 'From: ' . $siteemail . "\r\n" .
                    'Reply-To: ' . $siteemail . "\r\n" .
                    'X-Mailer: PHP/' . phpversion() . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=UTF-8' . "\r\n";

                $email = array(
                    'to' => $to,
                    'subject' => $subject,
                    'message' => str_replace('[Link]', $link, $html),
                    'headers' => $headers
                );

                $sent = Email::send($email, 'php');

                if (isset($sent['error'])) {
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

    // Internal functions

    private function validateKey($api_key = null) {
        if ($api_key) {
            // Check cached key
            if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache')) {
                // Not cached, cache now
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

    public function throwError($code = null, $message = null) {
        if ($code && $message) {
            die(json_encode(array('error' => true, 'code' => $code, 'message' => $message), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error')), JSON_PRETTY_PRINT));
        }
    }

    public function returnArray($arr = null) {
        if (!$arr) $arr = array();

        $arr['error'] = false;
        die(json_encode($arr, JSON_PRETTY_PRINT));
    }
    
    public function validateParams($input, $required_fields) {
        if (!isset($input) || empty($input)) {
            $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
        }
        foreach ($required_fields as $required) {
            if (!isset($input[$required]) || empty($input[$required])) {
                $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }
        }
        return true;
    }
}