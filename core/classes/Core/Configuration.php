<?php
/**
 * Allows easy read/write to configuration values for a module stored in the database.
 *
 * @package NamelessMC\Core
 * @author Partydragen
 * @version 2.0.0-pr8
 * @license MIT
 * @deprecated Use Util::getSetting and Util::setSetting with $module parameter instead
 */
class Configuration {

    private string $_module;

    public function __construct(string $module) {
        if ($module === 'Core') {
            throw new InvalidArgumentException('Configuration class should not be used for the Core module');
        }

        $this->_module = $module;
    }

    /**
     * Get a configuration value
     *
     * @param string $setting Setting name
     *
     * @return mixed The configuration value
     */
    public function get(string $setting) {
        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $this->_module) . '_settings';
        $data = DB::getInstance()->query("SELECT value FROM $table WHERE `name` = ?", [$setting]);
        if ($data->count()) {
            $results = $data->results();
            return $results[0]->value;
        }

        return null;
    }

    /**
     * Set configuration value
     *
     * @param string $setting Setting name
     * @param mixed $value New value
     */
    public function set(string $setting, $value): void {
        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $this->_module) . '_settings';
        DB::getInstance()->query("UPDATE $table SET `value` = ? WHERE `name` = ?", [
            $value,
            $setting,
        ]);
    }
}
