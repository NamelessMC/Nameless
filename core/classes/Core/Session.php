<?php
/**
 * Provides access to get/set/delete session data.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Session {

    /**
     * "Flash" a session variable.
     * The first time this is called, the variable is set, the second time it is retrieved + removed from session.
     * Often used for temp success/error messages.
     *
     * @param string $name Contains the session variable name to flash on screen.
     * @param string $string Contains the message to flash on the screen (optional).
     * @return mixed Session variable if it exists, nothing if it is being set.
     */
    public static function flash(string $name, string $string = '') {
        // If the session exists, display it on screen ("flash" it)
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }

        // The session doesn't exist, set it as a variable now, so it can be "flashed" in the future
        self::put($name, $string);
        return null;
    }

    /**
     * Check to see if a session exists.
     *
     * @param string $name Session variable name to check for.
     *
     * @return bool
     */
    public static function exists(string $name): bool {
        return isset($_SESSION[$name]);
    }

    /**
     * Get a session variable.
     *
     * @param string $name Contains the session variable name to retrieve.
     *
     * @return mixed Session variable.
     */
    public static function get(string $name) {
        return $_SESSION[$name];
    }

    /**
     * Delete a session variable.
     *
     * @param string $name Contains the session variable name to delete.
     */
    public static function delete(string $name): void {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Create a new session variable
     *
     * TODO: specify mixed as $value type when minimum PHP version bumped to 8
     *
     * @param string $name Contains the session variable name that will be created.
     * @param mixed $value Contains the variable value to store
     */
    public static function put(string $name, $value): void {
        $_SESSION[$name] = $value;
    }
}
