<?php
/**
 * Allows an endpoint to require an API key to be present (and valid) in the request.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class KeyAuthEndpoint extends EndpointBase {

    /**
     * Determine if the passed API key (in Authorization header) is valid.
     *
     * @param Nameless2API $api Instance of the Nameless2API class
     * @return bool Whether the API key is valid
     */
    final public function isAuthorised(Nameless2API $api): bool {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return false;
        }

        $exploded = explode(' ', trim($headers['Authorization']));

        if (count($exploded) !== 2 ||
            strcasecmp($exploded[0], 'Bearer') !== 0) {
            return false;
        }

        $api_key = $exploded[1];

        return $this->validateKey($api, $api_key);
    }

    /**
     * Validate provided API key to make sure it matches.
     *
     * @param Nameless2API $api Instance of API to use for database connection.
     * @param string $api_key API key to check.
     * @return bool Whether it matches or not.
     */
    private function validateKey(Nameless2API $api, string $api_key): bool {
        $correct_key = Util::getSetting('mc_api_key');
        if ($correct_key == null) {
            die('API key is null');
        }
        return hash_equals($api_key, $correct_key);
    }

}
