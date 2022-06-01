<?php
/**
 * Allows easy read/write to configuration values for a module stored in the database.
 *
 * @package NamelessMC\Core
 * @author Partydragen
 * @version 2.0.0-pr8
 * @license MIT
 */
class Configuration {

    private static Configuration $_instance;

    private DB $_db;

    public function __construct() {
        $this->_db = DB::getInstance();

        self::$_instance = $this;
    }

    public static function getInstance(): Configuration {
        return self::$_instance;
    }

    /**
     * Get a configuration value
     *
     * @param string $module Module name
     * @param string $setting Setting name
     *
     * @return mixed The configuration value
     */
    public function get(string $module, string $setting) {
        if ($module === 'Core') {
            throw new InvalidArgumentException('Configuration class should not be used for the Core module');
        }

        $module = $module . '_';

        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $module) . 'settings';
        $data = $this->_db->query("SELECT value FROM $table WHERE `name` = ?", [$setting]);
        if ($data->count()) {
            $results = $data->results();
            $this->_cache->store($setting, $results[0]->value);
            return $results[0]->value;
        }

        return null;
    }

    /**
     * Set configuration value
     *
     * @param string $module Module name
     * @param string $setting Setting name
     * @param mixed $value New value
     */
    public function set(string $module, string $setting, $value): void {
        if ($module === 'Core') {
            throw new InvalidArgumentException('Configuration class should not be used for the Core module');
        }

        $module = $module . '_';

        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $module) . 'settings';
        $this->_db->query("UPDATE $table SET `value` = ? WHERE `name` = ?", [
            $value,
            $setting,
        ]);
    }
}
