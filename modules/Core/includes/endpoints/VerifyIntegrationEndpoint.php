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

        // Get integration user by code
        $integrationUser = new IntegrationUser($integration, $_POST['code'], 'code');
        if (!$integrationUser->exists() || $integrationUser->isVerified()) {
            $api->throwError(28, $api->getLanguage()->get('api', 'invalid_code'));
        }

        // Ensure username doesn't already exist
        $exists = new IntegrationUser($integration, $_POST['username'], 'username');
        if ($exists->exists() && $exists->data()->id != $integrationUser->data()->id) {
            $api->throwError(38, str_replace('{integration}', $integration->getName(), $api->getLanguage()->get('api', 'integration_username_already_linked')));
        }

        // Ensure identifier doesn't already exist
        $exists = new IntegrationUser($integration, $_POST['identifier'], 'identifier');
        if ($exists->exists() && $exists->data()->id != $integrationUser->data()->id) {
            $api->throwError(39, str_replace('{integration}', $integration->getName(), $api->getLanguage()->get('api', 'integration_identifier_already_linked')));
        }

        $integrationUser->update([
            'identifier' => $_POST['identifier'],
            'username' => $_POST['username'],
        ]);

        $integrationUser->verifyIntegration();

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'account_validated')]);
    }
}
