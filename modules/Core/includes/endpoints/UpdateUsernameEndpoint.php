<?php

/**
 * @param int $id The NamelessMC user to update
 * @param string $uuid The Minecraft UUID of the user
 * @param string $username The new username of the user
 *
 * @return string JSON Array
 */
class UpdateUsernameEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'updateUsername';
        $this->_module = 'Core';
        $this->_description = 'Update a username from a UUID';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['id', 'username'])) {
                // Ensure user exists
                $user = $api->getDb()->get('users', array('id', '=', Output::getClean($_POST['id'])));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));

                $user = $user->first()->id;

                $fields = array('username' => Output::getClean($_POST['username']));

                if (!Util::getSetting($api->getDb(), 'displaynames')) $fields['nickname'] = Output::getClean($_POST['username']);

                try {
                    $api->getDb()->update('users', $user, $fields);
                } catch (Exception $e) {
                    $api->throwError(24, $api->getLanguage()->get('api', 'unable_to_update_username'));
                }

                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'username_updated')));
            }
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}