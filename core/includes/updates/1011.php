<?php
// 1.0.11 -> 1.0.12 updater

// Settings
// MCAssoc
$queries->create('settings', array(
	'name' => 'use_mcassoc',
	'value' => '0'
));

$queries->create('settings', array(
	'name' => 'mcassoc_key',
	'value' => 'null'
));

// Twitter style
$queries->create('settings', array(
	'name' => 'twitter_style',
	'value' => 'light'
));

// Announcements
try {
	$data = $queries->createTable("announcements", " `id` int(11) NOT NULL AUTO_INCREMENT, `content` mediumtext NOT NULL, `can_close` tinyint(1) NOT NULL DEFAULT '0', `type` varchar(16) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	$data = $queries->createTable("announcements_pages", " `id` int(11) NOT NULL AUTO_INCREMENT, `announcement_id` int(11) NOT NULL, `page` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
	$data = $queries->createTable("announcements_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `announcement_id` int(11) NOT NULL, `group_id` int(11) DEFAULT NULL, `user_id` int(11) DEFAULT NULL, `view` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch(Exception $e){
	// Error, continue anyway
}

// Modify donation addon package and description columns
try {
	$donation_exists = $queries->tableExists('donation_settings');
	if(!empty($donation_exists)){
		$queries->modifyColumn('donation_cache', 'package', 'varchar(64)');
		$queries->modifyColumn('donation_packages', 'description', 'MEDIUMTEXT');
	}
} catch(Exception $e){
	// Error, continue anyway
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.12'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));