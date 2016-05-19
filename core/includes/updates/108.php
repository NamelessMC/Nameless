<?php
// 1.0.8 -> 1.0.9 updater

// Database changes:
// Private messages
$pm_update = $queries->tableExists('private_messages_replies');
if(empty($pm_update)){
	// Make the changes
	$data = $queries->createTable("private_messages_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `pm_id` int(11) NOT NULL, `content` mediumtext NOT NULL, `user_id` int(11) NOT NULL, `created` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	echo '<strong>Private Messages Replies</strong> table successfully initialised<br />';
	
	$queries->alterTable('private_messages', 'updated', "int(11) NOT NULL");
	echo '<strong>Private Messages</strong> table successfully updated<br />';
}

// Two Factor Authentication
$queries->alterTable('users', 'tfa_enabled', "tinyint(1) NOT NULL DEFAULT '0'");
$queries->alterTable('users', 'tfa_type', "int(11) NOT NULL DEFAULT '0'");
$queries->alterTable('users', 'tfa_secret', "varchar(256) DEFAULT NULL");
$queries->alterTable('users', 'tfa_complete', "tinyint(1) NOT NULL DEFAULT '0'");

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.9'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));