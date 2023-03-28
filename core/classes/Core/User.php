<?php
/**
 * Represents a user, logged in or not.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @author Partydragen
 * @author Aberdeener
 * @version 2.0.2
 * @license MIT
 */
class User {

    private static array $_user_cache = [];
    private static array $_group_cache = [];
    private static array $_integration_cache = [];

    private DB $_db;

    /**
     * @var UserData|null The user's data. Basically just the row from `nl2_users` where the user ID is the key.
     */
    private ?UserData $_data;

    /**
     * @var array<int, Group> The user's groups.
     */
    private array $_groups;

    /**
     * @var IntegrationUser[] The user's integrations.
     */
    private array $_integrations;

    /**
     * @var Group The user's main group.
     */
    private Group $_main_group;

    /**
     * @var array The user's placeholders.
     */
    private array $_placeholders;

    /**
     * @var string The session name configuration value for remembering the user.
     */
    private string $_sessionName;

    /**
     * @var string The cookie name configuration value.
     */
    private string $_cookieName;

    /**
     * @var bool Whether this user is logged in or not.
     */
    private bool $_isLoggedIn = false;

    /**
     * @var string The session name configuration value for remembering the admin user.
     */
    private string $_admSessionName;

    /**
     * @var bool Whether this user is logged in as an admin or not.
     */
    private bool $_isAdmLoggedIn = false;

