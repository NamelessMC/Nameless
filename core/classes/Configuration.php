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
	private $_db,
			$_cache;

	public function __construct($cache){
		$this->_db = DB::getInstance();
		$this->_cache = $cache;
	}
	
	// Get configuration value
	// Params: 	$module (string) - name of the module (required)
	//			$setting (string) - setting to retrieve value for (required)
	public function get($module, $setting) {
		if($module == null || $setting == null) {
			return false;
		}
		
		$module = ($module == 'Core' ? '' : $module . '_');
		
		$this->_cache->setCache($module . 'configuration');
		if($this->_cache->isCached($setting)){
			return $this->_cache->retrieve($setting);
		} else {
			$data = $this->_db->query('SELECT value FROM `nl2_'. $module .'settings` WHERE `name` = ?', array($setting));
			if($data->count()){
				$results = $data->results();
				$this->_cache->store($setting, $results[0]->value);
				return $results[0]->value;
			}
		}
	}
	
	// Update configuration value
	// Params: 	$module (string) - name of the module (required)
	//			$setting (string) - setting to update (required)
	//			$value (string) - value to set in the setting (required)
	public function set($module, $setting, $value) {
		if($module == null || $setting == null || $value == null ) {
			return false;
		}
		
		$module = ($module == 'Core' ? '' : $module . '_');
		
		$this->_db->createQuery('UPDATE `nl2_'. $module .'settings` SET `value` = ? WHERE `name` = ?', array(
			$value,
			$setting
        ));
		
		$this->_cache->setCache($module . 'configuration');
		$this->_cache->store($setting, $value);
		return true;
	}
}