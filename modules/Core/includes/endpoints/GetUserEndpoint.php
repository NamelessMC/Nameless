<?php

/**
 * POC, remove upon merge... or not :O
 */
class GetUserEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'user/{user}';
        $this->_module = 'Core';
        $this->_description = 'Get a NamelessMC user by their NamelessMC ID';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api, User $user): void {
        die(json_encode($user->data()));
    }

}
