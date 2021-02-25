<?php

/**
 * @param int $id The NamelessMC user's ID
 * @param string $code The NamelessMC user's reset code, used to verify they own the account
 *
 * @return string JSON Array
 */
class VerifyMinecraftEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'verifyMinecraft';
        $this->_module = 'Core';
        $this->_description = 'Validate/Activate a NamelessMC account by confirming their reset code';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['user', 'code']);

        $user = $api->getUser('id', $_POST['user']);

        if ($user->data()->active || $user->reset_code == '') {
            $api->throwError(32, $api->getLanguage()->get('api', 'user_already_active'));
        }

        if ($user->reset_code != $_POST['code']) {
            $api->throwError(28, $api->getLanguage()->get('api', 'invalid_code'));
        }

        $api->getDb()->update(
            'users',
            $user->data()->id,
            array(
                'reset_code' => '',
                'active' => 1
            )
        );

        try {
            HookHandler::executeEvent('validateUser', array(
                'event' => 'validateUser',
                'user_id' => $user->data()->id,
                'username' => Output::getClean($user->username),
                'language' => $api->getLanguage()
            ));
        } catch (Exception $e) {
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'account_validated')));
    }
}
