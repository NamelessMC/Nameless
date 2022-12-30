<?php

/**
 * TODO: Add description
 *
 * @package Modules\Core\Endpoints
 * @author UNKNOWN
 * @author UNKOWN
 * @version UNKNOWN
 * @license MIT
 */
class UpdateUsernameEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/update-username';
        $this->_module = 'Core';
        $this->_description = 'Update a users NamelessMC username to a new username';
        $this->_method = 'POST';
    }

    /**
     * @param Nameless2API $api
     * @param User $user
     *
     * @return void
     */
    public function execute(Nameless2API $api, User $user): void {
        $api->validateParams($_POST, ['username']);

        $fields = ['username' => $_POST['username']];

        if (Util::getSetting('displaynames') !== '1') {
            $fields['nickname'] = $_POST['username'];
        }

        try {
            $api->getDb()->update('users', $user->data()->id, $fields);
        } catch (Exception $e) {
            $api->throwError(CoreApiErrors::ERROR_UNABLE_TO_UPDATE_USERNAME, null, 500);
        }

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'username_updated')]);
    }
}
