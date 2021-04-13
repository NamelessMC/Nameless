<?php
/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Configuration class
 */

class Configuration {

    /** @var DB */
    private $_db;
    
    /** @var Cache */
    private $_cache;

    public function __construct($cache){
        $this->_db = DB::getInstance();
        $this->_cache = $cache;
    }

    /**
     * Get configuration value
     *
     * @param string $module  Module name
     * @param string $setting Setting name
     *
     * @return mixed Configuration value
     */
    public function get($module, $setting) {
        if ($module == null || $setting == null) {
            throw new InvalidArgumentException('Parameter is null');
        }

        $module = ($module == 'Core' ? '' : $module . '_');

        $this->_cache->setCache($module . 'configuration');
        if ($this->_cache->isCached($setting)) {
            return $this->_cache->retrieve($setting);
        } else {
            $data = $this->_db->query('SELECT value FROM `nl2_'. Output::getClean($module) .'settings` WHERE `name` = ?', array($setting));
            if ($data->count()) {
                $results = $data->results();
                $this->_cache->store($setting, $results[0]->value);
                return $results[0]->value;
            }
        }
    }

    /**
     * Set configuration value
     *
     * @param string $module  Module name
     * @param string $setting Setting name
     * @param mixed  $value   New value
     *
     * @return void
     */
    public function set($module, $setting, $value) {
        if ($module == null || $setting == null || $value === null ) {
            throw new InvalidArgumentException('Parameter is null');
        }

        $module = ($module == 'Core' ? '' : $module . '_');

        $this->_db->createQuery(
            'UPDATE `nl2_'. Output::getClean($module) .'settings` SET `value` = ? WHERE `name` = ?',
            array(
                $value,
                $setting
            )
        );

        $this->_cache->setCache($module . 'configuration');
        $this->_cache->store($setting, $value);
    }
}
