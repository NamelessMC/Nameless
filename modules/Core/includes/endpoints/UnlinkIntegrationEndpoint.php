<?php

/**
 * @param string $integration The Integration Name
 *
 * @return string JSON Array
 */
class UnlinkIntegrationEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/integrations/unlink';
        $this->_module = 'Core';
        $this->_description = 'Unlink integration from user';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api, User $user): void {
        $api->validateParams($_POST, ['integration']);

        $integration = Integrations::getInstance()->getIntegration($_POST['integration']);
        if ($integration === null) {
            $api->throwError(CoreApiErrors::ERROR_INVALID_INTEGRATION);
        }

        $integration_user = $user->getIntegration($integration->getName());
        if ($integration_user == null) {
            $api->throwError(CoreApiErrors::ERROR_INTEGRATION_NOT_LINKED);
        }

        $integration_user->unlinkIntegration();

        $api->returnArray(['success' => true]);
    }
}