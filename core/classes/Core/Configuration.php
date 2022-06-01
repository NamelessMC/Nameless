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

    private string $module;

    public function __construct(string $module) {
        if ($module === 'Core') {
            throw new InvalidArgumentException('Configuration class should not be used for the Core module');
        }

        $this->module = $module;
    }

    /**
     * Get a configuration value
     *
     * @param string $module Module name
     * @param string $setting Setting name
     *
     * @return mixed The configuration value
     */
    public function get(string $setting) {
        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $this->module) . 'settings';
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
    public function set(string $setting, $value): void {
        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $this->module) . 'settings';
        $this->_db->query("UPDATE $table SET `value` = ? WHERE `name` = ?", [
            $value,
            $setting,
        ]);
    }
}
