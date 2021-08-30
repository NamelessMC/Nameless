<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Config class
 */

class Config {

    /**
     * Get a config value from `core/config.php` file.
     * 
     * @param string $path `/` seperated path of key to get from config file.
     */
    public static function get($path = null) {
        if ($path) {
            if (!isset($GLOBALS['config'])) {
                throw new Exception('Config unavailable. Please refresh the page.');
            }

            $config = $GLOBALS['config'];

            $path = explode('/', $path);

            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                } else {
                    $not_matched = true;
                }
            }

            if (!isset($not_matched)) return $config;
        }

        return false;
    }

    /**
     * Write a value to `core/config.php` file.
     * 
     * @param string $key `/` seperated path of key to set.
     * @param mixed $value Value to set under $key.
     */
    public static function set($key, $value) {
        if (!file_exists(ROOT_PATH . '/core/config.php')) {
            fopen(ROOT_PATH . '/core/config.php', 'w');
        }

        require(ROOT_PATH . '/core/config.php');

        if (!isset($conf) || !is_array($conf)) {
            $conf = [];
        }

        $path = explode('/', $key);

        if (!is_array($path)) {
            $conf[$key] = $value;
        } else {
            $loc = &$conf;
            foreach($path as $step) {
                $loc = &$loc[$step];
            }
            $loc = $value;
        }

        return static::write($conf);
    }

    /**
     * Write multiple values to `core/config.php` file.
     *
     * @param array $values Array of key/value pairs
     */
    public static function setMultiple($values) {
        if (!file_exists(ROOT_PATH . '/core/config.php')) {
            fopen(ROOT_PATH . '/core/config.php', 'w');
        }

        require(ROOT_PATH . '/core/config.php');

        if (!isset($conf) || !is_array($conf)) {
            $conf = [];
        }

        foreach ($values as $key => $value) {
            $path = explode('/', $key);

            if (!is_array($path)) {
                $conf[$key] = $value;
            } else {
                $loc = &$conf;
                foreach($path as $step) {
                    $loc = &$loc[$step];
                }
                $loc = $value;
            }
        }

        return static::write($conf);
    }

    /**
     * Overwrite new `core/config.php` file.
     * 
     * @param array $config New config array to store.
     */
    public static function write($config) {
        $file = fopen(ROOT_PATH . '/core/config.php', 'wa+');
        fwrite($file, '<?php' . PHP_EOL . '$conf = ' . var_export($config, true) . ';' . PHP_EOL . '$CONFIG[\'installed\'] = true;');
        return fclose($file);
    }
}
