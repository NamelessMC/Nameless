<?php

class IntegrityChecker {

    /**
     * Files with relative paths starting with a string in this array are ignored
     */
    const IGNORED_PATHS = [
        'cache/', # The htaccess file is included again, below
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

    /**
     * @return bool If the file should be ignored from integrity checking, according to IGNORED_PATHS and INCLUDED_PATHS.
     */
    private static function isIgnored($path): bool {
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

    private static function checksumsPath(): string {
        return ROOT_PATH . '/checksums.json';
    }

    /**
     * Generate checksums for files recursively, ignoring files according to IGNORED_PATHS and INCLUDED_PATHS.
     *
     * @return array An associative array (relative file path as key, checksum as value).
     */
    public static function generateChecksums(): array {
        $checksums_dict = [];

        // Iterate over all files, recursively
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOT_PATH));

        foreach ($iterator as $path) {
            // Get path relative to Nameless root directory
            $relative_path = substr($path, strlen(ROOT_PATH) + 1);

            if (self::isIgnored($relative_path)) {
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

    /**
     * Save checksum array to checksums file, in json format.
     *
     * @param array $checksums An associative array (relative file path as key, checksum as value).
     */
    public static function saveChecksums(array $checksums): void {
        $json = json_encode($checksums);
        file_put_contents(self::checksumsPath(), $json);
    }

    /**
     * Load checksums from checksums.json file into an associative array.
     *
     * @return array|null An associative array (relative file path as key, checksum as value) or null if the checksum
     * file does not exist.
     */
    public static function loadChecksums() {
        if (!is_file(self::checksumsPath())) {
            return null;
        }

        $json = file_get_contents(self::checksumsPath());
        return json_decode($json, true);
    }

    /**
     * Verify code integrity, by calculating checksums for files recursively, and comparing them to checksums in the
     * checksums file.
     *
     * @return array Array of errors strings, empty if no issues were found.
     */
    public static function verifyChecksums(): array {
        $errors = [];

        $expected_checksums = self::loadChecksums();

        if ($expected_checksums == null) {
            $errors[] = 'Checksums file is missing, integrity cannot be verified';
            return $errors;
        }

        $actual_checksums = self::generateChecksums();

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
            if (!self::isIgnored($path) && !array_key_exists($path, $actual_checksums)) {
                $errors[] = 'Missing file: ' . $path;
            }
        }

        return $errors;
    }

}
