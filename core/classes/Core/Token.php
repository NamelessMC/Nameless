<?php
/**
 * Validates and generates CSRF tokens.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Token {

    /**
     * Get current form token.
     *
     * @return string current form token.
     */
    public static function get(): string {
        $tokenName = Config::get('session.token_name');

        // Return if it already exists
        if (Session::exists($tokenName)) {
            return Session::get($tokenName);
        }

        // Otherwise, generate a new one
        self::generate();

        return self::get();
    }

    /**
     * Generate a form token and store in a session variable
     */
    public static function generate(): void {
        // Generate random token using md5
        Session::put(Config::get('session.token_name'), md5(uniqid('', true)));
    }

    /**
     * Check if token in session matches current token.
     *
     * @param string|null $token Contains the form token which will be checked against the session variable.
     *
     * @return bool Whether token matches.
     * @throws Exception
     */
    public static function check(string $token = null): bool {
        if ($token === null) {
            $token = Input::get('token');
        }

        $tokenName = Config::get('session.token_name');

        // Check the token matches
        return Session::exists($tokenName) && $token === Session::get($tokenName);
    }
}
