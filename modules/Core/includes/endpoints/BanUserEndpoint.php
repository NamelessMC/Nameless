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
class BanUserEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/ban';
        $this->_module = 'Core';
        $this->_description = 'Ban a NamelessMC user by their NamelessMC ID';
        $this->_method = 'POST';
    }

    /**
     * @param Nameless2API $api
     * @param User $user
     *
     * @return void
     * @throws Exception
     */
    public function execute(Nameless2API $api, User $user): void {
        $user->update([
            'isbanned' => true,
        ]);

        DB::getInstance()->delete('users_session', [
            'user_id', $user->data()->id
        ]);
    }
}
