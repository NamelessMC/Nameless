<?php
/**
 * No params
 *
 * @return string JSON Array
 */
class ListUsersEndpoint extends EndpointBase {
    public function __construct() {
        $this->_route = 'listUsers';
        $this->_module = 'Core';
        $this->_description = 'List all users on the NamelessMC site';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api) {
        $query = 'SELECT id, username, uuid, isbanned AS banned, active FROM nl2_users';

        if (isset($_GET['banned']) && $_GET['banned'] == 'true') {
            $query .= ' WHERE `isbanned` = 1';
            $filterBanned = true;
        }

        if (isset($_GET['active']) && $_GET['active'] == 'true') {
            if (isset($filterBanned)) {
                $query .= ' AND';
            } else {
                $query .= ' WHERE';
            }
            $query .= ' `active` = 1';
        }

        $users = $api->getDb()->query($query)->results();

        $users_json = array();
        foreach ($users as $user) {
            $user_json = array();
            $user_json['id'] = intval($user->id);
            $user_json['username'] = $user->username;
            $user_json['uuid'] = $user->uuid;
            $user_json['banned'] = (bool) $user->banned;
            $user_json['verified'] = (bool) $user->active;
            $users_json[] = $user_json;
        }

        $api->returnArray(array('users' => $users_json));
    }
}
