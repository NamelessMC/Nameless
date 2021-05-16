<?php

/**
 * @param string $username The username of the new user to create
 * @param string $uuid (optional) The Minecraft UUID of the new user
 * @param string $email The email of the new user
 *
 * @return string JSON Array
 */
class RegisterEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'register';
        $this->_module = 'Core';
        $this->_description = 'Register a new user, and send email verification if needed.';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $params = ['username', 'email'];

        $minecraft_integration = Util::getSetting($api->getDb(), 'mc_integration');
        if ($minecraft_integration) {
            $params[] = 'uuid';
        }

        $api->validateParams($_POST, $params);

        if ($minecraft_integration) {
            $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);
            if (strlen($_POST['uuid']) > 32) {
                $api->throwError(9, $api->getLanguage()->get('api', 'invalid_uuid'));
            }
        }

        if (strlen($_POST['username']) > 20) {
            $api->throwError(8, $api->getLanguage()->get('api', 'invalid_username'));
        }

        if (strlen($_POST['email']) > 64) {
            $api->throwError(7, $api->getLanguage()->get('api', 'invalid_email_address'));
        }

        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $api->throwError(7, $api->getLanguage()->get('api', 'invalid_email_address'));
        }

        // Ensure user doesn't already exist
        $username = $api->getDb()->get('users', array('username', '=', Output::getClean($_POST['username'])));
        if (count($username->results())) {
            $api->throwError(11, $api->getLanguage()->get('api', 'username_already_exists'));
        }

        if ($minecraft_integration) {
            $uuid = $api->getDb()->get('users', array('uuid', '=', Output::getClean($_POST['uuid'])));
            if (count($uuid->results())) $api->throwError(12, $api->getLanguage()->get('api', 'uuid_already_exists'));
        }

        $email = $api->getDb()->get('users', array('email', '=', Output::getClean($_POST['email'])));
        if (count($email->results())) {
            $api->throwError(10, $api->getLanguage()->get('api', 'email_already_exists'));
        }

        $uuid = ($minecraft_integration) ? Output::getClean($_POST['uuid']) : 'none';

        if (Util::getSetting($api->getDb(), 'api_verification', false)) {
            // Create user and send link to set password
            $this->createUser($api, $_POST['username'], $uuid, $_POST['email'], true, null, true);
        } else if (Util::getSetting($api->getDb(), 'email_verification', true)) {
            // Send email to verify
            $this->sendRegistrationEmail($api, $_POST['username'], $uuid, $_POST['email']);
        } else {
            // Register user + send link to verify account
            $this->createUser($api, $_POST['username'], $uuid, $_POST['email'], true);
        }
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
    private function sendRegistrationEmail(Nameless2API $api, $username, $uuid, $email) {
        // Generate random code
        $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

        // Create user
        $user_id = $this->createUser($api, $username, $uuid, $email, false, $code);
        $user_id = $user_id['user_id'];

        // Get link + template
        $link =  Util::getSelfURL() . ltrim(URL::build('/complete_signup/', 'c=' . $code), '/');

        $html = Email::formatEmail('register', $api->getLanguage());

        if (Util::getSetting($api->getDb(), 'phpmailer')) {
            // PHP Mailer
            $email = array(
                'to' => array('email' => Output::getClean($email), 'name' => Output::getClean($username)),
                'subject' => SITE_NAME . ' - ' . $api->getLanguage()->get('emails', 'register_subject'),
                'message' => str_replace('[Link]', $link, $html)
            );

            $sent = Email::send($email, 'mailer');

            if (isset($sent['error'])) {
                // Error, log it
                $api->getDb()->insert(
                    'email_errors',
                    array(
                        'type' => 4, // 4 = API registration email
                        'content' => $sent['error'],
                        'at' => date('U'),
                        'user_id' => $user_id
                    )
                );

                $api->throwError(14, $api->getLanguage()->get('api', 'unable_to_send_registration_email'));
            }
        } else {
            // PHP mail function
            $siteemail = Util::getSetting($api->getDb(), 'site_email');

            $to      = $email;
            $subject = SITE_NAME . ' - ' . $api->getLanguage()->get('emails', 'register_subject');

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
                $api->getDb()->insert(
                    'email_errors',
                    array(
                        'type' => 4,
                        'content' => $sent['error'],
                        'at' => date('U'),
                        'user_id' => $user_id
                    )
                );

                $api->throwError(14, $api->getLanguage()->get('api', 'unable_to_send_registration_email'));
            }
        }

        $user = new User();
        HookHandler::executeEvent('registerUser', array(
                'event' => 'registerUser',
                'user_id' => $user_id,
                'username' => Output::getClean($username),
                'content' => str_replace('{x}', Output::getClean($username), $api->getLanguage()->get('user', 'user_x_has_registered')),
                'avatar_url' => $user->getAvatar(128, true),
                'url' => Util::getSelfURL() . ltrim(URL::build('/profile/' . Output::getClean($username)), '/'),
                'language' => $api->getLanguage()
            )
        );

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'finish_registration_email')));
    }

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
    private function createUser(Nameless2API $api, $username, $uuid, $email, $return, $code = null, $api_verification = false) {
        try {
            // Get default group ID
            if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache')) {
                // Not cached, cache now
                // Retrieve from database
                $default_group = $api->getDb()->get('groups', array('default_group', '=', 1));
                if (!$default_group->count()) {
                    $default_group = 1;
                } else {
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

            if (!$code) {
                $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
            }

            $api->getDb()->insert('users', array(
                    'username' => Output::getClean($username),
                    'nickname' => Output::getClean($username),
                    'uuid' => $uuid,
                    'email' => Output::getClean($email),
                    'password' => md5($code), // temp code
                    'joined' => date('U'),
                    'lastip' => 'Unknown',
                    'active' => $api_verification === true ? 1 : 0,
                    'reset_code' => $code,
                    'last_online' => date('U')
                )
            );

            $user_id = $api->getDb()->lastId();

            $user = new User($user_id);
            $user->setGroup($default_group);

            HookHandler::executeEvent('registerUser', array(
                    'event' => 'registerUser',
                    'user_id' => $user_id,
                    'username' => $user->getDisplayname(),
                    'content' => str_replace('{x}', $user->getDisplayname(), $api->getLanguage()->get('user', 'user_x_has_registered')),
                    'avatar_url' => $user->getAvatar(128, true),
                    'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
                    'language' => $api->getLanguage()
                )
            );

            if ($return || $api_verification) {
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'finish_registration_link'), 'user_id' => $user_id, 'link' => rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . $code)));
            } else {
                return array('user_id' => $user_id);
            }

        } catch (Exception $e) {
            $api->throwError(13, $api->getLanguage()->get('api', 'unable_to_create_account'), $e->getMessage());
        }
    }
}
