<?php
/**
 * Allows an endpoint to not require any authorisation.
 *
 * @author Aberdeener
 *
 * @version 2.0.0-pr13
 *
 * @license MIT
 */
class NoAuthEndpoint extends EndpointBase
{
    final public function isAuthorised(Nameless2API $api): bool
    {
        return true;
    }
}
