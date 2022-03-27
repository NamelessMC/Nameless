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
     * Determine if the passed API key (`HTTP_X_API_KEY`) is valid.
     *
     * @param Nameless2API $api Instance of the Nameless2API class
     * @return bool Whether the API key is valid
     */
    final public function isAuthorised(Nameless2API $api): bool {
        if (!isset($_SERVER['HTTP_X_API_KEY'])) {
            return false;
        }

        if ($this->validateKey($api, $_SERVER['HTTP_X_API_KEY'])) {
            return true;
        }

        /** @phpstan-ignore-next-line  */
        $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'), null, 403);
    }

    /**
     * Validate provided API key to make sure it matches.
     *
     * @param Nameless2API $api Instance of API to use for database connection.
     * @param string $api_key API key to check.
     * @return bool Whether it matches or not.
     */
    private function validateKey(Nameless2API $api, string $api_key): bool {
        // Check cached key
        if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache')) {
            // Not cached, cache now
            // Retrieve from database
            $correct_key = $api->getDb()->get('settings', ['name', '=', 'mc_api_key']);
            $correct_key = $correct_key->results();
            $correct_key = htmlspecialchars($correct_key[0]->value);

            // Store in cache file
            file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $correct_key);

        } else {
            $correct_key = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache');
        }

        return $api_key === $correct_key;
    }
}
