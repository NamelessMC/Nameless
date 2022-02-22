<?php

/**
 * @param string $integration The Integration Name
 * @param string $code Used to verify they own the account
 * @param string $identifier The id of the integration account
 * @param string $username The username of the integration account
 *
 * @return string JSON Array
 */
class VerifyIntegrationEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'integration/verify';
        $this->_module = 'Core';
        $this->_description = 'Verify and link a NamelessMC user\'s Integration account using their validation code';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['integration', 'code', 'identifier', 'username']);

        $integration = Integrations::getInstance()->getIntegration($_POST['integration']);
        if ($integration == null) {
            $api->throwError(37, $api->getLanguage()->get('api', 'invalid_integration'));
        }

        $integrationUser = new IntegrationUser($integration, $_POST['code'], 'code');
        if (!$integrationUser->exists() || $integrationUser->exists() && $integrationUser->data()->verified) {
            $api->throwError(28, $api->getLanguage()->get('api', 'invalid_code'));
        }

        $integrationUser->update([
            'identifier' => Output::getClean($_POST['identifier']),
            'username' => Output::getClean($_POST['username']),
            'verified' => 1,
            'code' => null
        ]);

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'account_validated')]);
    }
}