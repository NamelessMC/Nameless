<?php
/*
 *	Made by Samerton
 *  Additions by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 */

// Headers
header("Content-Type: application/json; charset=UTF-8");

$page_title = 'api';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

// Ensure API is actually enabled
if (!Util::getSetting(DB::getInstance(), 'use_api')) {
    die($language->get('api', 'api_disabled'));
}

// Initialise
$api = new Nameless2API($route, $language, $endpoints);

class Nameless2API {

    private $_db,
            $_language,
            $_endpoints;

    public function getDb() {
        return $this->_db;
    }

    public function getLanguage() {
        return $this->_language;
    }

    public function __construct($route, $api_language, $endpoints) {
        try {
            $this->_db = DB::getInstance();
            $explode = explode('/', $route);

            for ($i = count($explode) - 1; $i >= 0; $i--) {
                if (strlen($explode[$i]) == 32) {
                    if ($this->validateKey($explode[$i])) {
                        $api_key = $explode[$i];
                        break;
                    }
                }
            }

            // Set language
            if (!isset($api_language) || empty($api_language)) {
                $this->throwError(2, 'Invalid language file');
            }

            $this->_language = $api_language;

            if (isset($api_key)) {
                // API key specified
                $this->_endpoints = $endpoints;

                $request = explode('/', $route);
                $this->_db = DB::getInstance();
                $request = $request[count($request) - 1];

                $_POST = json_decode(file_get_contents('php://input'), true);

                if ($this->_endpoints->handle($request, $this) == false) {
                    $this->throwError(3, $this->_language->get('api', 'invalid_api_method'));
                }
            } else {
                $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
            }
        } catch(Exception $e) {
            $this->throwError($e->getMessage());
        }
    }

    // Internal functions

    private function validateKey($api_key = null) {
        if ($api_key) {
            // Check cached key
            if (!is_file(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache')) {
                // Not cached, cache now
                // Retrieve from database
                $correct_key = $this->_db->get('settings', array('name', '=', 'mc_api_key'));
                $correct_key = $correct_key->results();
                $correct_key = htmlspecialchars($correct_key[0]->value);

                // Store in cache file
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $correct_key);

            } else $correct_key = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache');

            if ($api_key == $correct_key) return true;
        }
        return false;
    }

    public function getUser($column, $value) {
        $user = new User(Output::getClean($value), Output::getClean($column));
        if (!count($user->data())) $this->throwError(16, $this->getLanguage()->get('api', 'unable_to_find_user'));
        return $user;
    }

    public function throwError($code = null, $message = null, $meta = null) {
        if ($code && $message) {
            die(json_encode(array('error' => true, 'code' => $code, 'message' => $message, 'meta' => $meta), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error'), 'meta' => $meta), JSON_PRETTY_PRINT));
        }
    }

    public function returnArray($arr = null) {
        if (!$arr) $arr = array();

        $arr['error'] = false;
        die(json_encode($arr, JSON_PRETTY_PRINT));
    }

    public function validateParams($input, $required_fields, $type = 'post') {
        if (!isset($input) || empty($input)) {
            $this->throwError(6, $this->_language->get('api', 'invalid_' . $type . '_contents'));
        }
        foreach ($required_fields as $required) {
            if (!isset($input[$required]) || empty($input[$required])) {
                $this->throwError(6, $this->_language->get('api', 'invalid_' . $type . '_contents'), array('field' => $required));
            }
        }
        return true;
    }
}
