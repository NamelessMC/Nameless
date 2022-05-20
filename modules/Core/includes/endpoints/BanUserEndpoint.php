<?php

class BanUserEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/ban';
        $this->_module = 'Core';
        $this->_description = 'Ban a NamelessMC user by their NamelessMC ID';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api, User $user): void {
        $user->update([
            'isbanned' => true,
        ]);

        DB::getInstance()->delete('users_session', [
            'user_id', $user->data()->id
        ]);
    }
}
