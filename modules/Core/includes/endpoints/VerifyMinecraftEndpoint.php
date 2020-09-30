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
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['id', 'code'])) {
                $user_query = $api->getDb()->get('users', array('id', '=', $_POST['id']));
                if (!$user_query->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $user_query = $user_query->first();

                if ($user_query->reset_code == $_POST['code']) {
                    $api->getDb()->update('users', $user_query->id, array(
                        'reset_code' => '',
                        'active' => 1
                    ));

                    try {
                        HookHandler::executeEvent('validateUser', array(
                            'event' => 'validateUser',
                            'user_id' => $user_query->id,
                            'username' => Output::getClean($user_query->username),
                            'language' => $api->getLanguage()
                        ));
                    } catch (Exception $e) {
                    }

                    $api->returnArray(array('message' => $api->getLanguage()->get('api', 'account_validated')));
                } else $api->throwError(28, $api->getLanguage()->get('api', 'invalid_code'));
            }
        }
    }
}