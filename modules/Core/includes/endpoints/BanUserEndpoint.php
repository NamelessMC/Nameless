<?php

class BanUserEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'banUser';
        $this->_module = 'Core';
        $this->_description = 'Ban a NamelessMC user by their NamelessMC ID';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['id']);

        $user = $api->getUser('id', $_POST['id']);

        $user->update([
            'isbanned' => 1,
        ]);

        DB::getInstance()->delete('users_session', [
            'user_id', '=', $user->data()->id
        ]);
    }
}