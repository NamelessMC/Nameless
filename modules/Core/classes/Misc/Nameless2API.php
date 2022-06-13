<?php
/**
 * NamelessMC API v2 class
 *
 * @package Modules\Core\Misc
 * @author Samerton
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Nameless2API {

    private DB $_db;
    private Language $_language;

    public const ERROR_API_DISABLED = 'nameless:api_is_disabled';
    public const ERROR_UNKNOWN_ERROR = 'nameless:unknown_error';
    public const ERROR_NOT_AUTHORIZED = 'nameless:not_authorized';
    public const ERROR_INVALID_API_KEY = 'nameless:invalid_api_key';
    public const ERROR_MISSING_API_KEY = 'nameless:missing_api_key';
    public const ERROR_INVALID_API_METHOD = 'nameless:invalid_api_method';
    public const ERROR_CANNOT_FIND_USER = 'nameless:cannot_find_user';
    public const ERROR_INVALID_POST_CONTENTS = 'nameless:invalid_post_contents';
    public const ERROR_INVALID_GET_CONTENTS = 'nameless:invalid_get_contents';
    public const ERROR_NO_SITE_UID = 'nameless:no_site_uid';

    /**
     * Create an instance of the API class and forward the request to the Endpoints class.
     *
     * @param string $route The incoming API request route
     * @param Language $api_language Instance of the language class
     * @param Endpoints $endpoints Instance of the Endpoints class
     */
    public function __construct(string $route, Language $api_language, Endpoints $endpoints) {
        try {
            $this->_db = DB::getInstance();

            // Ensure API is actually enabled
            if (!Util::getSetting('use_api')) {
                $this->throwError(self::ERROR_API_DISABLED);
            }

            $this->_language = $api_language;

            $route = explode('/', $route);
            $route = array_slice($route, 3);
            $route = implode('/', $route);

            $_POST = json_decode(file_get_contents('php://input'), true);

            $endpoints->handle(
                $route,
                $_SERVER['REQUEST_METHOD'],
                $this
            );

        } catch (Exception $e) {
            $this->throwError(self::ERROR_UNKNOWN_ERROR, $e->getMessage());
        }
    }

    /**
     * Throw an error to the client
     *
     * @param string $error The namespaced error code
     * @param mixed $meta Any additional data to return
     * @param int $status HTTP status code
     * @return never
     */
    public function throwError(string $error, $meta = null, int $status = 400): void {
        $this->returnArray(
            array_merge(['error' => $error], $meta ? ['meta' => $meta] : []),
            $status
        );
    }

    /**
     * @return DB The database instance
     */
    public function getDb(): DB {
        return $this->_db;
    }

    /**
     * Find a user in the database, or throw an error if not found
     *
     * @param string $column The column to lookup
     * @param string $value The value to lookup in the specified column
     * @return User The resolved user
     */
    public function getUser(string $column, string $value): User {
        $user = new User(Output::getClean($value), Output::getClean($column));

        if (!$user->exists()) {
            $this->throwError(self::ERROR_CANNOT_FIND_USER);
        }

        return $user;
    }

    /**
     * @return Language The current language instance for translations
     */
    public function getLanguage(): Language {
        return $this->_language;
    }

    /**
     * Return an array of data to the client.
     *
     * @param array $array Array of data to be returned
     * @param int $status HTTP status code
     * @return never
     */
    public function returnArray(array $array, int $status = 200): void {
        http_response_code($status);

        die(self::encodeJson($array));
    }

    /**
     * Validate input data
     *
     * @param array $input The input array
     * @param array $required_fields Array of required fields
     * @param string $type Whether to check `post` or `get` input
     * @return bool True if the input is valid, false if not
     */
    public function validateParams(?array $input, array $required_fields, string $type = 'post'): bool {
        $error = $type === 'post'
            ? self::ERROR_INVALID_POST_CONTENTS
            : self::ERROR_INVALID_GET_CONTENTS;

        if (empty($input)) {
            $this->throwError($error);
        }

        foreach ($required_fields as $required) {
            if (empty($input[$required])) {
                $this->throwError($error, ['field' => $required]);
            }
        }
        return true;
    }

    /**
     * Encode a value as json, with pretty printing enabled if DEBUGGING is defined.
     * @param mixed $value Object to encode
     * @return string|false JSON encoded string on success or false on failure.
     */
    private static function encodeJson($value) {
        if (defined('DEBUGGING')) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }

        return json_encode($value);
    }

}
