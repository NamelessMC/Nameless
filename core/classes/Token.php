<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Token class
 */

class Token {

    // Generate a form token and store in a session variable
    // No parameters
    public static function generate() {
        // Generate random token using md5
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    // Get the current form token
    // No parameters
    public static function get() {
        $tokenName = Config::get('session/token_name');

        // Return if it already exists
        if (Session::exists($tokenName)) {
            return Session::get($tokenName);
        }
        else {
            // Otherwise generate a new one
            return self::generate();
        }
    }

    // Check a token in session matches
    // Params: $token (string) - contains the form token which will be checked against the session variable
    public static function check($token = null) {
        if ($token == null) $token = Input::get('token');
        $tokenName = Config::get('session/token_name');

        // Check the token matches
        return Session::exists($tokenName) && $token === Session::get($tokenName);
    }
}
