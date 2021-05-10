<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Hash class
 */

class Hash {

    /**
     * Generate a hash using sha256.
     * 
     * @param string $string String to hash.
     * @param string|null $salt Salt.
     * @return string hashed string.
     */
    public static function make($string, $salt = '') {
        return hash('sha256', $string . $salt);
    }

    /**
     * Generate unique hash.
     * 
     * @return string Generated hash.
     */
    public static function unique() {
        return self::make(uniqid());
    }
}
