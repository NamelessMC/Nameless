<?php

/**
 * @param string $integration The Integration Name
 * @param string $identifier The id of the integration account
 * @param string $username The username of the integration account
 * @param bool $verified Is the integration account verified
 *
 * @return string JSON Array
 */
class LinkIntegrationEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/integrations/link';
        $this->_module = 'Core';
        $this->_description = 'Link a integration to user';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api, User $user): void {
        $api->validateParams($_POST, ['integration', 'identifier', 'username']);

        $integration = Integrations::getInstance()->getIntegration($_POST['integration']);
        if ($integration === null) {
            $api->throwError(CoreApiErrors::ERROR_INVALID_INTEGRATION);
        }

        $integration_user = $user->getIntegration($integration->getName());
        if ($integration_user != null) {
            $api->throwError(CoreApiErrors::ERROR_INTEGRATION_ALREADY_LINKED);
        }

        // Validate username and make sure username is unique
        if (!$integration->validateUsername($_POST['username'])) {
            $api->throwError(CoreApiErrors::ERROR_INTEGRATION_USERNAME_ERRORS, $integration->getErrors());
        }

        // Validate identifier and make sure identifier is unique
        if (!$integration->validateIdentifier($_POST['identifier'])) {
            $api->throwError(CoreApiErrors::ERROR_INTEGRATION_IDENTIFIER_ERRORS, $integration->getErrors());
        }

        $integrationUser = new IntegrationUser($integration);
        $integrationUser->linkIntegration($user, $_POST['identifier'], $_POST['username'], $_POST['verified'] === true);
        if ($_POST['verified'] === true) {
            $integrationUser->verifyIntegration();
        }

        $api->returnArray(['success' => true]);
    }
}