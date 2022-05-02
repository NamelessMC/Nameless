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

    private DB $_db;

    private Cache $_cache;

    public function __construct(Cache $cache) {
        $this->_db = DB::getInstance();
        $this->_cache = $cache;
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
        if ($module == null || $setting == null) {
            throw new InvalidArgumentException('Parameter is null');
        }

        $module = ($module == 'Core' ? '' : $module . '_');

        $this->_cache->setCache($module . 'configuration');
        if ($this->_cache->isCached($setting)) {
            return $this->_cache->retrieve($setting);
        }

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
        if ($module == null || $setting == null || $value === null) {
            throw new InvalidArgumentException('Parameter is null');
        }

        $module = ($module == 'Core' ? '' : $module . '_');

        $table = 'nl2_' . preg_replace('/[^A-Za-z0-9_]+/', '', $module) . 'settings';
        $this->_db->query("UPDATE $table SET `value` = ? WHERE `name` = ?", [
            $value,
            $setting,
        ]);

        $this->_cache->setCache($module . 'configuration');
        $this->_cache->store($setting, $value);
    }
}
