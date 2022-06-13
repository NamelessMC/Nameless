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

        require(ROOT_PATH . '/core/config.php');

        /** @phpstan-ignore-next-line  */
        if (!isset($conf) || !is_array($conf)) {
            throw new RuntimeException('Config file is invalid');
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
        $contents = '<?php' . PHP_EOL . '$conf = ' . var_export($config, true) . ';';
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
     * @return false|mixed Returns false if key doesn't exist, otherwise returns the value.
     *
     * @throws RuntimeException If the config file is not found.
     */
    public static function get(string $path) {
        $config = self::all();

        $path = self::parsePath($path);

        foreach ($path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            } else {
                $not_matched = true;
            }
        }

        if (!isset($not_matched)) {
            return $config;
        }

        return false;
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
}
