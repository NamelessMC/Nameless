<?php

class Nameless2API {

    private DB $_db;
    private Language $_language;

    public function getDb(): DB {
        return $this->_db;
    }

    public function getLanguage(): Language {
        return $this->_language;
    }

    public function __construct(string $route, Language $api_language, Endpoints $endpoints) {
        try {
            $this->_db = DB::getInstance();
            $this->_language = $api_language;

            $explode = explode('/', $route);

            for ($i = count($explode) - 1; $i >= 0; $i--) {
                if (strlen($explode[$i]) == 32) {
                    if ($this->validateKey($explode[$i])) {
                        $api_key = $explode[$i];
                        break;
                    }
                }
            }

            if (!isset($api_key)) {
                $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
            }

            $route = explode('/', $route);
            $route = array_slice($route, 4);
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
     * Validate provided API key to make sure it matches.
     *
     * @param string|null $api_key API key to check.
     *
     * @return bool Whether it matches or not.
     */
    private function validateKey(string $api_key = null): bool {
        if ($api_key) {
            // Check cached key
            if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache')) {
                // Not cached, cache now
                // Retrieve from database
                $correct_key = $this->_db->get('settings', ['name', '=', 'mc_api_key']);
                $correct_key = $correct_key->results();
                $correct_key = htmlspecialchars($correct_key[0]->value);

                // Store in cache file
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $correct_key);

            } else {
                $correct_key = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache');
            }

            if ($api_key == $correct_key) {
                return true;
            }
        }

        return false;
    }

    public function getUser(string $column, string $value): User {
        $user = new User(Output::getClean($value), Output::getClean($column));

        if (!$user->data()) {
            $this->throwError(16, $this->getLanguage()->get('api', 'unable_to_find_user'));
        }

        return $user;
    }

    public function throwError($code = null, $message = null, $meta = null, int $status = 400) {
        http_response_code($status);

        if ($code && $message) {
            die(json_encode(['error' => true, 'code' => $code, 'message' => $message, 'meta' => $meta], JSON_PRETTY_PRINT));
        } else {
            die(json_encode(['error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error'), 'meta' => $meta], JSON_PRETTY_PRINT));
        }
    }

    public function returnArray($arr = null, int $status = 200) {
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
