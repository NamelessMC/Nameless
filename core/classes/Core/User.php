<?php

/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  User class
 */

class User {

    private DB $_db;

    private $_data;
    private array $_groups = [];
    private array $_placeholders;
    private string $_sessionName;
    private string $_cookieName;
    private bool $_isLoggedIn = false;
    private string $_admSessionName;
    private bool $_isAdmLoggedIn = false;

    public function __construct(string $user = null, string $field = 'id') {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        $this->_admSessionName = Config::get('session/admin_name');

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
     * Find a user by unique identifier (username, ID, email, etc).
     * Loads instance variables for this class.
     *
     * @param string|null $value Unique identifier.
     * @param string $field What column to check for their unique identifier in.
     *
     * @return bool True/false on success or failure respectfully.
     */
    public function find(string $value = null, string $field = 'id'): bool {
        if ($value) {
            $data = $this->_db->get('users', [$field, '=', $value]);

            if ($data->count()) {
                $this->_data = $data->first();

                // Get user groups
                $groups_query = $this->_db->selectQuery('SELECT nl2_groups.* FROM nl2_users_groups INNER JOIN nl2_groups ON group_id = nl2_groups.id WHERE user_id = ? AND deleted = 0 ORDER BY `order`;', [$this->_data->id]);

                if ($groups_query->count()) {

                    $groups_query = $groups_query->results();
                    foreach ($groups_query as $item) {
                        $this->_groups[$item->id] = $item;
                    }

                } else {
                    // Get default group
                    // TODO: Use PRE_VALIDATED_DEFAULT ?
                    $default_group = $this->_db->selectQuery('SELECT * FROM nl2_groups WHERE default_group = 1', [])->first();
                    if ($default_group) {
                        $default_group_id = $default_group->id;
                    } else {
                        $default_group_id = 1; // default to 1
                        $default_group = $this->_db->selectQuery('SELECT * FROM nl2_groups WHERE id = 1', [])->first();
                    }

                    $this->addGroup($default_group_id, 0, $default_group);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Add a group to this user.
     *
     * @param int $group_id ID of group to give.
     * @param int $expire Expiry in epoch time. If not supplied, group will never expire.
     * @param object|null $group_data Load data from existing query.
     *
     * @return bool True on success, false if they already have it.
     */
    public function addGroup(int $group_id, int $expire = 0, $group_data = null): bool {
        if (array_key_exists($group_id, $this->_groups)) {
            return false;
        }

        $this->_db->createQuery(
            'INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)',
            [
                $this->data()->id,
                $group_id,
                date('U'),
                $expire
            ]
        );

        if ($group_data == null) {
            $group_data = $this->_db->get('groups', ['id', '=', $group_id]);
            if ($group_data->count()) {
                $this->_groups[$group_id] = $group_data->first();
            }
        } else {
            $this->_groups[$group_id] = $group_data;
        }

        return true;
    }

    /**
     * Get the currently logged in user's data.
     *
     * @return object This user's data.
     */
    public function data(): ?object {
        return $this->_data;
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
    public function getIP(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Update a user's data in the database.
     *
     * @param array $fields Column names and values to update.
     * @param int|null $id If not supplied, will use ID of logged in user.
     * @throws Exception
     */
    public function update(array $fields = [], int $id = null): void {
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
    public function create(array $fields = []): void {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    /**
     * Get a user's username from their ID.
     *
     * @param int|null $id Their ID.
     *
     * @return string|bool Their username, false on failure.
     */
    public function idToName(int $id = null) {
        if ($id) {
            $data = $this->_db->get('users', ['id', '=', $id]);

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
     * @param int|null $id Their ID.
     *
     * @return string|bool Their nickname, false on failure.
     */
    public function idToNickname(int $id = null) {
        if ($id) {
            $data = $this->_db->get('users', ['id', '=', $id]);

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
     * @param string|null $username Their username (or email, depending on $method).
     * @param string|null $password Their password.
     * @param bool $remember Whether to keep them logged in or not.
     * @param string $method What column to check for their details in. Can be either `username` or `email`.
     *
     * @return bool True/false on success or failure respectfully.
     */
    public function login(?string $username = null, ?string $password = null, bool $remember = false, string $method = 'email'): bool {
        return $this->_commonLogin($username, $password, $remember, $method, false);
    }

    private function _commonLogin(?string $username, ?string $password, bool $remember, string $method, bool $is_admin): bool {
        $sessionName = $is_admin ? $this->_admSessionName : $this->_sessionName;
        if (!$username && !$password && $this->exists()) {
            Session::put($sessionName, $this->data()->id);
            if (!$is_admin) {
                $this->_isLoggedIn = true;
            }
        } else {
            if ($this->checkCredentials($username, $password, $method) === true) {
                // Valid credentials
                Session::put($sessionName, $this->data()->id);

                if ($remember) {
                    $hash = Hash::unique();
                    $table = $is_admin ? 'users_admin_session' : 'users_session';
                    $hashCheck = $this->_db->get($table, ['user_id', '=', $this->data()->id]);

                    if (!$hashCheck->count()) {
                        $this->_db->insert($table, [
                            'user_id' => $this->data()->id,
                            'hash' => $hash
                        ]);
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }

                    $expiry = $is_admin ? 3600 : Config::get('remember/cookie_expiry');
                    $cookieName = $is_admin ? ($this->_cookieName . '_adm') : $this->_cookieName;
                    Cookie::put($cookieName, $hash, $expiry, Util::isConnectionSSL(), true);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Does this user exist?
     *
     * @return bool Whether the user exists (has data) or not.
     */
    public function exists(): bool {
        return (!empty($this->_data));
    }

    /**
     * Check whether given credentials are valid.
     *
     * @param string $username Username (or email) to check.
     * @param string $password Password entered by user.
     * @param string $method Column to search for user with. Can be `email` or `username`.
     *
     * @return bool True if correct, false otherwise.
     */
    public function checkCredentials(string $username, string $password, string $method = 'email'): bool {
        $user = $this->find($username, $method);
        if ($user) {
            switch ($this->data()->pass_method) {
                case 'wordpress':
                    // phpass
                    $phpass = new PasswordHash(8, false);

                    return ($phpass->checkPassword($password, $this->data()->password));

                case 'sha256':
                    $exploded = explode('$', $this->data()->password);

                    $salt = $exploded[0];
                    $pass = $exploded[1];

                    return ($salt . hash('sha256', hash('sha256', $password) . $salt) == $salt . $pass);

                case 'pbkdf2':
                    $exploded = explode('$', $this->data()->password);

                    $iterations = $exploded[0];
                    $salt = $exploded[1];
                    $pass = $exploded[2];

                    $hashed = hash_pbkdf2('sha256', $password, $salt, $iterations, 64, true);

                    return ($hashed == hex2bin($pass));

                case 'modernbb':
                case 'sha1':
                    return (sha1($password) == $this->data()->password);

                default:
                    // Default to bcrypt
                    return (password_verify($password, $this->data()->password));
            }
        }

        return false;
    }

    /**
     * Handle StaffCP logins.
     *
     * @param string|null $username Their username (or email, depending on $method).
     * @param string|null $password Their password.
     * @param string $method What column to check for their details in. Can be either `username` or `email`.
     *
     * @return bool True/false on success or failure respectfully.
     */
    public function adminLogin(?string $username = null, ?string $password = null, string $method = 'email'): bool {
        return $this->_commonLogin($username, $password, true, $method, true);
    }

    /**
     * Get user's display name.
     *
     * @param bool $username If true, will use their username. If false, will use their nickname.
     * @return string Their display name.
     */
    public function getDisplayName(bool $username = false): string {
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
    public function getProfileURL(): string {
        return Output::getClean(URL::build('/profile/' . $this->data()->username));
    }

    /**
     * @deprecated Use specific group HTML or group IDs methods instead
     */
    public function getAllGroups($html = null): array {
        if (is_null($html)) {
            return $this->getAllGroupIds();
        }

        return $this->getAllGroupHtml();
    }

    /**
     * Get all of a user's groups id.
     *
     * @param bool $login_check If true, will first check if this user is logged in or not. Set to "false" for API usage.
     *
     * @return array Array of all their group IDs.
     */
    public function getAllGroupIds(bool $login_check = true): array {
        if ($login_check) {
            if (!$this->isLoggedIn()) {
                return [0];
            }
        }

        $groups = [];

        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                $groups[$group->id] = $group->id;
            }
        }

        return $groups;
    }

    /**
     * Get if this user is currently logged in or not.
     *
     * @return bool Whether they're logged in.
     */
    public function isLoggedIn(): bool {
        return $this->_isLoggedIn;
    }

    /**
     * Get all of a user's groups. We can return their ID only or their HTML display code.
     *
     * @return array Array of all their group's IDs or HTML.
     */
    public function getAllGroupHtml(): array {
        $groups = [];

        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                $groups[] = $group->group_html;
            }
        }

        return $groups;
    }

    /**
     * Get this user's signature.
     *
     * @return string Their signature.
     */
    public function getSignature(): string {
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
     *
     * @return string URL to their avatar image.
     */
    public function getAvatar(int $size = 128, bool $full = false): string {

        // If custom avatars are enabled, first check if they have gravatar enabled, and then fallback to normal image
        if (defined('CUSTOM_AVATARS')) {

            if ($this->data()->gravatar) {
                return 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($this->data()->email))) . '?s=' . $size;
            }

            if ($this->data()->has_avatar) {
                $exts = ['png', 'jpg', 'jpeg'];

                if ($this->hasPermission('usercp.gif_avatar')) {
                    $exts[] = 'gif';
                }

                foreach ($exts as $ext) {
                    if (file_exists(ROOT_PATH . '/uploads/avatars/' . $this->data()->id . '.' . $ext)) {
                        return ($full ? rtrim(Util::getSelfURL(), '/') : '') . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars/' . $this->data()->id . '.' . $ext . '?v=' . Output::getClean($this->data()->avatar_updated);
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
            if (preg_match('#[^][_A-Za-z0-9]#', $uuid)) {
                $uuid = 'Steve';
            }
        }

        return AvatarSource::getAvatarFromUUID($uuid, $size);
    }

    /**
     * Does the user have a given permission in any of their groups?
     *
     * @param string $permission Permission node to check recursively for.
     *
     * @return bool Whether they inherit this permission or not.
     */
    public function hasPermission(string $permission): bool {
        $groups = $this->_groups;
        if ($this->isLoggedIn() && $groups) {
            foreach ($groups as $group) {
                $permissions = json_decode($group->permissions, true);

                if (isset($permissions['administrator']) && $permissions['administrator'] == 1) {
                    return true;
                }

                if (isset($permissions[$permission]) && $permissions[$permission] == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * If the user has infractions, list them all. Or else return false.
     * Not used internally.
     *
     * @return array|bool Array of infractions if they have one or more, else false.
     */
    public function hasInfraction() {
        $data = $this->_db->get('infractions', ['punished', '=', $this->data()->id])->results();
        if (empty($data)) {
            return false;
        }

        $return = [];
        $n = 0;
        foreach ($data as $infraction) {
            if ($infraction->acknowledged == '0') {
                $return[$n]['id'] = $infraction->id;
                $return[$n]['staff'] = $infraction->staff;
                $return[$n]['reason'] = $infraction->reason;
                $return[$n]['date'] = $infraction->infraction_date;
                $n++;
            }
        }

        return $return;
    }

    /**
     * Log the user out.
     * Deletes their cookies, sessions and database session entry.
     */
    public function logout(): void {

        $this->_db->delete('users_session', ['user_id', '=', $this->data()->id]);

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    /**
     * Process logout if user is admin
     */
    public function admLogout(): void {

        $this->_db->delete('users_admin_session', ['user_id', '=', $this->data()->id]);

        Session::delete($this->_admSessionName);
        Cookie::delete($this->_cookieName . '_adm');
    }

    /**
     * Get the currently logged in user's groups.
     *
     * @return array Their groups.
     */
    public function getGroups(): array {
        return $this->_groups;
    }

    /**
     * Get this user's placeholders to display on their profile.
     *
     * @return array Profile placeholders.
     */
    public function getProfilePlaceholders(): array {
        return array_filter($this->getPlaceholders(), static function ($placeholder) {
            return $placeholder->show_on_profile;
        });
    }

    /**
     * Get the currently logged in user's placeholders.
     *
     * @return array Their placeholders.
     */
    public function getPlaceholders(): array {
        return $this->_placeholders ??= (function (): array {
            if ($this->_data->uuid != null && $this->_data->uuid != 'none') {
                return Placeholders::getInstance()->loadUserPlaceholders($this->_data->uuid);
            }

            return [];
        })();
    }

    /**
     * Get this user's placeholders to display on their forum posts.
     *
     * @return array Forum placeholders.
     */
    public function getForumPlaceholders(): array {
        return array_filter($this->getPlaceholders(), static function ($placeholder) {
            return $placeholder->show_on_forum;
        });
    }

    /**
     * Get this user's main group (with highest order).
     *
     * @return object|null The group
     */
    public function getMainGroup(): ?object {
        if (count($this->_groups)) {
            foreach ($this->_groups as $group) {
                return $group;
            }
        }

        return null;
    }

    /**
     * Set a group to user and remove all other groups
     *
     * @param int $group_id ID of group to set as main group.
     * @param int $expire Expiry in epoch time. If not supplied, group will never expire.
     * @param array|null $group_data Load data from existing query.
     * @return false|void
     */
    public function setGroup(int $group_id, int $expire = 0, array $group_data = null) {
        if ($this->data()->id == 1) {
            return false;
        }
        $this->_db->createQuery('DELETE FROM `nl2_users_groups` WHERE `user_id` = ?', [$this->data()->id]);

        $this->_db->createQuery(
            'INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)',
            [
                $this->data()->id,
                $group_id,
                date('U'),
                $expire
            ]
        );

        $this->_groups = [];
        if ($group_data == null) {
            $group_data = $this->_db->get('groups', ['id', '=', $group_id]);
            if ($group_data->count()) {
                $this->_groups[$group_id] = $group_data->first();
            }
        } else {
            $this->_groups[$group_id] = $group_data;
        }
    }

    /**
     * Remove a group from the user.
     *
     * @param int|null $group_id ID of group to remove.
     *
     * @return bool Returns false if they did not have this group or the admin group is being removed from root user
     */
    public function removeGroup(?int $group_id): bool {
        if (!array_key_exists($group_id, $this->_groups)) {
            return false;
        }

        if ($group_id == 2 && $this->data()->id == 1) {
            return false;
        }

        $this->_db->createQuery(
            'DELETE FROM `nl2_users_groups` WHERE `user_id` = ? AND `group_id` = ?',
            [
                $this->data()->id,
                $group_id
            ]
        );

        unset($this->_groups[$group_id]);

        return true;
    }

    /**
     * Get if this user is active/validated or not.
     *
     * @return bool Whether this user has been validated/activated.
     */
    public function isValidated(): bool {
        return $this->data()->active;
    }

    /**
     * Get a comma separated string of all users.
     * For the new private message dropdown.
     *
     * @return string CSV list of user's usernames.
     */
    public function listAllUsers(): string {
        $data = $this->_db->get('users', ['id', '<>', '0'])->results();
        $return = '';

        foreach ($data as $item) {
            $return .= '"' . $item->username . '",';
        }

        return rtrim($return, ',');
    }

    /**
     * Return an ID from a username.
     *
     * @param string|null $username Username to get ID for.
     *
     * @return int|bool ID on success, false on failure.
     */
    public function nameToId(string $username = null) {
        if ($username) {
            $data = $this->_db->get('users', ['username', '=', $username]);

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
     * @param string|null $email Email to get ID for.
     * @return int|bool ID on success, false on failure.
     */
    public function emailToId(string $email = null) {
        if ($email) {
            $data = $this->_db->get('users', ['email', '=', $email]);

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
     * @param int|null $user_id ID of user to get PMs for.
     * @return array|bool Array of PMs, false on failure.
     */
    public function listPMs(int $user_id = null) {
        if ($user_id) {
            $return = []; // Array to return containing info of PMs

            // Get a list of PMs which the user is in
            $data = $this->_db->get('private_messages_users', ['user_id', '=', $user_id]);

            if ($data->count()) {
                $data = $data->results();
                foreach ($data as $result) {
                    // Get a list of users who are in this conversation and return them as an array
                    $pms = $this->_db->get('private_messages_users', ['pm_id', '=', $result->pm_id])->results();
                    $users = []; // Array containing users with permission
                    foreach ($pms as $pm) {
                        $users[] = $pm->user_id;
                    }

                    // Get the PM data
                    $pm = $this->_db->get('private_messages', ['id', '=', $result->pm_id])->results();
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
     * @param int|null $pm_id ID of PM to find.
     * @param int|null $user_id ID of user to check permission for.
     * @return array|bool Array of info about PM, false on failure.
     */
    public function getPM(int $pm_id = null, int $user_id = null) {
        if ($user_id && $pm_id) {
            // Get the PM - is the user the author?
            $data = $this->_db->get('private_messages', ['id', '=', $pm_id]);
            if ($data->count()) {
                $data = $data->results();
                $data = $data[0];

                // Does the user have permission to view the PM?
                $pms = $this->_db->get('private_messages_users', ['pm_id', '=', $pm_id])->results();
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
                    $this->_db->update('private_messages_users', $pm_user_id, [
                        '`read`' => 1
                    ]);
                }

                // User has permission, return the PM information

                // Get a list of users in the conversation
                if (!isset($pms)) {
                    $pms = $this->_db->get('private_messages_users', ['pm_id', '=', $pm_id])->results();
                }

                $users = []; // Array to store users
                foreach ($pms as $pm) {
                    $users[] = $pm->user_id;
                }

                return [$data, $users];
            }
        }

        return false;
    }

    /**
     * Check the user's permission to see if they can view this staffCP page or not.
     * If they cannot, this will handle appropriate redirection.
     *
     * @param string|null $permission Permission required for this page.
     * @return bool
     */
    public function handlePanelPageLoad(string $permission = null): bool {
        // Set page user is trying to access in session, to allow for redirection post-auth
        if (FRIENDLY_URLS === true) {
            $split = explode('?', $_SERVER['REQUEST_URI']);

            if ($split != null && count($split) > 1) {
                $_SESSION['last_page'] = URL::build($split[0], $split[1]);
            } else {
                $_SESSION['last_page'] = URL::build($split[0]);
            }
        } else {
            $_SESSION['last_page'] = URL::build($_GET['route']);
        }

        if (defined('CONFIG_PATH')) {
            $_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));
        }

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
     * Can the specified user view StaffCP?
     *
     * @return bool Whether they can view it or not.
     */
    public function canViewStaffCP(): bool {
        if (isset($this->_groups) && count($this->_groups)) {
            foreach ($this->_groups as $group) {
                if ($group->admin_cp == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get if the current user is authenticated as an administrator.
     *
     * @return bool Whether they're logged in as admin.
     */
    public function isAdmLoggedIn(): bool {
        return $this->_isAdmLoggedIn;
    }

    /**
     * Get profile fields for specified user
     *
     * @param int $user_id User to retrieve fields for.
     * @param bool $public Whether to only return public fields or not (default `true`).
     * @param bool $forum Whether to only return fields which display on forum posts, only if $public is true (default `false`).
     *
     * @return array Array of profile fields.
     */
    public function getProfileFields(int $user_id, bool $public = true, bool $forum = false): array {
        if ($user_id == null) {
            throw new InvalidArgumentException('User id is null');
        }

        $data = $this->_db->get('users_profile_fields', ['user_id', '=', $user_id]);

        if (!$data->count()) {
            return [];
        }

        $return = [];
        if ($public == true) {
            // Return public fields only
            foreach ($data->results() as $result) {
                $is_public = $this->_db->get('profile_fields', ['id', '=', $result->field_id]);
                if (!$is_public->count()) {
                    continue;
                } else {
                    $is_public = $is_public->results();
                }

                if ($is_public[0]->public == 1) {
                    if ($forum == true) {
                        if ($is_public[0]->forum_posts == 1) {
                            $return[] = [
                                'name' => Output::getClean($is_public[0]->name),
                                'value' => Output::getClean($result->value)
                            ];
                        }
                    } else {
                        $return[] = [
                            'name' => Output::getClean($is_public[0]->name),
                            'value' => Output::getClean($result->value)
                        ];
                    }
                }
            }

        } else {
            // Return all fields
            foreach ($data->results() as $result) {
                $name = $this->_db->get('profile_fields', ['id', '=', $result->field_id]);
                if (!$name->count()) {
                    continue;
                } else {
                    $name = $name->results();
                }

                $return[] = [
                    'name' => Output::getClean($name[0]->name),
                    'value' => Output::getClean($result->value)
                ];
            }

        }
        return $return;
    }

    /**
     * Is a user blocked?
     *
     * @param int $user ID of first user
     * @param int $blocked ID of user who may or may not be blocked
     *
     * @return bool Whether they are blocked or not.
     */
    public function isBlocked(int $user, int $blocked): bool {
        if ($user && $blocked) {
            $possible_users = $this->_db->get('blocked_users', ['user_id', '=', $user]);
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
     * Get this user's profile views.
     *
     * @return int Numer of profile views they have
     */
    public function getProfileViews(): int {
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
    public function canPrivateProfile(): bool {
        $settings_data = $this->_db->get('settings', ['name', '=', 'private_profile']);
        $settings_results = $settings_data->results();

        return (($settings_results[0]->value == 1) && ($this->hasPermission('usercp.private_profile')));
    }

    /**
     * Is the profile page set to private?
     *
     * @return bool Whether their profile is set to private or not.
     */
    public function isPrivateProfile(): bool {
        return $this->_data->private_profile ?? false;
    }

    /**
     * Get templates this user's group has access to.
     *
     * @return array Templates which the user has access to.
     */
    public function getUserTemplates(): array {
        $groups = '(';
        foreach ($this->_groups as $group) {
            if (is_numeric($group->id)) {
                $groups .= ((int)$group->id) . ',';
            }
        }
        $groups = rtrim($groups, ',') . ')';

        return $this->_db->selectQuery('SELECT template.id, template.name FROM nl2_templates AS template WHERE template.enabled = 1 AND template.id IN (SELECT template_id FROM nl2_groups_templates WHERE can_use_template = 1 AND group_id IN ' . $groups . ')')->results();
    }

    /**
     * Save/update this users placeholders.
     *
     * @param int $server_id Server ID from staffcp -> integrations to assoc these placeholders with.
     * @param array $placeholders Key/value array of placeholders name/value from API endpoint.
     */
    public function savePlaceholders(int $server_id, array $placeholders): void {
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
