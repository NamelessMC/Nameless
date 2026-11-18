<?php

/**
 * Centralised password hashing and verification helpers.
 *
 * @package NamelessMC\Core
 * @author TeemoCell
 * @version 2.3.0
 * @license MIT
 */
class Password
{
    public const DEFAULT_METHOD = 'default';

    private const BCRYPT_OPTIONS = [
        'cost' => 13,
    ];

    /**
     * Verify a plaintext password against a stored password hash.
     *
     * @param  string $password         Plaintext password to verify
     * @param  string $hashed_password  Stored password hash
     * @param  string $method           Password hashing method used by the stored hash
     * @return bool Whether the password matches the stored hash
     */
    public static function check(string $password, string $hashed_password, string $method = self::DEFAULT_METHOD): bool
    {
        try {
            return match ($method) {
                self::DEFAULT_METHOD, 'bcrypt' => password_verify($password, $hashed_password),
                'sha256' => self::checkSha256($password, $hashed_password),
                'pbkdf2' => self::checkPbkdf2($password, $hashed_password),
                'modernbb', 'sha1' => hash_equals($hashed_password, sha1($password)),
                default => throw new InvalidArgumentException("Unknown password method [$method]."),
            };
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Hash a plaintext password using the default website password scheme.
     *
     * @param  string $password Plaintext password to hash
     * @return string Hashed password
     */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, self::BCRYPT_OPTIONS);
    }

    /**
     * Reformat a stored password hash for the requested target method.
     *
     * This is primarily used to normalise external password formats such as AuthMe's
     * prefixed sha256/pbkdf2 hashes before storing them in NamelesMC.
     *
     * @param  string $password   Stored password hash to reformat
     * @param  string $method     Current password hashing method
     * @param  string $new_method Target password hashing method
     * @return string Reformatted password hash
     */
    public static function rehash(string $password, string $method, string $new_method): string
    {
        return match ($method) {
            self::DEFAULT_METHOD, 'bcrypt' => self::rehashBcrypt($password, $new_method),
            'modernbb', 'sha1' => self::rehashSha1($password, $new_method),
            'sha256' => self::rehashSha256($password, $new_method),
            'pbkdf2' => self::rehashPbkdf2($password, $new_method),
            default => throw new InvalidArgumentException("Unknown password rehash source [$method]."),
        };
    }

    /**
     * Reformat a bcrypt password hash for a compatible target method.
     *
     * @param  string $password   Stored bcrypt password hash
     * @param  string $new_method Target password hashing method
     * @return string Reformatted password hash
     */
    private static function rehashBcrypt(string $password, string $new_method): string
    {
        return match ($new_method) {
            self::DEFAULT_METHOD, 'bcrypt' => $password,
            default => throw new InvalidArgumentException("Cannot rehash bcrypt password to [$new_method]."),
        };
    }

    /**
     * Reformat a sha1-based password hash for a compatible target method.
     *
     * @param  string $password   Stored sha1 password hash
     * @param  string $new_method Target password hashing method
     * @return string Reformatted password hash
     */
    private static function rehashSha1(string $password, string $new_method): string
    {
        return match ($new_method) {
            'modernbb', 'sha1' => $password,
            default => throw new InvalidArgumentException("Cannot rehash sha1 password to [$new_method]."),
        };
    }

    /**
     * Reformat a sha256 password hash for a compatiblee target method.
     *
     * @param  string $password   Stored sha256 password hash
     * @param  string $new_method Target password hashing method
     * @return string Reformatted password hash
     */
    private static function rehashSha256(string $password, string $new_method): string
    {
        $parts = self::parseSha256($password);

        return match ($new_method) {
            'sha256' => self::formatSha256($parts),
            'authme_sha256' => self::formatAuthmeSha256($parts),
            default => throw new InvalidArgumentException("Cannot rehash sha256 password to [$new_method]."),
        };
    }

    /**
     * Reformat a pbkdf2 password hash for a compatible target method.
     *
     * @param  string $password   Stored pbkdf2 password hash
     * @param  string $new_method Target password hashing method
     * @return string Reformatted password hash
     */
    private static function rehashPbkdf2(string $password, string $new_method): string
    {
        $parts = self::parsePbkdf2($password);

        return match ($new_method) {
            'pbkdf2' => self::formatPbkdf2($parts),
            'authme_pbkdf2' => self::formatAuthmePbkdf2($parts),
            default => throw new InvalidArgumentException("Cannot rehash pbkdf2 password to [$new_method]."),
        };
    }

