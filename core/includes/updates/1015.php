<?php
// 1.0.15 -> 1.0.16 updater
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Add icon column to custom tables
try {
	$queries->alterTable('custom_pages', 'icon', "varchar(64) DEFAULT NULL");
} catch(Exception $e){
	// Error, continue anyway
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.16'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));