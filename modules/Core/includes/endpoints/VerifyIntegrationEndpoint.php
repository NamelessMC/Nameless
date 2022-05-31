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
            $api->throwError(CoreApiErrors::ERROR_INVALID_INTEGRATION);
        }

        // Get integration user by code
        $integrationUser = new IntegrationUser($integration, $_POST['code'], 'code');
        if (!$integrationUser->exists() || $integrationUser->isVerified()) {
            $api->throwError(CoreApiErrors::ERROR_INVALID_CODE);
        }

        // Validate username and make sure username is unique
        if (!$integration->validateUsername($_POST['username'], $integrationUser->data()->id)) {
            $api->throwError($integration->getErrors()[0]);
        }

        // Validate identifier and make sure identifier is unique
        if (!$integration->validateIdentifier($_POST['identifier'], $integrationUser->data()->id)) {
            $api->throwError($integration->getErrors()[0]);
        }

        $integrationUser->update([
            'identifier' => $_POST['identifier'],
            'username' => $_POST['username'],
        ]);

        $integrationUser->verifyIntegration();

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'account_validated')]);
    }
}
