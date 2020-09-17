<?php

/**
 * List all users on the NamelessMC site
 *
 * @return string JSON Array
 */
class ListUsersEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'listUsers';
        $this->_module = 'Core';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            $users = $api->getDb()->query('SELECT username, uuid, isbanned AS banned, active FROM nl2_users')->results();

            $api->returnArray(array('users' => $users));
        }
    }
}