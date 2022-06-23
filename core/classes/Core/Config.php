<?php
/**
 * Provides static methods to get and set configuration values from the `core/config.php` file.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0
 * @license MIT
 */
class Config {

    private static ?array $_config_cache = null;

    /**
     * @return bool Whether config file exists
     */
    public static function exists(): bool {
        return file_exists(ROOT_PATH . '/core/config.php');
    }

    /**
     * Read `core/config.php` file and load into cache
     *
     * @return array The entire config array
     */
    public static function all(): array {
        if (self::$_config_cache !== null) {
            return self::$_config_cache;
        }

        if (!self::exists()) {
            throw new RuntimeException('Config file does not exist');
        }

        $config = require(ROOT_PATH . '/core/config.php');
        if ($config === 1) {
            // TODO: Legacy < 2.0.0 config file. Remove in 2.1.0
            /** @phpstan-ignore-next-line  */
            if (!isset($conf) || !is_array($conf)) {
                throw new RuntimeException('Config file is invalid');
            }
        } else {
            $conf = $config;
        }

        /** @phpstan-ignore-next-line  */
        return self::$_config_cache = $conf;
    }

    /**
     * Overwrite new `core/config.php` file.
     *
     * @param array $config New config array to store.
     */
    public static function write(array $config): void {
        $contents = '<?php' . PHP_EOL . 'return ' . self::arrayToString($config) . ';';
        if (file_put_contents(ROOT_PATH . '/core/config.php', $contents) === false) {
            throw new RuntimeException('Failed to write to config file');
        }
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate(ROOT_PATH . '/core/config.php', true);
        }
        self::$_config_cache = $config;
    }

    /**
     * Get a config value from `core/config.php` file.
     *
     * @param string $path `.` seperated path of key to get from config file.
     * @param mixed $fallback Value to return if option is not present in config file. If set to null, false is returned.
     * @return false|mixed Returns false if key doesn't exist, otherwise returns the value.
     *
     * @throws RuntimeException If the config file is not found.
     */
    public static function get(string $path, $fallback = null) {
        $config = self::all();

        $parsed_path = self::parsePath($path);

        if (!is_array($parsed_path)) {
            return $config[$parsed_path] ?? false;
        }

        foreach ($parsed_path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            } else {
                $not_matched = true;
            }
        }

        if (!isset($not_matched)) {
            return $config;
        }

        return $fallback ?? false;
    }

    /**
     * Write a value to `core/config.php` file.
     *
     * @param string $key `.` seperated path of key to set.
     * @param mixed $value Value to set under $key.
     */
    public static function set(string $key, $value): void {
        $config = self::all();

        $path = self::parsePath($key);

        if (!is_array($path)) {
            $config[$key] = $value;
        } else {
            $loc = &$config;
            foreach ($path as $step) {
                $loc = &$loc[$step];
            }
            $loc = $value;
        }

        static::write($config);
    }

    /**
     * Write multiple values to `core/config.php` file.
     *
     * @param array $values Array of key/value pairs
     */
    public static function setMultiple(array $values): void {
        $config = self::all();

        foreach ($values as $key => $value) {
            $path = self::parsePath($key);

            if (!is_array($path)) {
                $config[$key] = $value;
            } else {
                $loc = &$config;
                foreach ($path as $step) {
                    $loc = &$loc[$step];
                }
                $loc = $value;
            }
        }

        static::write($config);
    }

    /**
     * Parse a string path to an array of config paths.
     * Will log a warning if a legacy path (using `/` is used).
     *
     * @param string $path Path to parse.
     * @return string|array Path split into sections or plain string if no section seperator was found.
     */
    private static function parsePath(string $path) {
        if (str_contains($path, '.')) {
            return explode('.', $path);
        }

        // TODO: Remove for 2.1.0
        if (str_contains($path, '/')) {
            ErrorHandler::logWarning("Legacy config path: {$path}. Please use periods to seperate paths.");
            return explode('/', $path);
        }

        return $path;
    }

    /**
     * Converts an array to a string to be inserted into the config file, with shorthand array syntax.
     *
     * @link https://gist.github.com/Bogdaan/ffa287f77568fcbb4cffa0082e954022
     * @param array $config Config array to convert to string.
     * @return string PHP code for the config array
     */
    private static function arrayToString(array $config): string {
        $export = var_export($config, true);
        $export = preg_replace("/^(' '*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        return implode(PHP_EOL, array_filter(["["] + ($array ?: [])));
    }
}
