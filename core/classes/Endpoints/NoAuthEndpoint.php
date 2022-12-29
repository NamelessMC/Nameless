<?php

/**
 * Allows an endpoint to not require any authorisation.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class NoAuthEndpoint extends EndpointBase {

    /**
     * Determine if this request is authorized to use this Endpoint.
     * Default implementations:
     * - NoAuthEndpoint to return true
     * - KeyAuthEndpoint to return true if the API key in header is valid
     *
     * @param Nameless2API $api Instance of Nameless2API.
     *
     * @return bool
     */
    final public function isAuthorised(Nameless2API $api): bool {
        return true;
    }
}
