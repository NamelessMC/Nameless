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
     * Generate unique hash.
     *
     * @return string Generated hash.
     */
    public static function unique(): string {
        return hash('sha256', uniqid());
    }

}
