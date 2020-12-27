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

        $users_array = array();
        foreach ($users as $user) {
            $users_array[$user->id]['id'] = intval($user->id);
            $users_array[$user->id]['username'] = $user->username;
            $users_array[$user->id]['uuid'] = $user->uuid;
            $users_array[$user->id]['banned'] = (bool) $user->banned;
            $users_array[$user->id]['verified'] = (bool) $user->active;
        }

        $api->returnArray(array('users' => $users_array));
    }
}
