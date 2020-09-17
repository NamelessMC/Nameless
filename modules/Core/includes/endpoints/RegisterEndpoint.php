<?php

/**
 * Register a new user
 * 
 * Sends email verification if needed
 * 
 * @param string $username The username of the new user to create
 * @param string $uuid (optional) The Minecraft UUID of the new user
 * @param string $email The email of the new user
 * 
 * @return string JSON Array
 */
// TODO: Finish MC Integration checks etc
class RegisterEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'register';
        $this->_module = 'Core';
    }

    public function execute(Nameless2API $api) {
        if ($api->validateParams($_POST, ['username', 'email'])) {
            // Remove -s from UUID (if present)
            $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);
            if (strlen($_POST['uuid']) > 32) $api->throwError(9, $api->getLanguage()->get('api', 'invalid_uuid'));

            if (strlen($_POST['username']) > 20) $api->throwError(8, $api->getLanguage()->get('api', 'invalid_username'));
            if (strlen($_POST['email']) > 64) $api->throwError(7, $api->getLanguage()->get('api', 'invalid_email_address'));

            // Validate email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $api->throwError(7, $api->getLanguage()->get('api', 'invalid_email_address'));

            // Ensure user doesn't already exist
            $username = $api->getDb()->get('users', array('username', '=', htmlspecialchars($_POST['username'])));
            if (count($username->results())) $api->throwError(11, $api->getLanguage()->get('api', 'username_already_exists'));

            $uuid = $api->getDb()->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
            if (count($uuid->results())) $api->throwError(12, $api->getLanguage()->get('api', 'uuid_already_exists'));

            $email = $api->getDb()->get('users', array('email', '=', htmlspecialchars($_POST['email'])));
            if (count($email->results())) $api->throwError(10, $api->getLanguage()->get('api', 'email_already_exists'));

            // Registration email enabled?
            $registration_email = $api->getDb()->get('settings', array('name', '=', 'email_verification'));
            if ($registration_email->count()) $registration_email = $registration_email->first()->value;
            else $registration_email = 1;

            if ($registration_email) {
                // Send email
                $api->sendRegistrationEmail($_POST['username'], $_POST['uuid'], $_POST['email']);
            } else {
                // Register user + send link
                $code = $api->createUser($_POST['username'], $_POST['uuid'], $_POST['email']);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'finish_registration_link'), 'link' => rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . $code['code'])));
            }
        }
    }

}