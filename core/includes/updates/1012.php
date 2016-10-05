<?php
// 1.0.12 -> 1.0.13 updater

// Add 'deleted' column to posts
try {
	$queries->alterTable('posts', 'deleted', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
	// Error, continue anyway
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.13'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));