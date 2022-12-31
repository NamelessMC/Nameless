<?php

/**
 * TODO: Add description
 *
 * @package Modules\Core\Endpoints
 * @author UNKNOWN
 * @version UNKNOWN
 * @license MIT
 */
class VerifyEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/verify';
        $this->_module = 'Core';
        $this->_description = 'Validate/Activate a NamelessMC account by confirming their reset code';
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
        $api->validateParams($_POST, ['code']);

        if ($user->data()->active || $user->data()->reset_code === '') {
            $api->throwError(CoreApiErrors::ERROR_USER_ALREADY_ACTIVE);
        }

        if ($user->data()->reset_code !== $_POST['code']) {
            $api->throwError(CoreApiErrors::ERROR_INVALID_CODE);
        }

        $user->update([
            'active' => true,
            'reset_code' => ''
        ]);

        EventHandler::executeEvent('validateUser', [
            'user_id' => $user->data()->id,
            'username' => $user->data()->username,
            'language' => $api->getLanguage()
        ]);

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'account_validated')]);
    }
}
