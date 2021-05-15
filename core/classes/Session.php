<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT

 *  Session class
 */

class Session {

    /**
     * Check to see if a session exists.
     *
     * @param string $name Session variable name to check for.
     * @return bool
     */
    public static function exists($name) {
        return isset($_SESSION[$name]);
    }

    /**
     * Create a new session variable
     *
     * @param string $name Contains the session variable name that will be created.
     * @param string $value Contains the variable value to store
     */
    public static function put($name, $value) {
        $_SESSION[$name] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $name Contains the session variable name to retrieve.
     * @return mixed Session variable.
     */
    public static function get($name) {
        return $_SESSION[$name];
    }
    
    /**
     * Delete a session variable.
     *
     * @param  mixed $name Contains the session variable name to delete.
     */
    public static function delete($name) {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Flash a session variable on the screen.
     *
     * @param string $name Contains the session variable name to flash on screen.
     * @param string|null $string Contains the message to flash on the screen (optional).
     * @return mixed Session variable if it exists, nothing if it is being set.
     */
    public static function flash($name, $string = '') {
        // If the session exists, display it on screen ("flash" it)
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            // The session doesn't exist, set it as a variable now so it can be "flashed" in the future
            self::put($name, $string);
        }
    }
}
