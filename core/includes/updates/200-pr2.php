<?php
// 2.0.0 pr-2 to 2.0.0 pr-3 updater

// Database changes
try {
	$queries->createTable("blocked_users", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `user_blocked_id` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch(Exception $e){

}

try {
	$queries->alterTable('custom_pages', 'all_html', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
	// Error, may have already been created - continue anyway
}

try {
	$queries->alterTable('infractions', 'revoked', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
	// Error
}

try {
	$queries->alterTable('infractions', 'revoked_by', "int(11) DEFAULT NULL");
} catch(Exception $e){
	// Error
}

try {
	$queries->alterTable('infractions', 'revoked_at', "int(11) DEFAULT NULL");
} catch(Exception $e){
	// Error
}

try {
	$queries->createTable("query_results", " `id` int(11) NOT NULL AUTO_INCREMENT, `server_id` int(11) NOT NULL, `queried_at` int(11) NOT NULL, `players_online` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch(Exception $e){
	// Error
}

try {
	$queries->createTable("widgets", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '0', `pages` text, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch(Exception $e){
	// Error
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
$version_number_id = $version_number_id[0]->id;

if(count($version_number_id)){
	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr3'
	));
} else {
	$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
	$version_number_id = $version_number_id[0]->id;

	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr3'
	));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));