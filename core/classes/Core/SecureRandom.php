<?php

/**
 * Secure random token generation.
 *
 * @author Derkades
 *
 * @version 2.0.0-pr13
 *
 * @license MIT
 */
class SecureRandom
{
    /**
     * Generate a unique alphanumeric string.
     *
     * @throws Exception If an appropriate source of randomness cannot be found.
     *
     * @return string Generated string.
     */
    public static function alphanumeric(int $num_bytes = 32): string
    {
        return preg_replace('/(\+|\/|\=)*/', '', base64_encode(random_bytes($num_bytes)));
    }
}
