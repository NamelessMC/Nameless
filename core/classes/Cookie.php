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

    // Check the specified cookie exists (returns true or false)
    // Params: $name (string) - name of cookie to check
    public static function exists($name) {
        return isset($_COOKIE[$name]);
    }

    // Return the value of the specified cookie
    // Params: $name (string) - name of cookie to return value of
    public static function get($name) {
        return $_COOKIE[$name];
    }

    // Create a new cookie
    // Params: $name (string) - name of cookie to create
    //         $value (string) - value to store in cookie
    //         $expiry (integer) - when does the cookie expire?
    public static function put($name, $value, $expiry) {
        return setcookie($name, $value, time() + $expiry, '/');
    }

    // Delete a cookie
    // Params: $name (string) - name of cookie to delete
    public static function delete($name) {
        return setcookie($name, '', time() - 1, '/');
    }
}
