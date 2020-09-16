<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Version 2.0.0 API
 *  API version 2.0.0
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
$api = new Nameless2API($route, $language);

class Nameless2API {

    private 
        $_validated = false,
        $_db,
        $_language;

    public function __construct($route, $api_language) {
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
                // API key specified
                $this->_validated = true;
                $request = explode('/', $route);
                $request = $request[count($request) - 1];

                // Dynamically call the requested function, if it does not exist, throw an error.
                if (method_exists($this, $request)) {
                    call_user_func($request);
                } else {
                    $this->throwError(3, $this->_language->get('api', 'invalid_api-method'));
                }

            } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
        } catch(Exception $e) {
            $this->throwError($e->getMessage());
        }
    }

    // API functions

    /**
     * Return info about the Nameless installation
     * 
     * No params
     * 
     * @return string JSON Array of NamelessMC information
     */
    private function info() {
        // Ensure the API key is valid
        if ($this->_validated === true) {
            // Get version, update info and modules from database
            $this->_db = DB::getInstance();

            $version_query = $this->_db->query('SELECT `name`, `value` FROM nl2_settings WHERE `name` = ? OR `name` = ? OR `name` = ? OR `name` = ?', array('nameless_version', 'version_checked', 'version_update', 'new_version'));
            if ($version_query->count())
                $version_query = $version_query->results();

            $site_id = $this->_db->get('settings', array('name', '=', 'unique_id'));
            if (!$site_id->count())
                $this->throwError(4, $this->_language->get('api', 'no_unique_site_id'));

            $site_id = $site_id->results();
            $site_id = $site_id[0]->value;

            $ret = array();
            foreach ($version_query as $item) {
                if ($item->name == 'nameless_version') {
                    $ret[$item->name] = $item->value;
                    $current_version = $item->value;
                } else if ($item->name == 'version_update')
                    $version_update = $item->value;
                else if ($item->name == 'version_checked')
                    $version_checked = (int) $item->value;
                else
                    $new_version = $item->value;
            }

            if (isset($version_checked) && isset($version_update) && isset($current_version)) {
                if ($version_update == 'false') {
                    if ($version_checked < strtotime('-1 hour')) {
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
                    }
                } else {
                    $ret['version_update'] = array('update' => true, 'version' => (isset($new_version) ? Output::getClean($new_version) : 'unknown'), 'urgent' => ($version_update == 'urgent'));
                }
            }
            $modules_query = $this->_db->get('modules', array('enabled', '=', 1));
            $ret_modules = array();
            if ($modules_query->count()) {
                $modules_query = $modules_query->results();
                foreach($modules_query as $module) {
                    $ret_modules[] = $module->name;
                }
            }
            $ret['modules'] = $ret_modules;

            if (count($ret)) {
                $this->returnArray($ret);
            }

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Return latest available announcements for the supplied user
     * 
     * @param int $id NamelessMC ID of the user whose announcements to view
     * 
     * @return string JSON Array of latest announcements
     */
    private function getAnnouncements() {
        if ($this->_validated === true) {

            $tempUser = null;

            if (isset($_GET['id'])) {
                $user_id = $_GET['id'];
                $tempUser = new User();
                $tempUser->find($user_id);
            }

            $announcements = array();

            foreach(Announcements::getAvailable('api', null, !is_null($tempUser) ? $tempUser->data()->group_id : 0, !is_null($tempUser) ? $tempUser->data()->secondary_groups : null) as $announcement) {
                $announcements[] = $announcement;
            }

            $this->returnArray(array('announcements' => $announcements));
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

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
    private function register() {
        if ($this->validateParams($_POST, ['username', 'email'])) {
            // Remove -s from UUID (if present)
            $_POST['uuid'] = str_replace('-', '', $_POST['uuid']);
            if (strlen($_POST['uuid']) > 32) $this->throwError(9, $this->_language->get('api', 'invalid_uuid'));

            if (strlen($_POST['username']) > 20) $this->throwError(8, $this->_language->get('api', 'invalid_username'));
            if (strlen($_POST['email']) > 64) $this->throwError(7, $this->_language->get('api', 'invalid_email_address'));

            // Validate email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $this->throwError(7, $this->_language->get('api', 'invalid_email_address'));

            // Ensure user doesn't already exist
            $this->_db = DB::getInstance();

            $username = $this->_db->get('users', array('username', '=', htmlspecialchars($_POST['username'])));
            if (count($username->results())) $this->throwError(11, $this->_language->get('api', 'username_already_exists'));

            $uuid = $this->_db->get('users', array('uuid', '=', htmlspecialchars($_POST['uuid'])));
            if (count($uuid->results())) $this->throwError(12, $this->_language->get('api', 'uuid_already_exists'));

            $email = $this->_db->get('users', array('email', '=', htmlspecialchars($_POST['email'])));
            if (count($email->results())) $this->throwError(10, $this->_language->get('api', 'email_already_exists'));

            // Registration email enabled?
            $registration_email = $this->_db->get('settings', array('name', '=', 'email_verification'));
            if ($registration_email->count()) $registration_email = $registration_email->first()->value;
            else $registration_email = 1;

            if ($registration_email) {
                // Send email
                $this->sendRegistrationEmail($_POST['username'], $_POST['uuid'], $_POST['email']);
            } else {
                // Register user + send link
                $code = $this->createUser($_POST['username'], $_POST['uuid'], $_POST['email']);
                $this->returnArray(array('message' => $this->_language->get('api', 'finish_registration_link'), 'link' => rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . $code['code'])));
            }
        }
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
    // TODO: Finish MC Integration checks etc
    private function createUser($username, $uuid, $email, $code = null) {
        if ($this->_validated === true) {
            try {
                // Get default group ID
                if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('default_group') . '.cache')) {
                    // Not cached, cache now
                    $this->_db = DB::getInstance();

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

            $this->_db = DB::getInstance();

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

            if ($mailer == '1') {
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

    /**
     * Get information about a NamelessMC user
     * 
     * @param int $id NamelessMC ID of user to view
     * @param string $username The NamelessMC username of the user to view
     * @param string $uuid The Minecraft UUID of the user
     * 
     * @return string JSON Array
     */
    private function userInfo() {
        if ($this->_validated === true) {
            if (isset($_GET['id']))
                $query = $_GET['id'];
            else if (isset($_GET['username']))
                $query = $_GET['username'];
            else if (isset($_GET['uuid']))
                $query = str_replace('-', '', $_GET['uuid']);
            else
                $this->throwError(26, $this->_language->get('api', 'invalid_get_contents'));

            // Ensure the user exists
            $this->_db = DB::getInstance();

            // Check UUID
            $user = $this->_db->query('SELECT nl2_users.id, nl2_users.username, nl2_users.nickname as displayname, nl2_users.uuid, nl2_users.group_id, nl2_users.joined as registered, nl2_users.isbanned as banned, nl2_users.active as validated, nl2_users.user_title as userTitle, nl2_groups.name as group_name FROM nl2_users LEFT JOIN nl2_groups ON nl2_users.group_id = nl2_groups.id WHERE nl2_users.id = ? OR nl2_users.username = ? OR nl2_users.uuid = ?', array($query, $query, $query));

            if (!$user->count()) {
                $this->returnArray(array('exists' => false));
            }
            $user = $user->first();
            $user->exists = true;
            $user->banned = ($user->banned) ? true : false;
            $user->validated = ($user->validated) ? true : false;

            $custom_profile_fields = $this->_db->query('SELECT fields.name, fields.type, fields.public, fields.description, pf_values.value FROM nl2_users_profile_fields pf_values LEFT JOIN nl2_profile_fields fields ON pf_values.field_id = fields.id WHERE pf_values.user_id = ?', array($user->id));
            $user->profile_fields = $custom_profile_fields->results();

            unset($user->id);

            $this->returnArray((array) $user);

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Set a user's primary NamelessMC group
     * 
     * @param int $id NamelessMC ID of user to view
     * @param int $group_id ID of NamelessMC group
     * 
     * @return string JSON Array
     */
    private function setGroup() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['id', 'group_id'])) {

                $this->_db = DB::getInstance();

                // Ensure user exists
                $user = $this->_db->get('users', array('id', '=', htmlspecialchars($_POST['id'])));
                if (!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));

                $user = $user->first()->id;

                // Ensure group exists
                $group = $this->_db->get('groups', array('id', '=', $_POST['group_id']));
                if (!$group->count()) $this->throwError(17, $this->_language->get('api', 'unable_to_find_group'));

                $group = $group->first()->id;

                try {
                    $this->_db->update('users', $user, array(
                        'group_id' => $group
                    ));
                } catch(Exception $e) {
                    $this->throwError(18, $this->_language->get('api', 'unable_to_update_group'));
                }

                $this->returnArray(array('message' => $this->_language->get('api', 'group_updated')));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Set a NamelessMC user's Discord ID using their validation token
     * 
     * @param string $token The token of the user to update
     * @param int $discord_id The user's Discord user ID to set
     * 
     * @return string JSON Array
     */
    private function setDiscordId() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['token', 'discord_id'])) {

                $token = Output::getClean($_POST['token']);
                $discord_id = $_POST['discord_id'];

                $this->_db = DB::getInstance();

                // Find their id 
                $id = $this->_db->get('discord_verifications', array('token', '=', $token));
                if (!$id->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
                $id = $id->first()->user_id;

                // Find the user with the id
                $user = $this->_db->get('users', array('id', '=', $id));
                if (!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
                $user = $user->first()->id;

                try {
                    $this->_db->update('users', $user, array(
                        'discord_id' => $discord_id
                    ));
                } catch (Exception $e) {
                    $this->throwError(23, $this->_language->get('api', 'unable_to_set_discord_id'));
                }
                $this->returnArray(array('message' => $this->_language->get('api', 'discord_id_set')));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Set a NamelessMC user's primary group from a provided Discord role ID
     * 
     * @param int $discord_user_id The Discord ID of the NamelessMC user to edit
     * @param int $discord_id The Discord ID fo the NamelessMC group to apply as their primary group
     * 
     * @return string JSON Array
     */
    private function setGroupFromDiscord() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['discord_user_id', 'discord_role_id'])) {

                $this->_db = DB::getInstance();

                if (!$this->_db->get('settings', array('name', '=', 'discord_integration'))->first()->value) {
                    $this->throwError(33, $this->_language->get('api', 'discord_integration_disabled'));
                }

                $discord_user_id = $_POST['discord_user_id'];
                $discord_role_id = $_POST['discord_role_id'];

                $user = $this->_db->get('users', array('discord_id', '=', $discord_user_id));
                if (!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
                $user = $user->first();

                $group = $this->_db->get('groups', array('discord_role_id', '=', $discord_role_id));
                if (!$group->count()) $this->throwError(17, $this->_language->get('api', 'unable_to_find_group'));
                $group = $group->first();

                // Set their secondary groups to all of their old secondary groups, except the new group - just incase
                $new_secondary_groups = array();
                foreach ($user->secondary_groups as $secondary_group) {
                    if ($group != $secondary_group) {
                        $new_secondary_groups[] = $secondary_group;
                    }
                }

                try {
                    $this->_db->update('users', $user->id, array(
                        'group_id' => $group->id
                    ));
                    $this->_db->update('users', $user->id, array(
                        'secondary_groups' => json_encode($new_secondary_groups)
                    ));
                } catch (Exception $e) {
                    $this->throwError(18, $this->_language->get('api', 'unable_to_update_group'));
                }
                Log::getInstance()->log(Log::Action('discord/role_add'), 'Role changed to: ' . $group->name, $user->id);
                // Success
                $this->returnArray(array('message' => $this->_language->get('api', 'group_updated')));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Remove a user's role from NamelessMC when it was removed from Discord
     * 
     * Sets their role to the VALIDATED_DEFAULT group (Editable from StaffCP)
     * 
     * @param int $discord_user_id The NamelessMC user's Discord user ID to edit
     * @param int $discord_role_id The Discord role ID to verify exists on the site and to remove
     * 
     * @return string JSON Array
     */
    private function removeGroupFromDiscord() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['discord_user_id', 'discord_role_id'])) {

                $this->_db = DB::getInstance();

                if (!$this->_db->get('settings', array('name', '=', 'discord_integration'))->first()->value) {
                    $this->throwError(33, $this->_language->get('api', 'discord_integration_disabled'));
                }

                $discord_user_id = $_POST['discord_user_id'];
                $discord_role_id = $_POST['discord_role_id'];

                $user = $this->_db->get('users', array('discord_id', '=', $discord_user_id));
                if (!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
                $user = $user->first()->id;

                $group = $this->_db->get('groups', array('discord_role_id', '=', $discord_role_id));
                if (!$group->count()) $this->throwError(17, $this->_language->get('api', 'unable_to_find_group'));

                try {
                    $this->_db->update('users', $user, array(
                        'group_id' => VALIDATED_DEFAULT
                    ));
                } catch (Exception $e) {
                    $this->throwError(18, $this->_language->get('api', 'unable_to_update_group'));
                }
                Log::getInstance()->log(Log::Action('discord/role_remove'), 'Role removed: ' . $group->first()->name, $user);
                // Success
                $this->returnArray(array('message' => $this->_language->get('api', 'group_updated')));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Create a report
     * 
     * @param string $reporter The NamelessMC username of the user who is creating the report
     * @param string $reported The NamelessMC username of the user who is getting reported
     * @param string $content The content of the report
     * 
     * @return string JSON Array
     */
    private function createReport() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['reporter', 'reported', 'content'])) {

                // Ensure content is correct length
                if (strlen($_POST['content']) > 255) $this->throwError(19, $this->_language->get('api', 'report_content_too_long'));

                // Ensure user reporting has website account, and has not been banned
                $this->_db = DB::getInstance();
                $user_reporting = $this->_db->get('users', array('username', '=', Output::getClean($_POST['reporter'])));

                if (!$user_reporting->count()) $this->throwError(20, $this->_language->get('api', 'you_must_register_to_report'));
                else $user_reporting = $user_reporting->first();

                if ($user_reporting->isbanned) $this->throwError(21, $this->_language->get('api', 'you_have_been_banned_from_website'));

                // See if reported user exists
                $user_reported = $this->_db->get('users', array('username', '=', Output::getClean($_POST['reported'])));

                if (!$user_reported->count()) $user_reported = 0;
                else $user_reported = $user_reported->first()->id;

                // Ensure user has not already reported the same player, and the report is open
                $user_reports = $this->_db->get('reports', array('reporter_id', '=', $user_reporting->id));
                if (count($user_reports->results())) {
                    foreach($user_reports->results() as $report) {
                        if ($report->reported_id == $user_reported) {
                            if ($report->status == 0) $this->throwError(22, $this->_language->get('api', 'you_have_open_report_already'));
                        }
                    }
                }

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

                } catch(Exception $e) {
                    $this->throwError(23, $this->_language->get('api', 'unable_to_create_report'));
                }
            }

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Get notifications for a user
     * 
     * @see Alert
     * 
     * @param int $id The NamelessMC user to get notifications for
     *
     * @return string JSON Array
     */
    private function getNotifications() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['id'])) {

                // Ensure the user exists
                $this->_db = DB::getInstance();

                $user = $this->_db->query('SELECT id FROM nl2_users WHERE id = ?', array($_GET['id']));

                if (!$user->count()) {
                    $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
                }
                $user = $user->first()->id;

                $return = array('notifications' => array());

                // Get unread alerts
                $alerts = $this->_db->query('SELECT id, type, url, content_short FROM nl2_alerts WHERE user_id = ? AND `read` = 0', array($user));
                if ($alerts->count()) {
                    foreach($alerts->results() as $result) {
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

                if ($messages->count()) {
                    foreach($messages->results() as $result) {
                        $return['notifications'][] = array(
                            'type' => 'message',
                            'url' => Util::getSelfURL() . ltrim(URL::build('/user/messaging/', 'action=view&message=' . $result->id), '/'),
                            'message_short' => $result->title,
                            'message' => $result->title
                        );
                    }
                }

                $this->returnArray($return);
            }

        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Update a username from a UUID
     * 
     * @param int $id The NamelessMC user to update
     * @param string $uuid The Minecraft UUID of the user
     * @param string $username The new username of the user
     *
     * @return string JSON Array
     */
    private function updateUsername() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['id', 'username'])) {

                $this->_db = DB::getInstance();

                // Ensure user exists
                $user = $this->_db->get('users', array('id', '=', Output::getClean($_POST['id'])));
                if (!$user->count()) $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));

                $user = $user->first()->id;

                // Update just Minecraft username, or displayname too?
                $displaynames = $this->_db->get('settings', array('name', '=', 'displaynames'));
                if (!$displaynames->count()) $displaynames = 'false';
                else $displaynames = $displaynames->first()->value;

                $fields = array('username' => Output::getClean($_POST['username']));

                if ($displaynames == 'false')
                    $fields['nickname'] = Output::getClean($_POST['username']);

                try {
                    $this->_db->update('users', $user, $fields);

                } catch(Exception $e) {
                    $this->throwError(24, $this->_language->get('api', 'unable_to_update_username'));
                }

                $this->returnArray(array('message' => $this->_language->get('api', 'username_updated')));
            }
        } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
    }

    /**
     * Update the Minecraft server information NamelessMC tracks
     * 
     * @param array $info Minecraft server info
     *
     * @return string JSON Array
     */
    private function serverInfo() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['info'])) {

                $info = json_decode($_POST['info'], true);
                if (!isset($info['server-id']) || !isset($info['max-memory']) || !isset($info['free-memory']) || !isset($info['allocated-memory']) || !isset($info['tps']) || !isset($info['players']) || !isset($info['groups']))
                    $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));

                $this->_db = DB::getInstance();

                // Ensure server exists
                $server_query = $this->_db->get('mc_servers', array('id', '=', $info['server-id']));
                if (!$server_query->count()) $this->throwError(27, $this->_language->get('api', 'invalid_server_id'));

                try {
                    $this->_db->insert('query_results', array(
                        'server_id' => $info['server-id'],
                        'queried_at' => date('U'),
                        'players_online' => count($info['players']),
                        'extra' => $_POST['info'],
                        'groups' => json_encode($info['groups'])
                    ));

                    if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache')) {
                        $query_cache = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache');
                        $query_cache = json_decode($query_cache);
                        if (isset($query_cache->query_interval))
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

                } catch(Exception $e) {
                    $this->throwError(25, $this->_language->get('api', 'unable_to_update_server_info'));
                }

                // Update usernames
                try {
                    $update_usernames = $this->_db->get('settings', array('name', '=', 'username_sync'))->results();
                    $update_usernames = $update_usernames[0]->value;

                    if ($update_usernames == '1') {
                        if (count($info['players'])) {
                            // Update just Minecraft username, or displayname too?
                            $displaynames = $this->_db->get('settings', array('name', '=', 'displaynames'));
                            if (!$displaynames->count()) $displaynames = 'false';
                            else $displaynames = $displaynames->first()->value;

                            foreach($info['players'] as $uuid => $player) {
                                $user = new User();
                                if ($user->find($uuid, 'uuid')) {
                                    if ($player['name'] != $user->data()->username) {
                                        // Update username
                                        if ($displaynames == 'false') {
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
                } catch(Exception $e) {
                    $this->throwError(25, $this->_language->get('api', 'unable_to_update_server_info'));
                }

                // Group sync
                try {
                    $group_sync = $this->_db->get('group_sync', array('id', '<>', 0));

                    if ($group_sync->count()) {
                        $group_sync = $group_sync->results();
                        $group_sync_updates = array();
                        foreach($group_sync as $item) {
                            $group_sync_updates[strtolower($item->ingame_rank_name)] = array(
                                'website' => $item->website_group_id,
                                'primary' => $item->primary
                            );
                        }

                        if (count($info['players'])) {
                            foreach($info['players'] as $uuid => $player) {
                                $user = new User();
                                if ($user->find($uuid, 'uuid')) {
                                    if ($user->data()->id != 1) {
                                        // Can't update root user
                                        $rank = strtolower($player['rank']);

                                        if (array_key_exists($rank, $group_sync_updates) && $user->data()->group_id != $group_sync_updates[$rank]['website']) {
                                            $new_rank = $group_sync_updates[$rank];

                                            if ($new_rank['primary']) {
                                                $user->update(array(
                                                    'group_id' => $new_rank['website']
                                                ), $user->data()->id);
                                            } else {
                                                if ($user->data()->secondary_groups)
                                                    $secondary = json_decode($user->data()->secondary_groups, true);
                                                else
                                                    $secondary = array();

                                                if (!in_array($new_rank['website'], $secondary)) {
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

                } catch(Exception $e) {
                    $this->throwError(25, $this->_language->get('api', 'unable_to_update_server_info'));
                }

                $this->returnArray(array('message' => $this->_language->get('api', 'server_info_updated')));

            } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
        }
    }

    /**
     * Validate/Activate a NamelessMC account by confirming their reset code
     * 
     * @param int $id The NamelessMC user's ID
     * @param string $code The NamelessMC user's reset code, used to verify they own the account
     *
     * @return string JSON Array
     */
    private function verifyMinecraft() {
        if ($this->_validated === true) {
            if ($this->validateParams($_POST, ['id', 'code'])) {
                $this->_db = DB::getInstance();
                $user_query = $this->_db->get('users', array('id', '=', $_POST['id']));
                if ($user_query->count()) {
                    $user_query = $user_query->first();

                    if ($user_query->reset_code == $_POST['code']) {
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
                        } catch(Exception $e) {
                            // Error
                        }

                        $this->returnArray(array('message' => $this->_language->get('api', 'account_validated')));

                    } else
                        $this->throwError(28, $this->_language->get('api', 'invalid_code'));

                } else
                    $this->throwError(16, $this->_language->get('api', 'unable_to_find_user'));
            }
        }
    }

    /**
     * List all users on the NamelessMC site
     *
     * @return string JSON Array
     */
	private function listUsers() {
		// Ensure the API key is valid
		if ($this->_validated === true) {
			$this->_db = DB::getInstance();

			$users = $this->_db->query('SELECT username, uuid, isbanned AS banned, active FROM nl2_users')->results();

			$this->returnArray(array('users' => $users));
		}
    }

    // Internal functions

    private function validateKey($api_key = null) {
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

    private function throwError($code = null, $message = null) {
        if ($code && $message) {
            die(json_encode(array('error' => true, 'code' => $code, 'message' => $message), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error')), JSON_PRETTY_PRINT));
        }
    }

    private function returnArray($arr = null) {
        if (!$arr) $arr = array();

        $arr['error'] = false;
        die(json_encode($arr, JSON_PRETTY_PRINT));
    }
    
    private function validateParams($input, $required_fields) {
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
