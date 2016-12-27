<?php
// 1.0.14 -> 1.0.15 updater
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Update donation packages table
try {
	$donation_settings = $queries->tableExists('donation_settings');
	if(!empty($donation_settings))
		$queries->alterTable('donation_packages', 'custom_description', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
	// Error, continue anyway
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.15'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));