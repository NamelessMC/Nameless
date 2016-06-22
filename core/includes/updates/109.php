<?php
// 1.0.9 -> 1.0.10 updater

// Database changes:
// Forum types
try {
	$queries->alterTable('forums', 'forum_type', "varchar(255) NOT NULL DEFAULT 'forum'");
} catch(Exception $e){
	// Unable to alter table, must already exist
}

// Convert avatar types from true/false to 1/0
$avatars = $queries->getWhere('settings', array('name', '=', 'user_avatars'));

if($avatars[0]->value == 'true') $update_avatars = '1';
else if($avatars[0]->value == 'false') $update_avatars = '0';

if(isset($update_avatars)) $queries->update('settings', $avatars[0]->id, array(
	'value' => $update_avatars
));

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.10'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));