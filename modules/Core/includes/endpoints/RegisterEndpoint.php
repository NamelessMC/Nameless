<?php

/**
 * @param string $username The username of the new user to create
 * @param string $email The email of the new user
 *
 * @return string JSON Array
 */
class RegisterEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/register';
        $this->_module = 'Core';
        $this->_description = 'Register a new user, and send email verification if needed.';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['username', 'email']);

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
        $username = $api->getDb()->get('users', ['username', '=', Output::getClean($_POST['username'])]);
        if (count($username->results())) {
            $api->throwError(11, $api->getLanguage()->get('api', 'username_already_exists'));
        }

        // Integrations
        if (isset($_POST['integrations'])) {
            $integrations = Integrations::getInstance();

            foreach ($_POST['integrations'] as $integration_name => $item) {
                if (!isset($item['identifier']) || !isset($item['username'])) {
                    continue;
                }

                $integration = $integrations->getIntegration($integration_name);
                if ($integration != null) {
                    // Ensure username doesn't already exist
                    $integrationUser = new IntegrationUser($integration, $item['username'], 'username');
                    if ($integrationUser->exists()) {
                        $api->throwError(38, $api->getLanguage()->get('api', 'integration_username_already_linked', ['integration' => $integration->getName()]));
                    }

                    // Ensure identifier doesn't already exist
                    $integrationUser = new IntegrationUser($integration, $item['identifier'], 'identifier');
                    if ($integrationUser->exists()) {
                        $api->throwError(39, $api->getLanguage()->get('api', 'integration_identifier_already_linked', ['integration' => $integration->getName()]));
                    }
                }
            }
        }

        $email = $api->getDb()->get('users', ['email', '=', Output::getClean($_POST['email'])]);
        if (count($email->results())) {
            $api->throwError(10, $api->getLanguage()->get('api', 'email_already_exists'));
        }

        if (Util::getSetting($api->getDb(), 'email_verification', true)) {
            // Send email to verify
            $this->sendRegistrationEmail($api, $_POST['username'], $_POST['email']);
        } else {
            // Register user + send link to verify account
            $this->createUser($api, $_POST['username'], $_POST['email'], true);
        }
    }

    /**
     * Inserts new user information to database
     *
     * For internal API use only
     *
     * @param Nameless2API $api
     * @param string $username The username of the new user to create
     * @param string $email The email of the new user
     * @param bool $return
     * @param string|null $code The reset token/temp password of the new user
     *
     * @return array
     */
    private function createUser(Nameless2API $api, string $username, string $email, bool $return, string $code = null): array {
        try {
            // Get default group ID
            if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache')) {
                // Not cached, cache now
                // Retrieve from database
                $default_group = $api->getDb()->get('groups', ['default_group', '=', 1]);
                if (!$default_group->count()) {
                    $default_group = 1;
                } else {
                    $default_group = $default_group->results();
                    $default_group = $default_group[0]->id;
                }

                $to_cache = [
                    'default_group' => [
                        'time' => date('U'),
                        'expire' => 0,
                        'data' => serialize($default_group)
                    ]
                ];

                // Store in cache file
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache', json_encode($to_cache));
            } else {
                $default_group = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache');
                $default_group = json_decode($default_group);
                $default_group = unserialize($default_group->default_group->data);
            }

            if (!$code) {
                $code = Hash::unique();
            }

            $api->getDb()->insert('users', [
                    'username' => $username,
                    'nickname' => $username,
                    'email' => $email,
                    'password' => md5($code), // temp code
                    'joined' => date('U'),
                    'lastip' => 'Unknown',
                    'reset_code' => $code,
                    'last_online' => date('U')
                ]
            );

            $user_id = $api->getDb()->lastId();

            $user = new User($user_id);
            $user->setGroup($default_group);

            // Integrations
            if (isset($_POST['integrations'])) {
                $integrations = Integrations::getInstance();

                foreach ($_POST['integrations'] as $integration_name => $item) {
                    if (!isset($item['identifier']) || !isset($item['username'])) {
                        continue;
                    }

                    $integration = $integrations->getIntegration($integration_name);
                    if ($integration == null) {
                        continue;
                    }

                    // Validate username and make sure username is unique
                    if (!$integration->validateUsername($item['username'])) {
                        continue;
                    }

                    // Validate identifier and make sure identifier is unique
                    if (!$integration->validateIdentifier($item['identifier'])) {
                        continue;
                    }

                    $integrationUser = new IntegrationUser($integration);
                    $integrationUser->linkIntegration($user, $item['identifier'], $item['username'], true);
                }
            }

            EventHandler::executeEvent('registerUser', [
                    'user_id' => $user_id,
                    'username' => $user->getDisplayname(),
                    'content' => $api->getLanguage()->get('user', 'user_x_has_registered', [
                        'user' => $user->getDisplayName(),
                        'siteName' => SITE_NAME,
                    ]),
                    'avatar_url' => $user->getAvatar(128, true),
                    'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
                    'language' => $api->getLanguage()
                ]
            );

            if ($return) {
                $api->returnArray(['message' => $api->getLanguage()->get('api', 'finish_registration_link'), 'user_id' => $user_id, 'link' => rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . urlencode($code))]);
            }

            return ['user_id' => $user_id];

        } catch (Exception $e) {
            $api->throwError(13, $api->getLanguage()->get('api', 'unable_to_create_account'), $e->getMessage());
        }
    }

    /**
     * Sends verification email upon new registration
     *
     * For internal API use only
     *
     * @param string $username The username of the new user to create
     * @param string $email The email of the new user
     * @see Nameless2API::register()
     *
     */
    private function sendRegistrationEmail(Nameless2API $api, string $username, string $email): void {
        // Generate random code
        $code = Hash::unique();

        // Create user
        $user_id = $this->createUser($api, $username, $email, false, $code);
        $user_id = $user_id['user_id'];

        // Get link + template
        $link = Util::getSelfURL() . ltrim(URL::build('/complete_signup/', 'c=' . urlencode($code)), '/');

        $sent = Email::send(
            ['email' => Output::getClean($email), 'name' => Output::getClean($username)],
            SITE_NAME . ' - ' . $api->getLanguage()->get('emails', 'register_subject'),
            str_replace('[Link]', $link, Email::formatEmail('register', $api->getLanguage()))
        );

        if (isset($sent['error'])) {
            $api->getDb()->insert('email_errors', [
                    'type' => Email::API_REGISTRATION,
                    'content' => $sent['error'],
                    'at' => date('U'),
                    'user_id' => $user_id
            ]);

            $api->throwError(14, $api->getLanguage()->get('api', 'unable_to_send_registration_email'));
        }

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'finish_registration_email')]);
    }
}
