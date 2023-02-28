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
        $auth_header = HttpUtils::getHeader('Authorization');

        if ($auth_header !== null) {
            $exploded = explode(' ', trim($auth_header));

            if (count($exploded) !== 2 ||
                strcasecmp($exploded[0], 'Bearer') !== 0) {
                $api->throwError(Nameless2API::ERROR_MISSING_API_KEY, 'Authorization header not in expected format');
            }

            $api_key = $exploded[1];
        } else {
            // Some hosting providers remove the Authorization header, fall back to non-standard X-API-Key heeader
            $api_key_header = HttpUtils::getHeader('X-API-Key');
            if ($api_key_header === null) {
                $api->throwError(Nameless2API::ERROR_MISSING_API_KEY, 'Missing authorization header');
            }

            $api_key = $api_key_header;
        }

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
        if ($correct_key === null) {
            die('API key is null');
        }

        return hash_equals($api_key, $correct_key);
    }

}
