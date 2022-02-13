<?php
/**
 * Nameless2API class
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

    public function throwError($code = null, $message = null, $meta = null, int $status = 400): void {
        http_response_code($status);

        if ($code && $message) {
            die(json_encode(
                ['error' => true, 'code' => $code, 'message' => $message, 'meta' => $meta],
                JSON_PRETTY_PRINT
            ));
        }

        die(json_encode(
            ['error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error'), 'meta' => $meta],
            JSON_PRETTY_PRINT
        ));
    }

    public function getDb(): DB {
        return $this->_db;
    }

    public function getUser(string $column, string $value): User {
        $user = new User(Output::getClean($value), Output::getClean($column));

        if (!$user->data()) {
            $this->throwError(16, $this->getLanguage()->get('api', 'unable_to_find_user'));
        }

        return $user;
    }

    public function getLanguage(): Language {
        return $this->_language;
    }

    public function returnArray($arr = null, int $status = 200): void {
        if (!$arr) {
            $arr = [];
        }

        $arr['error'] = false;

        http_response_code($status);

        die(json_encode($arr, JSON_PRETTY_PRINT));
    }

    public function validateParams(array $input, array $required_fields, string $type = 'post'): bool {
        if (empty($input)) {
            $this->throwError(6, $this->_language->get('api', 'invalid_' . $type . '_contents'));
        }
        foreach ($required_fields as $required) {
            if (!isset($input[$required]) || empty($input[$required])) {
                $this->throwError(6, $this->_language->get('api', 'invalid_' . $type . '_contents'), ['field' => $required]);
            }
        }
        return true;
    }
}
