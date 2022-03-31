<?php
/**
 * Allows an endpoint to specify its own authorisation method.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
abstract class CustomAuthEndpoint extends EndpointBase {

    final public function isAuthorised(Nameless2API $api): bool {
        return $this->authorise($api);
    }

    abstract public function authorise(Nameless2API $api): bool;
}
