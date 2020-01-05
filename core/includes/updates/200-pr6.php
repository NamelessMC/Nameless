<?php
// 2.0.0 pr-6 to 2.0.0 pr-7 updater
try {
	$db_engine = Config::get('mysql/engine');
} catch(Exception $e){
	// unable to retrieve from config
	echo $e->getMessage() . '<br />';
}
if(!$db_engine || ($db_engine != 'MyISAM' && $db_engine != 'InnoDB'))
	$db_engine = 'InnoDB';

try {
	$db_charset = Config::get('mysql/charset');
} catch(Exception $e){
	// unable to retrieve from config
	echo $e->getMessage() . '<br />';
}
if(!$db_charset || ($db_charset != 'utf8mb4' && $db_charset != 'latin1'))
	$db_charset = 'latin1';

// Minecraft servers - show_ip
try {
	$queries->alterTable('mc_servers', '`show_ip`', "tinyint(1) NOT NULL DEFAULT '1'");
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

// Users - avatar_updated
try {
	$queries->alterTable('users', '`avatar_updated`', "int(11) DEFAULT NULL");
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

// Online guests
try {
	$queries->createTable("online_guests", " `id` int(11) NOT NULL AUTO_INCREMENT, `ip` varchar(45) NOT NULL, `last_seen` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch(Exception $e){
	// unable to create table
	echo $e->getMessage() . '<br />';
}

// Login reCAPTCHA
$recaptcha_check = $queries->getWhere('settings', array('name', '=', 'recaptcha_login'));

if(!count($recaptcha_check)){
	$queries->create('settings', array('name' => 'recaptcha_login', 'value' => 'false'));
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

if(count($version_number_id)){
	$version_number_id = $version_number_id[0]->id;
	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr7'
	));
} else {
	$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
	$version_number_id = $version_number_id[0]->id;

	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr7'
	));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));