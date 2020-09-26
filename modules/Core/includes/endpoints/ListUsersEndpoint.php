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
        if ($api->isValidated()) {

            if (isset($_GET['banned'])) {
                $banned = ($_GET['banned'] == 'false') ? false : true;
            } else $banned = true;
            
            if (isset($_GET['active'])) {
                $active = ($_GET['active'] == 'false') ? false : true;
            } else $active = true;

            $users = $api->getDb()->query('SELECT id, username, uuid, isbanned AS banned, active FROM nl2_users')->results();

            $users_array = array();
            foreach ($users as $user) {
                $users_array[$user->id]['id'] = intval($user->id);
                $users_array[$user->id]['username'] = $user->username;
                $users_array[$user->id]['uuid'] = $user->uuid;
                if ($banned) $users_array[$user->id]['banned'] = ($user->banned) ? true : false;
                if ($active) $users_array[$user->id]['active'] = ($user->active) ? true : false;
            }

            $api->returnArray(array('users' => $users_array));
        }
    }
}