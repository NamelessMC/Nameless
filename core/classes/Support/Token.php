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

namespace NamelessMC\Core\Support;

class Token {

    /**
     * Generate a form token and store in a session variable
     */
    public static function generate(): void {
        // Generate random token using md5
        Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    /**
     * Get current form token.
     *
     * @return string current form token.
     */
    public static function get(): string {
        $tokenName = Config::get('session/token_name');

        // Return if it already exists
        if (Session::exists($tokenName)) {
            return Session::get($tokenName);
        }

        // Otherwise generate a new one
        self::generate();
        
        return self::get();
    }

    /**
     * Check if token in session matches current token.
     *
     * @param string $token Contains the form token which will be checked against the session variable.
     * 
     * @return bool Whether token matches.
     */
    public static function check(string $token = null): bool {
        if ($token == null) {
            $token = Input::get('token');
        }
        
        $tokenName = Config::get('session/token_name');

        // Check the token matches
        return Session::exists($tokenName) && $token === Session::get($tokenName);
    }
}
