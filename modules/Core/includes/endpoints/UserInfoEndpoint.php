<?php

/**
 * Get information about a NamelessMC user
 * 
 * @param int $id NamelessMC ID of user to view
 * @param string $username The NamelessMC username of the user to view
 * @param string $uuid The Minecraft UUID of the user
 * 
 * @return string JSON Array
 */
class UserInfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'userInfo';
        $this->_module = 'Core';
    }
    
    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if (isset($_GET['id']))
                $query = $_GET['id'];
            else if (isset($_GET['username']))
                $query = $_GET['username'];
            else if (isset($_GET['uuid']))
                $query = str_replace('-', '', $_GET['uuid']);
            else
                $api->throwError(26, $api->getLanguage()->get('api', 'invalid_get_contents'));

            // Ensure the user exists
            // Check UUID
            $user = $api->getDb()->query('SELECT nl2_users.id, nl2_users.username, nl2_users.nickname as displayname, nl2_users.uuid, nl2_users.group_id, nl2_users.joined as registered, nl2_users.isbanned as banned, nl2_users.active as validated, nl2_users.user_title as userTitle, nl2_groups.name as group_name FROM nl2_users LEFT JOIN nl2_groups ON nl2_users.group_id = nl2_groups.id WHERE nl2_users.id = ? OR nl2_users.username = ? OR nl2_users.uuid = ?', array($query, $query, $query));

            if (!$user->count()) {
                $api->returnArray(array('exists' => false));
            }
            $user = $user->first();
            $user->exists = true;
            $user->banned = ($user->banned) ? true : false;
            $user->validated = ($user->validated) ? true : false;

            $custom_profile_fields = $api->getDb()->query('SELECT fields.name, fields.type, fields.public, fields.description, pf_values.value FROM nl2_users_profile_fields pf_values LEFT JOIN nl2_profile_fields fields ON pf_values.field_id = fields.id WHERE pf_values.user_id = ?', array($user->id));
            $user->profile_fields = $custom_profile_fields->results();

            unset($user->id);

            $api->returnArray((array) $user);
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}