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
            $this->throwError(0, $this->_language->get('api', 'unknown_error'), $e->getMessage());
        }
    }

    /**
     * Throw an error to the client
     *
     * @param mixed $code The error code
     * @param mixed $message The error message
     * @param mixed $meta Any additional data to return
     * @param int $status HTTP status code
     */
    public function throwError($code = null, $message = null, $meta = null, int $status = 400): void {
        http_response_code($status);

        if ($code && $message) {
            die(self::encodeJson(
                ['error' => true, 'code' => $code, 'message' => $message, 'meta' => $meta]
            ));
        }

        die(self::encodeJson(
            ['error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error'), 'meta' => $meta]
        ));
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

        if (!$user->data()) {
            $this->throwError(16, $this->getLanguage()->get('api', 'unable_to_find_user'));
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
     * @param mixed $arr Array of data to be returned
     * @param int $status HTTP status code
     */
    public function returnArray($arr = null, int $status = 200): void {
        if (!$arr) {
            $arr = [];
        }

        $arr['error'] = false;

        http_response_code($status);

        die(self::encodeJson($arr));
    }

    /**
     * Validate input data
     *
     * @param array $input The input array
     * @param array $required_fields Array of required fields
     * @param string $type Whether to check `post` or `get` input
     * @return bool True if the input is valid, false if not
     */
    public function validateParams(array $input, array $required_fields, string $type = 'post'): bool {
        if (empty($input)) {
            $this->throwError(6, $this->_language->get('api', 'invalid_' . $type . '_contents'));
        }
        foreach ($required_fields as $required) {
            if (empty($input[$required])) {
                $this->throwError(6, $this->_language->get('api', 'invalid_' . $type . '_contents'), ['field' => $required]);
            }
        }
        return true;
    }

    /**
     * Encode a value as json, with pretty printing enabled if DEBUGGING is defined.
     * @param mixed $value Object to encode
     * @return string|false JSON encoded string on success or false on failure.
     */
    private static function encodeJson(mixed $value): mixed {
        if (defined('DEBUGGING')) {
            return json_encode($value, JSON_PRETTY_PRINT);
        } else {
            return json_encode($value);
        }
    }

}
