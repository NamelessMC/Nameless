<?php
// 1.0.10 -> 1.0.11 updater

// Database changes
// Drop private messages content column
try {
	$queries->removeColumn('private_messages', 'content');
} catch(Exception $e){
	// Unable to drop column
}

// Avatar type
$queries->create('settings', array(
	'name' => 'avatar_type',
	'value' => 'helmavatar'
));

// Name history
try {
	$data = $queries->createTable("users_username_history", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `changed_to` varchar(64) NOT NULL, `changed_at` int(11) NOT NULL, `original` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Users username history</strong> table successfully initialised<br />';
} catch(Exception $e){
	// Unable to add table
}

// Custom pages redirect links
try {
	$queries->alterTable('custom_pages', 'redirect', "tinyint(1) NOT NULL DEFAULT '0'");
	$queries->alterTable('custom_pages', 'link', "varchar(512) DEFAULT NULL");
} catch(Exception $e){
	// Unable to update table
}

// Custom pages permissions
try {
	$data = $queries->createTable("custom_pages_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `page_id` int(11) NOT NULL, `group_id` int(11) NOT NULL, `view` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Custom pages permissions</strong> table successfully initialised<br />';
} catch(Exception $e){
	// Unable to add table
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.11'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));