    public function __construct(string $user = null, string $field = 'id') {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session.session_name');
        $this->_cookieName = Config::get('remember.cookie_name');
        $this->_admSessionName = Config::get('session.admin_name');

        if ($user === null) {
            if (Session::exists($this->_sessionName)) {
                $hash = Session::get($this->_sessionName);
                if ($this->find($hash, 'hash')) {
                    $this->_isLoggedIn = true;
                }
            }
            if (Session::exists($this->_admSessionName)) {
                $hash = Session::get($this->_admSessionName);
                if ($this->find($hash, 'hash')) {
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
     * @param string $value Unique identifier.
     * @param string $field What column to check for their unique identifier in.
     *
     * @return bool True/false on success or failure respectfully.
     */
    private function find(string $value, string $field = 'id'): bool {
        if (isset(self::$_user_cache["$value.$field"])) {
            $this->_data = self::$_user_cache["$value.$field"];
            return true;
        }

        if ($field !== 'hash') {
            $data = $this->_db->get('users', [$field, $value]);
        } else {
            $data = $this->_db->query('SELECT nl2_users.* FROM nl2_users LEFT JOIN nl2_users_session ON nl2_users.id = user_id WHERE hash = ? AND nl2_users_session.active = 1', [$value]);
        }

        if ($data->count()) {
            $this->_data = new UserData($data->first());
            self::$_user_cache["$value.$field"] = $this->_data;
            return true;
        }

        return false;
    }

    /**
     * Add a group to this user.
     *
     * @param int $group_id ID of group to give.
     * @param int $expire Expiry in epoch time. If not supplied, group will never expire.
     *
     * @return bool True on success, false if they already have it.
     */
    public function addGroup(int $group_id, int $expire = 0): bool {
        if (array_key_exists($group_id, $this->_groups ?? [])) {
            return false;
        }

        $this->_db->query('INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)', [
            $this->data()->id,
            $group_id,
            date('U'),
            $expire
        ]);

        $group = Group::find($group_id);
        if ($group) {
            $this->_groups[$group_id] = $group;
        }

        EventHandler::executeEvent(new UserGroupAddedEvent(
            $this,
            $this->_groups[$group_id],
        ));

        return true;
    }

    /**
     * Get the user's data.
     *
     * @return UserData This user's data.
     */
    public function data(): ?UserData {
        return $this->_data ?? null;
    }

    /**
     * Get this user's main group CSS styling
     *
     * @return string The CSS styling.
     */
    public function getGroupStyle(): string {
        $group = $this->getMainGroup();

        $group_username_color = Output::getClean($group->group_username_color);
        $group_username_css = Output::getClean($group->group_username_css);

        $css = '';
        if ($group_username_color) {
            $css .= "color: $group_username_color;";
        }
        if ($group_username_css) {
            $css .= $group_username_css;
        }

        return $css;
    }

    /**
     * Update a user's data in the database.
     *
     * @param array $fields Column names and values to update.
     * @throws Exception
     */
    public function update(array $fields = []): void {
        if (!$this->_db->update('users', $this->data()->id, $fields)) {
            throw new RuntimeException('There was a problem updating your details.');
        }
    }

    /**
     * Create a new user.
     *
     * @param array $fields Column names and values to insert to database.
     */
    public function create(array $fields = []): void {
        if (!$this->_db->insert('users', $fields)) {
            throw new RuntimeException('There was a problem creating an account.');
        }
    }

    /**
     * Get a user's username from their ID.
     *
     * @param int $id Their ID.
     *
     * @return ?string Their username, null on failure.
     */
    public function idToName(int $id): ?string {
        $data = $this->_db->get('users', ['id', $id]);

        if ($data->count()) {
            return $data->first()->username;
        }

        return null;
    }

    /**
     * Get a user's nickname from their ID.
     *
     * @param int $id Their ID.
     *
     * @return ?string Their nickname, null on failure.
     */
    public function idToNickname(int $id): ?string {
        $data = $this->_db->get('users', ['id', $id]);

        if ($data->count()) {
            return $data->first()->nickname;
        }

        return null;
    }

    /**
     * Log the user in.
     *
     * @param string|null $username Their username (or email, depending on $method).
     * @param string|null $password Their password.
     * @param bool $remember Whether to keep them logged in or not.
     * @param string $method What column to check for their details in. Can be either `username` or `email` or `oauth`.
     *
     * @return bool True/false on success or failure respectfully.
     */
    public function login(?string $username = null, ?string $password = null, bool $remember = false, string $method = 'email'): bool {
        return $this->_commonLogin($username, $password, $remember, $method, false);
    }

    private function _commonLogin(?string $username, ?string $password, bool $remember, string $method, bool $is_admin): bool {
        $sessionName = $is_admin ? $this->_admSessionName : $this->_sessionName;
        if (!$username && $method == 'hash' && $this->exists()) {
            // Logged in using hash from cookie
            Session::put($sessionName, $password);
            if (!$is_admin) {
                $this->_isLoggedIn = true;
            }
        } else if ($this->checkCredentials($username, $password, $method) === true) {
            // Valid credentials
            $hash = SecureRandom::alphanumeric();

            $this->_db->insert('users_session', [
                'user_id' => $this->data()->id,
                'hash' => $hash,
                'remember_me' => $remember,
                'active' => 1,
                'login_method' => $is_admin ? 'admin' : $method
            ]);

            Session::put($sessionName, $hash);

            if ($remember) {
                $expiry = $is_admin ? 3600 : Config::get('remember.cookie_expiry');
                $cookieName = $is_admin ? ($this->_cookieName . '_adm') : $this->_cookieName;
                Cookie::put($cookieName, $hash, $expiry, HttpUtils::getProtocol() === 'https', true);
            }

            return true;
        }

        return false;
    }

    /**
     * Does this user exist?
     *
     * @return bool Whether the user exists (has data) or not.
     */
    public function exists(): bool {
        return !empty($this->_data);
    }

    /**
     * Check whether given credentials are valid.
     *
     * @param string $username Username (or email) to check.
     * @param string $password Password entered by user.
     * @param string $method Column to search for user with. Can be `email` or `username` or `oauth`. If it is `oauth`, then the request will be granted.
     *
     * @return bool True if correct, false otherwise.
     */
    public function checkCredentials(string $username, string $password, string $method = 'email'): bool {
        $user = $this->find($username, $method === 'oauth' ? 'id' : $method);

        if ($method === 'oauth') {
            return true;
        }

        if ($user) {
            switch ($this->data()->pass_method) {
                case 'sha256':
                    [$salt, $pass] = explode('$', $this->data()->password);
                    return $salt . hash('sha256', hash('sha256', $password) . $salt) == $salt . $pass;

                case 'pbkdf2':
                    [$iterations, $salt, $pass] = explode('$', $this->data()->password);
                    $hashed = hash_pbkdf2('sha256', $password, $salt, $iterations, 64, true);
                    return $hashed == hex2bin($pass);

                case 'modernbb':
                case 'sha1':
                    return sha1($password) == $this->data()->password;

                default:
                    // Default to bcrypt
                    return password_verify($password, $this->data()->password);
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
    public function getDisplayname(bool $username = false): string {
        if ($username) {
            return Output::getClean($this->data()->username);
        }

        return Output::getClean($this->data()->nickname);
    }

    /**
     * Build this user's profile link.
     *
     * @return string Compiled profile URL.
     */
    public function getProfileURL(): string {
        return URL::build('/profile/' . urlencode($this->data()->username));
    }

    /**
     * Get all of a user's groups id. Logged out/non-existent users will return just `0`.
     *
     * @return array Array of all their group IDs.
     */
    public function getAllGroupIds(): array {
        if (!$this->exists()) {
            return [0 => 0];
        }

        $groups = [];
        foreach ($this->getGroups() as $group) {
            $groups[$group->id] = $group->id;
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
     * Get all of a user's group HTML display code.
     *
     * @return array Array of all their groups HTML.
     */
    public function getAllGroupHtml(): array {
        $groups = [];

        foreach ($this->getGroups() as $group) {
            $groups[] = $group->group_html;
        }

        return $groups;
    }

    /**
     * Get this user's signature.
     *
     * @return string Their signature.
     */
    public function getSignature(): string {
        return $this->data()->signature ?? '';
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
        $data_obj = new stdClass();
        // Convert UserData object to stdClass so we can dynamically add the 'uuid' property
        foreach (get_object_vars($this->data()) as $key => $value) {
            $data_obj->{$key} = $value;
        }

        $integrationUser = $this->getIntegration('Minecraft');
        if ($integrationUser != null) {
            $data_obj->uuid = $integrationUser->data()->identifier;
        } else {
            $data_obj->uuid = '';
        }

        return AvatarSource::getAvatarFromUserData($data_obj, $this->hasPermission('usercp.gif_avatar'), $size, $full);
    }

    /**
     * Does the user have a specific permission in any of their groups?
     *
     * @param string $permission Permission node to check recursively for.
     *
     * @return bool Whether they inherit this permission or not.
     */
    public function hasPermission(string $permission): bool {
        if (!$this->exists()) {
            return false;
        }

        foreach ($this->getGroups() as $group) {
            $permissions = json_decode($group->permissions, true) ?? [];

            if (isset($permissions['administrator']) && $permissions['administrator'] == 1) {
                return true;
            }

            if (isset($permissions[$permission]) && $permissions[$permission] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the user out from all other sessions.
     */
    public function logoutAllOtherSessions(): void {
        DB::getInstance()->query('UPDATE nl2_users_session SET `active` = 0 WHERE user_id = ? AND hash != ?', [
            $this->data()->id,
            Session::get(Config::get('session.session_name'))
        ]);
    }

    /**
     * Log the user out.
     * Deletes their cookies, sessions and database session entry.
     */
    public function logout(): void {
        $this->_db->update('users_session', [['user_id', $this->data()->id], ['hash', Session::get($this->_sessionName)]], [
            'active' => 0
        ]);

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    /**
     * Process logout if user is admin
     */
    public function admLogout(): void {
        $this->_db->update('users_session', [['user_id', $this->data()->id], ['hash', Session::get($this->_admSessionName)]], [
            'active' => 0
        ]);

        Session::delete($this->_admSessionName);
        Cookie::delete($this->_cookieName . '_adm');
    }

    /**
     * Get the user's groups.
     *
     * @return array<int, Group> Their groups.
     */
    public function getGroups(): array {
        if (isset($this->_groups)) {
            return $this->_groups;
        }

        if (isset(self::$_group_cache[$this->data()->id])) {
            $groups_query = self::$_group_cache[$this->data()->id];
        } else {
            $groups_query = $this->_db->query('SELECT nl2_groups.* FROM nl2_users_groups INNER JOIN nl2_groups ON group_id = nl2_groups.id WHERE user_id = ? AND deleted = 0 ORDER BY `order`', [$this->data()->id]);
            if ($groups_query->count()) {
                $groups_query = $groups_query->results();
            } else {
                $groups_query = [];
            }
            self::$_group_cache[$this->data()->id] = $groups_query;
        }

        if ($groups_query) {
            foreach ($groups_query as $item) {
                $this->_groups[$item->id] = new Group($item);
            }
        } else {
            // Get default group
            // TODO: Use PRE_VALIDATED_DEFAULT ?
            $default_group = Group::find(1, 'default_group');
            $default_group_id = $default_group->id ?? 1;

            $this->addGroup($default_group_id);
        }

        return $this->_groups;
    }

    /**
     * Get the user's integrations.
     *
     * @return IntegrationUser[] Their integrations.
     */
    public function getIntegrations(): array {
        if (isset($this->_integrations)) {
            return $this->_integrations;
        }

        $integrations = Integrations::getInstance();

        if (isset(self::$_integration_cache[$this->data()->id])) {
            $integrations_query = self::$_integration_cache[$this->data()->id];
        } else {
            $integrations_query = $this->_db->query('SELECT nl2_users_integrations.*, nl2_integrations.name as integration_name FROM nl2_users_integrations LEFT JOIN nl2_integrations ON integration_id=nl2_integrations.id WHERE user_id = ?', [$this->data()->id]);
            if ($integrations_query->count()) {
                $integrations_query = $integrations_query->results();
            } else {
                $integrations_query = [];
            }
            self::$_integration_cache[$this->data()->id] = $integrations_query;
        }

        $integrations_list = [];
        foreach ($integrations_query as $item) {
            $integration = $integrations->getIntegration($item->integration_name);
            if ($integration != null) {
                $integrationUser = new IntegrationUser($integration, $this->data()->id, 'user_id', $item);

                $integrations_list[$item->integration_name] = $integrationUser;
            }
        }

        return $this->_integrations = $integrations_list;
    }

    /**
     * Get the user's integration.
     *
     * @param string $integrationName Integration name
     *
     * @return IntegrationUser|null Their integration user  if connected otherwise null.
     */
    public function getIntegration(string $integrationName): ?IntegrationUser {
        return $this->getIntegrations()[$integrationName] ?? null;
    }

    /**
     * Get the users placeholders.
     *
     * @return array Their placeholders.
     */
    public function getPlaceholders(): array {
        if (isset($this->_placeholders)) {
            return $this->_placeholders;
        }

        $integrationUser = $this->getIntegration('Minecraft');
        if ($integrationUser !== null) {
            return $this->_placeholders = Placeholders::getInstance()->loadUserPlaceholders($integrationUser->data()->identifier);
        }

        return [];
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
     * Get this user's main group (with highest order).
     *
     * @return Group The group
     */
    public function getMainGroup(): Group {
        return $this->_main_group ??= array_reduce($this->getGroups(), static function (?Group $carry, Group $group) {
            return $carry === null || $carry->order > $group->order ? $group : $carry;
        });
    }

    /**
     * Set a group to user and remove all other groups
     *
     * @param int $group_id ID of group to set as main group.
     * @param int $expire Expiry in epoch time. If not supplied, group will never expire.
     * @return false|void
     */
    public function setGroup(int $group_id, int $expire = 0) {
        if ($this->data()->id == 1) {
            return false;
        }

        $this->_db->query('DELETE FROM `nl2_users_groups` WHERE `user_id` = ?', [$this->data()->id]);

        $this->_db->query('INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)', [
            $this->data()->id,
            $group_id,
            date('U'),
            $expire
        ]);

        $this->_groups = [];
        $group = Group::find($group_id);
        if ($group) {
            $this->_groups[$group_id] = $group;
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
        if (!array_key_exists($group_id, $this->getGroups())) {
            return false;
        }

        if ($group_id == 2 && $this->data()->id == 1) {
            return false;
        }

        $this->_db->query('DELETE FROM `nl2_users_groups` WHERE `user_id` = ? AND `group_id` = ?', [
            $this->data()->id,
            $group_id
        ]);

        EventHandler::executeEvent(new UserGroupRemovedEvent(
            $this,
            $this->_groups[$group_id],
        ));

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
     * Get a comma separated string of all other users.
     * For the new private message dropdown.
     *
     * @return array Array of usernames.
     */
    public function listAllOtherUsers(): array {
        $data = $this->_db->query("SELECT `username` FROM `nl2_users` WHERE `id` <> ?", [$this->data()->id])->results();
        $return = [];

        foreach ($data as $item) {
            $return[] = $item->username;
        }

        return $return;
    }

    /**
     * Return an ID from a username.
     *
     * @param string $username Username to get ID for.
     *
     * @return ?int ID on success, null on failure.
     */
    public function nameToId(string $username): ?int {
        $data = $this->_db->get('users', ['username', $username]);

        if ($data->count()) {
            return $data->first()->id;
        }

        return null;
    }

    /**
     * Return an ID from an email.
     *
     * @param string $email Email to get ID for.
     * @return ?int ID on success, false on failure.
     */
    public function emailToId(string $email): ?int {
        $data = $this->_db->get('users', ['email', $email]);

        if ($data->count()) {
            return $data->first()->id;
        }

        return null;
    }

    /**
     * Get a list of PMs a user has access to.
     *
     * @param int $user_id ID of user to get PMs for.
     * @return array Array of PMs.
     */
    public function listPMs(int $user_id): array {
        $return = []; // Array to return containing info of PMs

        // Get a list of PMs which the user is in
        $data = $this->_db->get('private_messages_users', ['user_id', $user_id]);

        if ($data->count()) {
            $data = $data->results();
            foreach ($data as $result) {
                // Get a list of users who are in this conversation and return them as an array
                $pms = $this->_db->get('private_messages_users', ['pm_id', $result->pm_id])->results();
                $users = []; // Array containing users with permission
                foreach ($pms as $pm) {
                    $users[] = $pm->user_id;
                }

                // Get the PM data
                $pm = $this->_db->get('private_messages', ['id', $result->pm_id])->first();

                $return[$pm->id]['id'] = $pm->id;
                $return[$pm->id]['title'] = $pm->title;
                $return[$pm->id]['created'] = $pm->created;
                $return[$pm->id]['updated'] = $pm->last_reply_date;
                $return[$pm->id]['user_updated'] = $pm->last_reply_user;
                $return[$pm->id]['users'] = $users;
            }
        }

        // Order the PMs by date updated - most recent first
        usort($return, static function ($a, $b) {
            return $b['updated'] - $a['updated'];
        });

        return $return;
    }

    /**
     * Get a specific private message, and see if the user actually has permission to view it
     *
     * @param int $pm_id ID of PM to find.
     * @param int $user_id ID of user to check permission for.
     * @return array|null Array of info about PM, null on failure.
     */
    public function getPM(int $pm_id, int $user_id): ?array {
        // Get the PM - is the user the author?
        $data = $this->_db->get('private_messages', ['id', $pm_id]);
        if ($data->count()) {
            $data = $data->first();

            // Does the user have permission to view the PM?
            $pms = $this->_db->get('private_messages_users', ['pm_id', $pm_id])->results();
            foreach ($pms as $pm) {
                if ($pm->user_id == $user_id) {
                    $has_permission = true;
                    $pm_user_id = $pm->id;
                    break;
                }
            }

            if (!isset($has_permission)) {
                return null; // User doesn't have permission
            }

            // Set message to "read"
            if ($pm->read == 0) {
                $this->_db->update('private_messages_users', $pm_user_id, [
                    'read' => true,
                ]);
            }

            // User has permission, return the PM information
            $users = []; // Array to store users
            foreach ($pms as $pm) {
                $users[] = $pm->user_id;
            }

            return [$data, $users];
        }

        return null;
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

            if (defined('CONFIG_PATH')) {
                $_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));
            }
        } else {
            $_SESSION['last_page'] = URL::build($_GET['route']);
        }

        if (!$this->isLoggedIn()) {
            Redirect::to(URL::build('/login'));
        }

        if (!$this->canViewStaffCP()) {
            Redirect::to(URL::build('/'));
        }

        if (!$this->isAdmLoggedIn()) {
            Redirect::to(URL::build('/panel/auth'));
        }

        return !($permission != null && !$this->hasPermission($permission));
    }

    /**
     * Can the specified user view StaffCP?
     *
     * @return bool Whether they can view it or not.
     */
    public function canViewStaffCP(): bool {
        foreach ($this->getGroups() as $group) {
            if ($group->admin_cp == 1) {
                return true;
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
     * Get profile fields for this user
     *
     * @param bool $show_private Whether to only return public fields or not (default `true`).
     * @param bool $only_forum Whether to only return fields which display on forum posts, only if $public is true (default `false`).
     *
     * @return array<int, UserProfileField> Array of profile fields.
     */
    public function getProfileFields(bool $show_private = false, bool $only_forum = false): array {
        $rows = DB::getInstance()->query('SELECT pf.*, upf.id as upf_id, upf.value, upf.updated FROM nl2_profile_fields pf LEFT JOIN nl2_users_profile_fields upf ON (pf.id = upf.field_id AND upf.user_id = ?)', [
            $this->data()->id,
        ])->results();

        $fields = [];

        foreach ($rows as $row) {
            $field = new UserProfileField($row);
            // Check that the field is public, or they are viewing private fields
            // also if they're checking forum fields, check that the field is a forum field
            // TODO: ideally within the query
            if (($field->public || $show_private) && (!$only_forum || $field->forum_posts)) {
                $fields[$row->id] = $field;
            }
        }

        return $fields;
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
            $possible_users = $this->_db->get('blocked_users', ['user_id', $user]);
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
        if ($this->exists()) {
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
        return Util::getSetting('private_profile') === '1' && $this->hasPermission('usercp.private_profile');
    }

    /**
     * Can the user bypass private profiles?
     *
     * @return bool Whether the user can bypass private profiles
     */
    public function canBypassPrivateProfile(): bool {
        return Util::getSetting('private_profile') === '1' && $this->hasPermission('profile.private.bypass');
    }

    /**
     * Is the profile page set to private?
     *
     * @return bool Whether their profile is set to private or not.
     */
    public function isPrivateProfile(): bool {
        return $this->data()->private_profile ?? false;
    }

    /**
     * Get templates this user's group has access to.
     *
     * @return array Templates which the user has access to.
     */
    public function getUserTemplates(): array {
        $groups = '(';
        foreach ($this->getGroups() as $group) {
            $groups .= ((int) $group->id) . ',';
        }
        $groups = rtrim($groups, ',') . ')';

        return $this->_db->query("SELECT template.id, template.name FROM nl2_templates AS template WHERE template.enabled = 1 AND template.id IN (SELECT template_id FROM nl2_groups_templates WHERE can_use_template = 1 AND group_id IN $groups)")->results();
    }

    /**
     * Save/update this users placeholders.
     *
     * @param int $server_id Server ID from staffcp -> integrations to assoc these placeholders with.
     * @param array $placeholders Key/value array of placeholders name/value from API endpoint.
     */
    public function savePlaceholders(int $server_id, array $placeholders): void {
        $integrationUser = $this->getIntegration('Minecraft');
        if ($integrationUser === null || !$integrationUser->getIntegration()->isEnabled()) {
            return;
        }

        $uuid = hex2bin(str_replace('-', '', $integrationUser->data()->identifier));
        foreach ($placeholders as $name => $value) {
            Placeholders::getInstance()->registerPlaceholder($server_id, $name);

            $last_updated = time();

            $this->_db->query('INSERT INTO nl2_users_placeholders (server_id, uuid, name, value, last_updated) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE value = ?, last_updated = ?', [
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
