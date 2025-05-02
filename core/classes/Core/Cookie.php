<?php

/**
 * Easy read/write of cookies.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr10
 * @license MIT
 */
class Cookie
{
    /**
     * Check if the specified cookie exists.
     *
     * @param  string $name Name of cookie to check
     * @return bool   Whether this cookie exists or not.
     */
    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Return the value of the specified cookie.
     *
     * @param  string $name Name of cookie to get the value of
     * @return mixed  Value of the cookie or an empty string if it doesn't exist
     */
    public static function get(string $name)
    {
        return $_COOKIE[$name] ?? '';
    }

    /**
     * Create a new cookie.
     *
     * @param  string $name      Name of cookie to create.
     * @param  string $value     Value to store in cookie.
     * @param  ?int   $expiry    When does the cookie expire? Null for session
     * @param  ?bool  $secure    Create as secure cookie?
     * @param  ?bool  $httpOnly  Create as httpOnly cookie?
     * @param  ?bool  $addExpiry Whether to add expiry onto current timestamp or not.
     * @return bool   Whether cookie was set or not
     */
    public static function put(
        string $name,
        string $value,
        ?int $expiry,
        ?bool $secure = false,
        ?bool $httpOnly = false,
        ?bool $addExpiry = true
    ): bool {
        if ($expiry && $addExpiry) {
            $expiry = time() + $expiry;
        }

        return setcookie($name, $value, $expiry, '/', null, $secure, $httpOnly);
    }

    /**
     * Delete a cookie.
     *
     * @param string $name Name of cookie to delete
     */
    public static function delete(string $name): bool
    {
        return setcookie($name, '', time() - 1, '/');
    }
}
