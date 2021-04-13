<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Cookie class
 */

class Cookie {

    /**
     * Check the specified cookie exists.
     * 
     * @param string $name Name of cookie to check
     * @return bool Whether this cookie exists or not.
     */
    public static function exists($name) {
        return isset($_COOKIE[$name]);
    }

    /**
     * Return the value of the specified cookie.
     * 
     * @param string $name Name of cookie to return value of
     */
    public static function get($name) {
        return $_COOKIE[$name];
    }

    /**
     * Create a new cookie.
     * 
     * @param string $name Name of cookie to create.
     * @param string $value Value to store in cookie.
     * @param int $expiry When does the cookie expire?
     */
    public static function put($name, $value, $expiry) {
        return setcookie($name, $value, time() + $expiry, '/');
    }

    /**
     * Delete a cookie.
     * 
     * @param string $name Name of cookie to delete
     */
    public static function delete($name) {
        return setcookie($name, '', time() - 1, '/');
    }
}
