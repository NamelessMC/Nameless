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
    public static function exists(string $name): bool {
        return isset($_COOKIE[$name]);
    }

    /**
     * Return the value of the specified cookie.
     * 
     * @param string $name Name of cookie to return value of
     */
    public static function get(string $name) {
        return $_COOKIE[$name] ?? '';
    }

    /**
     * Create a new cookie.
     * 
     * @param string $name Name of cookie to create.
     * @param string $value Value to store in cookie.
     * @param int $expiry When does the cookie expire?
     * @param ?bool $secure Create as secure cookie?
     * @param ?bool $httpOnly Create as httpOnly cookie?
     * @return bool Whether cookie was set or not
     */
    public static function put(string $name, string $value, int $expiry, ?bool $secure = false, ?bool $httpOnly = false): bool {
        return setcookie($name, $value, time() + $expiry, '/', null, $secure, $httpOnly);
    }

    /**
     * Delete a cookie.
     * 
     * @param string $name Name of cookie to delete
     */
    public static function delete(string $name): bool {
        return setcookie($name, '', time() - 1, '/');
    }
}
