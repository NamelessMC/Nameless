<?php

/**
 * @param int $id The NamelessMC user's ID
 * @param string $code The NamelessMC user's reset code, used to verify they own the account
 *
 * @return string JSON Array
 */
class VerifyMinecraftEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'minecraft/verify';
        $this->_module = 'Core';
        $this->_description = 'Validate/Activate a NamelessMC account by confirming their reset code';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['user', 'code']);

        $user = $api->getUser('id', $_POST['user']);

        if ($user->data()->active || $user->data()->reset_code == '') {
            $api->throwError(32, $api->getLanguage()->get('api', 'user_already_active'));
        }

        if ($user->data()->reset_code != $_POST['code']) {
            $api->throwError(28, $api->getLanguage()->get('api', 'invalid_code'));
        }

        $user->update([
            'active' => 1,
            'reset_code' => ''
        ]);

        EventHandler::executeEvent('validateUser', [
            'user_id' => $user->data()->id,
            'username' => Output::getClean($user->data()->username),
            'language' => $api->getLanguage()
        ]);

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'account_validated')]);
    }
}
