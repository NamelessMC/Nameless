<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  User class
 */
class User {
    
    /** @var DB */
    private $_db;
    
    private $_data,
            $_groups,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn,
            $_admSessionName,
            $_isAdmLoggedIn;

    public function __construct($user = null, $field = 'id') {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        $this->_admSessionName = Config::get('session/admin_name');
        $this->_placeholders = [];

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user, $field)) {
                    $this->_isLoggedIn = true;
                }
            }
            if (Session::exists($this->_admSessionName)) {
                $user = Session::get($this->_admSessionName);
                if ($user == $this->data()->id && $this->find($user, $field)) {
                    $this->_isAdmLoggedIn = true;
                }
            }
        } else {
            $this->find($user, $field);
        }
    }

    /**
     * Get this user's main group CSS styling
     *
     * @return string|bool Styling on success, false if they have no groups.
     */
    public function getGroupClass() {
        $groups = $this->_groups;
        if (count($groups)) {
            foreach ($groups as $group) {
                return 'color:' . htmlspecialchars($group->group_username_color) . '; ' . htmlspecialchars($group->group_username_css);
            }
        }

        return false;
    }
    
    /**
     * Get the logged in user's IP address.
     *
     * @return string Their IP.
     */
    public function getIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Update a user's data in the database.
     *
     * @param array $fields Column names and values to update.
     * @param int $id If not supplied, will use ID of logged in user.
     */
    public function update($fields = array(), $id = null) {

        if (!$id) {
            $id = $this->data()->id;
        }

        if (!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating your details.');
        }
    }

    /**
     * Create a new user.
     *
     * @param array $fields Column names and values to insert to database.
     */
    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    /**
     * Find a user by unique identifier (username, ID, email, etc).
     * Loads instance variables for this class.
     *
     * @param string $value Unique identifier.
     * @param string $field What column to check for their unique identifier in.
     * @return bool True/false on success or failure respectfully.
     */
    public function find($value = null, $field = 'id') {
        if ($value) {
            $data = $this->_db->get('users', array($field, '=', $value));

            if ($data->count()) {
                $this->_data = $data->first();

                // Get user groups
                $groups_query = $this->_db->query('SELECT nl2_groups.* FROM nl2_users_groups INNER JOIN nl2_groups ON group_id = nl2_groups.id WHERE user_id = ? AND deleted = 0 ORDER BY `order`;', array($this->_data->id));
                
                if ($groups_query->count()) {
                    
                    $groups_query = $groups_query->results();
                    foreach ($groups_query as $item) {
                        $this->_groups[$item->id] = $item;
                    }

                } else {
                    // Get default group
                    // TODO: Use PRE_VALIDATED_DEFAULT ?
                    $default_group = $this->_db->query('SELECT * FROM nl2_groups WHERE default_group = 1', array())->first();
                    if ($default_group) {
                        $default_group_id = $default_group->id;
                    } else {
                        $default_group_id = 1; // default to 1
                        $default_group = $this->_db->query('SELECT * FROM nl2_groups WHERE id = 1', array())->first();
                    }
                    
                    $this->addGroup($default_group_id, 0, $default_group);
                }

                // Get their placeholders only if they have a valid uuid
                if ($this->_data->uuid != null && $this->_data->uuid != 'none') {

                    $placeholders = Placeholders::getInstance()->loadUserPlaceholders($this->_data->uuid);

                    if (count($placeholders)) {
                        $this->_placeholders = $placeholders;
                    }
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Get a user's username from their ID.
     *
     * @param int $id Their ID.
     * @return string|bool Their username, false on failure.
     */
    public function idToName($id = null) {
        if ($id) {
            $data = $this->_db->get('users', array('id', '=', $id));

            if ($data->count()) {
                $results = $data->results();
                return $results[0]->username;
            }
        }

        return false;
    }

    /**
     * Get a user's nickname from their ID.
     *
     * @param int $id Their ID.
     * @return string|bool Their nickname, false on failure.
     */
    public function idToNickname($id = null) {
        if ($id) {
            $data = $this->_db->get('users', array('id', '=', $id));

            if ($data->count()) {
                $results = $data->results();
                return $results[0]->nickname;
            }
        }

        return false;
    }

    /**
     * Log the user in.
     *
     * @param string $username Their username (or email, depending on $method).
     * @param string $password Their password.
     * @param bool|null $remember Whether to keep them logged in or not.
     * @param string|null $method What column to check for their details in. Can be either `username` or `email`.
     * @return bool True/false on success or failure respectfully.
     */
    public function login($username = null, $password = null, $remember = false, $method = 'email') {
        if (!$username && !$password && $this->exists()) {

            Session::put($this->_sessionName, $this->data()->id);
            $this->_isLoggedIn = true;
            
        } else {

            if ($this->checkCredentials($username, $password, $method) === true) {
                // Valid credentials
                Session::put($this->_sessionName, $this->data()->id);

                if ($remember) {
                    $hash = Hash::unique();
                    $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                    if (!$hashCheck->count()) {
                        $this->_db->insert('users_session', array(
                            'user_id' => $this->data()->id,
                            'hash' => $hash
                        ));
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }

                    Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Handle StaffCP logins.
     *
     * @param string $username Their username (or email, depending on $method).
     * @param string $password Their password.
     * @param string|null $method What column to check for their details in. Can be either `username` or `email`.
     * @return bool True/false on success or failure respectfully.
     */
    public function adminLogin($username = null, $password = null, $method = 'email') {
        if (!$username && !$password && $this->exists()) {

            Session::put($this->_admSessionName, $this->data()->id);
            
        } else {

            if ($this->checkCredentials($username, $password, $method) === true) {
                Session::put($this->_admSessionName, $this->data()->id);

                $hash = Hash::unique();
                $hashCheck = $this->_db->get('users_admin_session', array('user_id', '=', $this->data()->id));

                if (!$hashCheck->count()) {
                    $this->_db->insert('users_admin_session', array(
                        'user_id' => $this->data()->id,
                        'hash' => $hash
                    ));
                } else {
                    $hash = $hashCheck->first()->hash;
                }

                Cookie::put($this->_cookieName . "_adm", $hash, 3600);

                return true;
            }
        }

        return false;
    }

    /**
     * Check whether given credentials are valid.
     *
     * @param string $username Username (or email) to check.
     * @param string $password Password entered by user.
     * @param string $method Column to search for user with. Can be `email` or `username`.
     * @return bool True if correct, false otherwise.
     */
    public function checkCredentials($username, $password, $method = 'email') {
        $user = $this->find($username, $method);
        if ($user) {
            switch ($this->data()->pass_method) {
                case 'wordpress':
                    // phpass
                    $phpass = new PasswordHash(8, FALSE);

                    return ($phpass->checkPassword($password, $this->data()->password));
                    break;

                case 'sha256':
                    $exploded = explode('$', $this->data()->password);

                    $salt = $exploded[0];
                    $pass = $exploded[1];

                    return ($salt . hash('sha256', hash('sha256', $password) . $salt) == $salt . $pass);
                    break;

                case 'pbkdf2':
                    $exploded = explode('$', $this->data()->password);

                    $iterations = $exploded[0];
                    $salt = $exploded[1];
                    $pass = $exploded[2];

                    $hashed = hash_pbkdf2('sha256', $password, $salt, $iterations, 64, true);

                    return ($hashed == hex2bin($pass));
                    break;

                case 'modernbb':
                case 'sha1':
                    return (sha1($password) == $this->data()->password);
                    break;

                default:
                    // Default to bcrypt
                    return (password_verify($password, $this->data()->password));
                    break;
            }
        }

        return false;
    }

    /**
     * Get user's display name.
     *
     * @param bool|null $username If true, will use their username. If false, will use their nickname.
     * @return string Their display name.
     */
    public function getDisplayName($username = false) {
        if ($username) {
            return Output::getClean($this->_data->username);
        }
        
        return Output::getClean($this->_data->nickname);
    }

    /**
     * Build this user's profile link.
     *
     * @return string Compiled profile URL.
     */
    public function getProfileURL() {
        return Output::getClean(URL::build("/profile/" . $this->data()->username));
    }

    /**
     * Get all of a user's groups. We can return their ID only or their HTML display code.
     *
     * @param mixed $html If not null, will use group_html column instead of ID.
     * @return array Array of all their group's IDs or HTML.
     */
    public function getAllGroups($html = null) {
        $groups = array();

        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                if (is_null($html)) {
                    $groups[] = $group->id;
                } else {
                    $groups[] = $group->group_html;
                }
            }
        }
        
        return $groups;
    }
   
    /**
     * Get all of a user's groups id.
     *
     * @param bool $login_check If true, will first check if this user is logged in or not. Set to "false" for API usage.
     * @return array Array of all their group IDs.
     */
    public function getAllGroupIds($login_check = true) {
        if ($login_check) {
            if (!$this->isLoggedIn()) {
                return array(0);
            }
        }

        $groups = array();

        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                $groups[$group->id] = $group->id;
            }
        }

        return $groups;
    }

    /**
     * Get this user's signature.
     *
     * @return string Their signature.
     */
    public function getSignature() {
        if (empty($this->data()->signature)) {
            return '';
        }

        return $this->data()->signature;
    }

    /**
     * Get this user's avatar.
     *
     * @param int $size Size of image to render in pixels.
     * @param bool $full Whether to use full site URL or not, for external loading - ie discord webhooks.
     * @return string URL to their avatar image.
     */
    public function getAvatar($size = 128, $full = false) {

        // If custom avatars are enabled, first check if they have gravatar enabled, and then fallback to normal image
        if (defined('CUSTOM_AVATARS')) {

            if ($this->data()->gravatar) {
                return "https://secure.gravatar.com/avatar/" . md5(strtolower(trim($this->data()->email))) . "?s=" . $size;
            }

            if ($this->data()->has_avatar) {
                $exts = array('png', 'jpg', 'jpeg');

                if ($this->hasPermission('usercp.gif_avatar')) {
                    $exts[] = 'gif';
                }

                foreach ($exts as $ext) {
                    if (file_exists(ROOT_PATH . "/uploads/avatars/" . $this->data()->id . "." . $ext)) {
                        return ($full ? rtrim(Util::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . "uploads/avatars/" . $this->data()->id . "." . $ext . '?v=' . Output::getClean($this->data()->avatar_updated);
                    }
                }
            }
        }

        // Fallback to default avatar image if it is set and the avatar type is custom
        if (defined('DEFAULT_AVATAR_TYPE') && DEFAULT_AVATAR_TYPE == 'custom') {
            if (file_exists(ROOT_PATH . '/uploads/avatars/defaults/' . DEFAULT_AVATAR_IMAGE)) {
                return ($full ? rtrim(Util::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars/defaults/' . DEFAULT_AVATAR_IMAGE;
            }
        }

        // If all else fails, or custom avatars are disabled or default avatar type is 'minecraft', get their MC avatar
        if ($this->data()->uuid != null && $this->data()->uuid != 'none') {
            $uuid = $this->data()->uuid;
        } else {
            $uuid = $this->data()->username;
            // Fallback to steve avatar if they have an invalid username
            if (preg_match("#[^][_A-Za-z0-9]#", $uuid)) {
                $uuid = 'Steve';
            }
        }

        return AvatarSource::getAvatarFromUUID($uuid, $size);
    }

    /**
     * If the user has infractions, list them all. Or else return false.
     * Not used internally.
     * 
     * @return array|bool Array of infractions if they have one or more, else false.
     */
    public function hasInfraction() {
        $data = $this->_db->get('infractions', array('punished', '=', $this->data()->id))->results();
        if (empty($data)) {
            return false;
        }

        $return = array();
        $n = 0;
        foreach ($data as $infraction) {
            if ($infraction->acknowledged == '0') {
                $return[$n]["id"] = $infraction->id;
                $return[$n]["staff"] = $infraction->staff;
                $return[$n]["reason"] = $infraction->reason;
                $return[$n]["date"] = $infraction->infraction_date;
                $n++;
            }
        }
        
        return $return;
    }

    /**
     * Does this user exist?
     *
     * @return bool Whether the user exists (has data) or not.
     */
    public function exists() {
        return (!empty($this->_data));
    }

    /**
     * Log the user out.
     * Deletes their cookies, sessions and database session entry.
     */
    public function logout() {

        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
 
    /**
     * Process logout if user is admin
     */
    public function admLogout() {

        $this->_db->delete('users_admin_session', array('user_id', '=', $this->data()->id));

        Session::delete($this->_admSessionName);
        Cookie::delete($this->_cookieName . '_adm');
    }
  
    /**
     * Get the currently logged in user's data.
     *
     * @return object This user's data.
     */
    public function data() {
        return $this->_data;
    }

    /**
     * Get the currently logged in user's groups.
     *
     * @return array Their groups.
     */
    public function getGroups() {
        return $this->_groups;
    }

    /**
     * Get the currently logged in user's placeholders.
     * 
     * @return array Their placeholders.
     */
    public function getPlaceholders() {
        return $this->_placeholders;
    }

    /**
     * Get this user's placeholders to display on their profile.
     * 
     * @return array Profile placeholders.
     */
    public function getProfilePlaceholders() {
        return array_filter($this->_placeholders, static function ($placeholder) {
            return $placeholder->show_on_profile;
        });
    }

    /**
     * Get this user's placeholders to display on their forum posts.
     * 
     * @return array Forum placeholders.
     */
    public function getForumPlaceholders() {
        return array_filter($this->_placeholders, static function ($placeholder) {
            return $placeholder->show_on_forum;
        });
    }

    /**
     * Get this user's main group (with highest order).
     *
     * @return object The group
     */
    public function getMainGroup() {
        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                return $group;
            }
        }

        return false;
    }
   
    /**
     * Set a group to user and remove all other groups
     *
     * @param int $group_id ID of group to set as main group.
     * @param int|null $expire Expiry in epoch time. If not supplied, group will never expire.
     * @param array|null $group_data Load data from existing query.
     */
    public function setGroup($group_id, $expire = 0, $group_data = null) {
        if ($this->data()->id == 1) {
            return false;
        }
        $this->_db->createQuery('DELETE FROM `nl2_users_groups` WHERE `user_id` = ?', array($this->data()->id));

        $this->_db->createQuery(
            'INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)',
            array(
                $this->data()->id,
                $group_id,
                date('U'),
                $expire
            )
        );
        
        $this->_groups = array();
        if($group_data == null) {
            $group_data = $this->_db->get('groups', array('id', '=', $group_id));
            if ($group_data->count()) {
                $this->_groups[$group_id] = $group_data->first();
            }
        } else {
            $this->_groups[$group_id] = $group_data;
        }
    }

    /**
     * Add a group to this user.
     *
     * @param int $group_id ID of group to give.
     * @param int|null $expire Expiry in epoch time. If not supplied, group will never expire.
     * @param array|null $group_data Load data from existing query.
     * @return bool True on success, false if they already have it.
     */
    public function addGroup($group_id, $expire = 0, $group_data = null) {
        $groups = $this->_groups ? $this->_groups : [];

        if (array_key_exists($group_id, $groups)) {
            return false;
        }

        $this->_db->createQuery(
            'INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)',
            array(
                $this->data()->id,
                $group_id,
                date('U'),
                $expire
            )
        );
        
        if($group_data == null) {
            $group_data = $this->_db->get('groups', array('id', '=', $group_id));
            if ($group_data->count()) {
                $this->_groups[$group_id] = $group_data->first();
            }
        } else {
            $this->_groups[$group_id] = $group_data;
        }

        return true;
    }

    /**
     * Remove a group from the user.
     *
     * @param int $group_id ID of group to remove.
     * @return bool Returns false if they did not have this group or the admin group is being removed from root user
     */
    public function removeGroup($group_id) {
        $groups = $this->_groups ? $this->_groups : [];
        
        if (!array_key_exists($group_id, $groups)) {
            return false;
        }

        if ($group_id == 2 && $this->data()->id == 1) {
            return false;
        }

        $this->_db->createQuery(
            'DELETE FROM `nl2_users_groups` WHERE `user_id` = ? AND `group_id` = ?',
            array(
                $this->data()->id,
                $group_id
            )
        );
        
        unset($this->_groups[$group_id]);

        return true;
    }

    /**
     * Removes all groups this user has.
     */
    public function removeGroups() {
        $where = 'WHERE `user_id` = ?';
        
        if ($this->data()->id == 1) {
            $where .= ' AND `group_id` <> 2';
        }

        $this->_db->createQuery('DELETE FROM `nl2_users_groups` ' . $where, array($this->data()->id));
        
        $this->_groups = array();
    }

    /**
     * Get if this user is currently logged in or not.
     *
     * @return bool Whether they're logged in.
     */
    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }

    /**
     * Get if the current user is authenticated as an administrator.
     *
     * @return bool Whether they're logged in as admin.
     */
    public function isAdmLoggedIn() {
        return $this->_isAdmLoggedIn;
    }

    /**
     * Get if this user is active/validated or not.
     *
     * @return bool Whether this user has been validated/activated.
     */
    public function isValidated() {
        return $this->data()->active;
    }

    /**
     * Get a comma separated string of all users.
     * For the new private message dropdown.
     *
     * @return string CSV list of user's usernames.
     */
    public function listAllUsers() {
        $data = $this->_db->get('users', array('id', '<>', '0'))->results();
        $return = '';

        foreach ($data as $item) {
            $return .= '"' . $item->username . '",';
        }
        
        return rtrim($return, ',');
    }
  
    /**
     * Return an ID from a username.
     *
     * @param string $username Username to get ID for.
     * @return int|bool ID on success, false on failure.
     */
    public function nameToId($username = null) {
        if ($username) {
            $data = $this->_db->get('users', array('username', '=', $username));

            if ($data->count()) {
                $results = $data->results();
                return $results[0]->id;
            }
        }
        
        return false;
    }

    /**
     * Return an ID from an email.
     *
     * @param string $email Email to get ID for.
     * @return int|bool ID on success, false on failure.
     */
    public function emailToId($email = null) {
        if ($email) {
            $data = $this->_db->get('users', array('email', '=', $email));

            if ($data->count()) {
                $results = $data->results();
                return $results[0]->id;
            }
        }
        
        return false;
    }
   
    /**
     * Get a list of PMs a user has access to.
     *
     * @param int $user_id ID of user to get PMs for.
     * @return array|bool Array of PMs, false on failure.
     */
    public function listPMs($user_id = null) {
        if ($user_id) {
            $return = array(); // Array to return containing info of PMs

            // Get a list of PMs which the user is in
            $data = $this->_db->get('private_messages_users', array('user_id', '=', $user_id));

            if ($data->count()) {
                $data = $data->results();
                foreach ($data as $result) {
                    // Get a list of users who are in this conversation and return them as an array
                    $pms = $this->_db->get('private_messages_users', array('pm_id', '=', $result->pm_id))->results();
                    $users = array(); // Array containing users with permission
                    foreach ($pms as $pm) {
                        $users[] = $pm->user_id;
                    }

                    // Get the PM data
                    $pm = $this->_db->get('private_messages', array('id', '=', $result->pm_id))->results();
                    $pm = $pm[0];

                    $return[$pm->id]['id'] = $pm->id;
                    $return[$pm->id]['title'] = Output::getClean($pm->title);
                    $return[$pm->id]['created'] = $pm->created;
                    $return[$pm->id]['updated'] = $pm->last_reply_date;
                    $return[$pm->id]['user_updated'] = $pm->last_reply_user;
                    $return[$pm->id]['users'] = $users;
                }
            }
            // Order the PMs by date updated - most recent first
            usort(
                $return,
                function ($a, $b) {
                    return $b['updated'] - $a['updated'];
                }
            );

            return $return;
        }

        return false;
    }
   
    /**
     * Get a specific private message, and see if the user actually has permission to view it
     *
     * @param int $pm_id ID of PM to find.
     * @param int $user_id ID of user to check permission for.
     * @return array|bool Array of info about PM, false on failure.
     */
    public function getPM($pm_id = null, $user_id = null) {
        if ($user_id && $pm_id) {
            // Get the PM - is the user the author?
            $data = $this->_db->get('private_messages', array('id', '=', $pm_id));
            if ($data->count()) {
                $data = $data->results();
                $data = $data[0];

                // Does the user have permission to view the PM?
                $pms = $this->_db->get('private_messages_users', array('pm_id', '=', $pm_id))->results();
                foreach ($pms as $pm) {
                    if ($pm->user_id == $user_id) {
                        $has_permission = true;
                        $pm_user_id = $pm->id;
                        break;
                    }
                }

                if (!isset($has_permission)) {
                    return false; // User doesn't have permission
                }

                // Set message to "read"
                if ($pm->read == 0) {
                    $this->_db->update('private_messages_users', $pm_user_id, array(
                        '`read`' => 1
                    ));
                }

                // User has permission, return the PM information

                // Get a list of users in the conversation
                if (!isset($pms)) {
                    $pms = $this->_db->get('private_messages_users', array('pm_id', '=', $pm_id))->results();
                }

                $users = array(); // Array to store users
                foreach ($pms as $pm) {
                    $users[] = $pm->user_id;
                }

                return array($data, $users);
            }
        }

        return false;
    }
    
    /**
     * Delete a user's access to view the PM, or if they're the author, the PM itself.
     *
     * @param int $pm_id ID of Pm to delete.
     * @param int $user_id ID of user to use.
     * @return bool Whether the action succeeded or not.
     */
    public function deletePM($pm_id = null, $user_id = null) {
        if ($user_id && $pm_id) {
            // Is the user the author?
            $data = $this->_db->get('private_messages', array('id', '=', $pm_id));
            if ($data->count()) {
                $data = $data->results();
                $data = $data[0];
                if ($data->author_id != $user_id) {
                    // User is not the author, only delete
                    $pms = $this->_db->get('private_messages_users', array('pm_id', '=', $pm_id))->results();
                    foreach ($pms as $pm) {
                        if ($pm->user_id == $user_id) {
                            // get the ID and delete
                            $id = $pm->id;
                            $this->_db->delete('private_messages_users', array('id', '=', $id));
                            return true;
                        }
                    }
                } else {
                    // User is the author, delete the PM altogether
                    $this->_db->delete('private_messages_users', array('pm_id', '=', $pm_id));
                    $this->_db->delete('private_messages', array('id', '=', $pm_id));
                    return true;
                }
            }
        }
        
        return false;
    }

    // Get the number of unread PMs for the specified user
    public function getUnreadPMs($user_id = null) {
        if ($user_id) {
            $pms = $this->_db->get('private_messages_users', array('user_id', '=', $user_id));
            if ($pms->count()) {
                $pms = $pms->results();
                $count = 0;
                foreach ($pms as $pm) {
                    if ($pm->read == 0) {
                        $count++;
                    }
                }
                return $count;
            } else {
                return 0;
            }
        }
        
        return false;
    }
  
    /**
     * Can the specified user view StaffCP?
     *
     * @return bool Whether they can view it or not.
     */
    public function canViewStaffCP() {
        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                if ($group->admin_cp == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check the user's permission to see if they can view this staffCP page or not.
     * If they cannot, this will handle appropriate redirection.
     *
     * @param string $permission Permission required for this page.
     */
    public function handlePanelPageLoad($permission = null) {
        // Set page user is trying to access in session, to allow for redirection post-auth
        if (FRIENDLY_URLS === true) {
            $split = explode('?', $_SERVER['REQUEST_URI']);

            if (count($split) > 1)
                $_SESSION['last_page'] = URL::build($split[0], $split[1]);
            else
                $_SESSION['last_page'] = URL::build($split[0]);
        } else
            $_SESSION['last_page'] = URL::build($_GET['route']);

        if (defined('CONFIG_PATH'))
            $_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));

        if (!$this->isLoggedIn()) {
            Redirect::to(URL::build('/login'));
            die();
        }

        if (!$this->canViewStaffCP()) {
            Redirect::to(URL::build('/'));
            die();
        }

        if (!$this->isAdmLoggedIn()) {
            Redirect::to(URL::build('/panel/auth'));
            die();
        }

        if ($permission != null && !$this->hasPermission($permission)) {
            return false;
        }
        return true;
    }

    /**
     * Get profile fields for specified user
     *
     * @param int $user_id User to retrieve fields for.
     * @param bool|null $public Whether to only return public fields or not (default `true`).
     * @param bool|null $forum Whether to only return fields which display on forum posts, only if $public is true (default `false`).
     * @return array|bool Array of profile fields. False on failure.
     */
    public function getProfileFields($user_id = null, $public = true, $forum = false) {
        if ($user_id) {
            $data = $this->_db->get('users_profile_fields', array('user_id', '=', $user_id));

            if ($data->count()) {
                if ($public == true) {
                    // Return public fields only
                    $return = array();
                    foreach ($data->results() as $result) {
                        $is_public = $this->_db->get('profile_fields', array('id', '=', $result->field_id));
                        if (!$is_public->count()) continue;
                        else $is_public = $is_public->results();

                        if ($is_public[0]->public == 1) {
                            if ($forum == true) {
                                if ($is_public[0]->forum_posts == 1) {
                                    $return[] = array(
                                        'name' => Output::getClean($is_public[0]->name),
                                        'value' => Output::getClean($result->value)
                                    );
                                }
                            } else {
                                $return[] = array(
                                    'name' => Output::getClean($is_public[0]->name),
                                    'value' => Output::getClean($result->value)
                                );
                            }
                        }
                    }

                    return $return;
                } else {
                    // Return all fields
                    $return = array();
                    foreach ($data->results() as $result) {
                        $name = $this->_db->get('profile_fields', array('id', '=', $result->field_id));
                        if (!$name->count()) continue;
                        else $name = $name->results();

                        $return[] = array(
                            'name' => Output::getClean($name[0]->name),
                            'value' => Output::getClean($result->value)
                        );
                    }

                    return $return;
                }
            } else return false;
        }
        return false;
    }

    /**
     * Is a user blocked?
     *
     * @param int $user ID of first user
     * @param int $blocked ID of user who may or may not be blocked
     * @return bool Whether they are blocked or not.
     */
    public function isBlocked($user, $blocked) {
        if ($user && $blocked) {
            $possible_users = $this->_db->get('blocked_users', array('user_id', '=', $user));
            if ($possible_users->count()) {
                $possible_users = $possible_users->results();

                foreach ($possible_users as $possible_user) {
                    if ($possible_user->user_blocked_id == $blocked) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Does the user have a given permission in any of their groups?
     *
     * @param string $permission Permission node to check recursively for.
     * @return bool Whether they inherit this permission or not.
     */
    public function hasPermission($permission) {
        $groups = $this->_groups;
        if ($this->isLoggedIn() && $groups) {
            foreach ($groups as $group) {
                $this->_permissions = json_decode($group->permissions, true);
                
                if (isset($this->_permissions['administrator']) && $this->_permissions['administrator'] == 1) {
                    return true;
                }

                if (isset($this->_permissions[$permission]) && $this->_permissions[$permission] == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get this user's profile views.
     *
     * @return int Numer of profile views they have
     */
    public function getProfileViews() {
        if (count($this->data())) {
            return $this->data()->profile_views;
        }

        return 0;
    }

    /**
     * Is private profile enabled and does he have the permission to use it?
     *
     * @return bool Whether profile privatizing is allowed and if they have permission to use it.
     */
    public function canPrivateProfile() {
        $settings_data = $this->_db->get('settings', array('name', '=', 'private_profile'));
        $settings_results = $settings_data->results();

        return (($settings_results[0]->value == 1) && ($this->hasPermission('usercp.private_profile')));
    }

    /**
     * Is the profile page set to private?
     *
     * @return bool Whether their profile is set to private or not.
     */
    public function isPrivateProfile() {
        return $this->_data->private_profile;
    }

    /**
     * Get templates this user's group has access to.
     *
     * @return object Templates which the user has access to.
     */
    public function getUserTemplates() {
        $groups = '(';
        foreach ($this->_groups as $group) {
            if (is_numeric($group->id)) {
                $groups .= ((int) $group->id) . ',';
            }
        }
        $groups = rtrim($groups, ',') . ')';

        return $this->_db->query('SELECT template.id, template.name FROM nl2_templates AS template WHERE template.enabled = 1 AND template.id IN (SELECT template_id FROM nl2_groups_templates WHERE can_use_template = 1 AND group_id IN ' . $groups . ')')->results();
    }

    /**
     * Save/update this users placeholders.
     * 
     * @param int $server_id Server ID from staffcp -> integrations to assoc these placeholders with.
     * @param array $placeholders Key/value array of placeholders name/value from API endpoint.
     */
    public function savePlaceholders($server_id, $placeholders) {
        foreach ($placeholders as $name => $value) {
            Placeholders::getInstance()->registerPlaceholder($server_id, $name);

            $last_updated = time();
            $uuid = hex2bin(str_replace('-', '', $this->data()->uuid));

            $this->_db->createQuery('INSERT INTO nl2_users_placeholders (server_id, uuid, name, value, last_updated) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE value = ?, last_updated = ?', [
                $server_id,
                $uuid,
                $name,
                $value,
                $last_updated,
                $value,
                $last_updated
            ]);
        }
    }
}
