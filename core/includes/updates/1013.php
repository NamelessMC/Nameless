<?php
// 1.0.13 -> 1.0.14 updater
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Reports columns update
try {
	$queries->alterTable('reports', 'reported_mcname', "varchar(64) DEFAULT NULL");
	$queries->alterTable('reports', 'reported_uuid', "varchar(64) DEFAULT NULL");
} catch(Exception $e){
	// Error, continue anyway
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.14'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));