<?php

// str_starts_with() is PHP 8, polyfill for PHP 7.4 compatibility
// source: Laravel Framework
// https://github.com/laravel/framework/blob/8.x/src/Illuminate/Support/Str.php
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

class IntegrityChecker {

    const CHECKSUMS_PATH = ROOT_PATH . '/checksums.txt';

    /**
     * Files with relative paths starting with a string in this array are ignored
     */
    const IGNORED_PATHS = [
        'checksums.txt',
        'cache/',
        'cache/logs/',
        'cache/templates_c/',
        'templates/', # The default template is included again, below
        'modules/', # Default modules are included again, below
        'uploads/',
    ];

    /**
     * Override a path within an ignored path to be included in the scan
     */
    // TODO: Have constants for default template and module names somewhere, so it doesn't have to be hardcoded here (and in other code)
    const INCLUDED_PATHS = [
        'cache/.htaccess',
        'templates/DefaultRevamp',
        'modules/Cookie Consent',
        'modules/Core',
        'modules/Discord Integration',
        'modules/Forum',
    ];

    private static function is_ignored($path): bool {
        foreach (self::INCLUDED_PATHS as $include) {
            if (str_starts_with($path, $include)) {
                return false;
            }
        }

        foreach (self::IGNORED_PATHS as $ignore) {
            if (str_starts_with($path, $ignore)) {
                return true;
            }
        }

        return false;
    }

    public static function generate_checksums(): array {
        $checksums_dict = [];

        // Iterate over all files, recursively
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOT_PATH));

        foreach ($iterator as $path) {
            // Get path relative to Nameless root directory
            $relative_path = substr($path, strlen(ROOT_PATH) + 1);

            if (self::is_ignored($relative_path)) {
                continue;
            }

            if (is_dir($path)) {
                continue;
            }

            // Calculate SHA-256 hash for file
            $hash = hash_file('sha256', $path);

            $checksums_dict[$relative_path] = $hash;
        }

        return $checksums_dict;
    }

    public static function save_checksums(array $checksums) {
        $json = json_encode($checksums);
        file_put_contents(self::CHECKSUMS_PATH, $json);
    }

    public static function load_checksums(): array {
        $json = file_get_contents(self::CHECKSUMS_PATH);
        return json_decode($json, true);
    }

    public static function verify_checksums(): array {
        $errors = [];

        $actual_checksums = self::generate_checksums();
        $expected_checksums = self::load_checksums();

        foreach ($actual_checksums as $path => $checksum) {
            if (!array_key_exists($path, $expected_checksums)) {
                $errors[] = 'Extra file: ' . $path;
                continue;
            }

            if ($checksum !== $expected_checksums[$path]) {
                $errors[] = 'Checksum mismatch: ' . $path;
            }
        }

        foreach ($expected_checksums as $path => $checksum) {
            if (!array_key_exists($path, $actual_checksums)) {
                $errors[] = 'Missing file: ' . $path;
            }
        }

        return $errors;
    }

}
