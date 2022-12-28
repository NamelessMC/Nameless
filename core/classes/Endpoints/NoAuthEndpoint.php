<?php
declare(strict_types=1);

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
     * @param Nameless2API $api
     *
     * @return bool
     */
    final public function isAuthorised(Nameless2API $api): bool {
        return true;
    }
}