    /**
     * Verify a plaintext password against a sha256 password hash.
     *
     * @param  string $password        Plaintext password to verify
     * @param  string $hashed_password Stored sha256 password hash
     * @return bool Whether the password matches the stored hash
     */
    private static function checkSha256(string $password, string $hashed_password): bool
    {
        [$salt, $pass] = self::parseSha256($hashed_password);

        return hash_equals($salt.hash('sha256', hash('sha256', $password).$salt), $salt.$pass);
    }

    /**
     * Verify a plaintext password against a pbkdf2 password hash.
     *
     * @param  string $password        Plaintext password to verify
     * @param  string $hashed_password Stored pbkdf2 password hash
     * @return bool Whether the password matches the stored hash
     */
    private static function checkPbkdf2(string $password, string $hashed_password): bool
    {
        [$iterations, $salt, $pass] = self::parsePbkdf2($hashed_password);
        $expected = hex2bin($pass);

        if ($expected === false) {
            throw new InvalidArgumentException('Invalid pbkdf2 password hash.');
        }

        return hash_equals($expected, hash_pbkdf2('sha256', $password, $salt, (int) $iterations, 64, true));
    }

    /**
     * Parse a sha256 password hash into its component parts.
     *
     * @param  string $hashed_password Stored sha256 password hash
     * @return array{0: string, 1: string} Parsed salt and hash components
     */
    private static function parseSha256(string $hashed_password): array
    {
        if (str_starts_with($hashed_password, '$SHA$')) {
            $parts = explode('$', $hashed_password, 4);
            if (count($parts) !== 4) {
                throw new InvalidArgumentException('Invalid AuthMe sha256 password hash.');
            }

            return [$parts[2], $parts[3]];
        }

        $parts = explode('$', $hashed_password, 2);
        if (count($parts) !== 2) {
            throw new InvalidArgumentException('Invalid sha256 password hash.');
        }

        return [$parts[0], $parts[1]];
    }

    /**
     * Parse a pbkdf2 password hash into its component parts.
     *
     * @param  string $hashed_password Stored pbkdf2 password hash
     * @return array{0: string, 1: string, 2: string} Parsed iteration, salt and hash components
     */
    private static function parsePbkdf2(string $hashed_password): array
    {
        if (str_starts_with($hashed_password, 'pbkdf2_sha256$')) {
            $parts = explode('$', $hashed_password, 4);
            if (count($parts) !== 4) {
                throw new InvalidArgumentException('Invalid AuthMe pbkdf2 password hash.');
            }

            return [$parts[1], $parts[2], $parts[3]];
        }

        $parts = explode('$', $hashed_password, 3);
        if (count($parts) !== 3) {
            throw new InvalidArgumentException('Invalid pbkdf2 password hash.');
        }

        return [$parts[0], $parts[1], $parts[2]];
    }

    /**
     * Format parsed sha256 password hash parts into NamelessMC storage format.
     *
     * @param  array{0: string, 1: string} $parts Parsed salt and hash components
     * @return string Formatted sha256 password hash
     */
    private static function formatSha256(array $parts): string
    {
        return $parts[0].'$'.$parts[1];
    }

    /**
     * Format parsed sha256 password hash parts into AuthMe storage format.
     *
     * @param  array{0: string, 1: string} $parts Parsed salt and hash components
     * @return string Formatted AuthMe sha256 password hash
     */
    private static function formatAuthmeSha256(array $parts): string
    {
        return '$SHA$'.$parts[0].'$'.$parts[1];
    }

    /**
     * Format parsed pbkdf2 password hash parts into NamelessMC storage format.
     *
     * @param  array{0: string, 1: string, 2: string} $parts Parsed iteration, salt and hash components
     * @return string Formatted pbkdf2 password hash
     */
    private static function formatPbkdf2(array $parts): string
    {
        return $parts[0].'$'.$parts[1].'$'.$parts[2];
    }

    /**
     * Format parsed pbkdf2 password hash parts into AuthMe storage format.
     *
     * @param  array{0: string, 1: string, 2: string} $parts Parsed iteration, salt and hash components
     * @return string Formatted AuthMe pbkdf2 password hash
     */
    private static function formatAuthmePbkdf2(array $parts): string
    {
        return 'pbkdf2_sha256$'.$parts[0].'$'.$parts[1].'$'.$parts[2];
    }
}
