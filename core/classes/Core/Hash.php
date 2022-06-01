<?php
/**
 * Hashing class.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Hash {

    /**
     * Generate a unique hash.
     *
     * @return string Generated hash.
     * @deprecated Use SecureRandom::alphanumeric() instead which is in a more appropriately named class
     * @throws Exception If an appropriate source of randomness cannot be found.
     */
    public static function unique(): string {
        return bin2hex(random_bytes(32));
    }

}
