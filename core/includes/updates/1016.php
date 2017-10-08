<?php
// 1.0.16 -> 1.0.17 updater
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Name History toggler
try {
	$name_history_exists = $queries->getWhere('settings', array('name', '=', 'enable_name_history'));
	if(!count($name_history_exists)){
		$queries->create('settings', array(
			'name' => 'enable_name_history',
			'value' => 1
		));
	}
} catch(Exception $e){
	// Error
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.17'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));