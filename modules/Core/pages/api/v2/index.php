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
$is_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
if ($is_enabled[0]->value != '1') {
    die('API is disabled');
}

// Initialise
$api = new Nameless2API($route, $language, $endpoints);

class Nameless2API {

    private 
        $_validated = false,
        $_db,
        $_language,
        $_endpoints;

    public function isValidated() {
        return $this->_validated;
    }

    public function getDb() {
        return $this->_db;
    }

    public function getLanguage() {
        return $this->_language;
    }

    public function __construct($route, $api_language, $endpoints) {
        try {
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
            if (!isset($api_language) || empty($api_language)) $this->throwError(2, 'Invalid language file');
            $this->_language = $api_language;

            if (isset($api_key)) {
                // API key specified
                $this->_validated = true;
                $this->_db = DB::getInstance();
                $this->_endpoints = $endpoints;
                
                $request = explode('/', $route);
                $request = $request[count($request) - 1];

                if ($this->_endpoints->handle($request, $this) == false) {
                    $this->throwError(3, $this->_language->get('api', 'invalid_api_method'));
                }   
            } else $this->throwError(1, $this->_language->get('api', 'invalid_api_key'));
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

    public function throwError($code = null, $message = null) {
        if ($code && $message) {
            die(json_encode(array('error' => true, 'code' => $code, 'message' => $message), JSON_PRETTY_PRINT));
        } else {
            die(json_encode(array('error' => true, 'code' => 0, 'message' => $this->_language->get('api', 'unknown_error')), JSON_PRETTY_PRINT));
        }
    }

    public function returnArray($arr = null) {
        if (!$arr) $arr = array();

        $arr['error'] = false;
        die(json_encode($arr, JSON_PRETTY_PRINT));
    }
    
    public function validateParams($input, $required_fields) {
        if (!isset($input) || empty($input)) {
            if (!empty($_GET)) $this->throwError(6, $this->_language->get('api', 'invalid_get_contents'));
            else $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
        }
        foreach ($required_fields as $required) {
            if (!isset($input[$required]) || empty($input[$required])) {
                if (!empty($_GET)) $this->throwError(6, $this->_language->get('api', 'invalid_get_contents'));
                else $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'));
            }
        }
        return true;
    }
}