<?php
// 2.0.0 pr-5 to 2.0.0 pr-6 updater
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

// Leftover from pr5, just in case
try {
	$queries->alterTable('groups', '`order`', "int(11) NOT NULL DEFAULT '1'");
} catch(Exception $e){
	// unable to update table
}

// Topic placeholders
try {
	$queries->alterTable('forums', '`topic_placeholder`', 'mediumtext');
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

// Panel templates
try {
	$queries->createTable("panel_templates", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '0', `is_default` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

try {
	$queries->create('panel_templates', array(
		'name' => 'Default',
		'enabled' => 1,
		'is_default' => 1
	));
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

// Permissions
$existing_user_permissions = $queries->getWhere('groups', array('id', '=', 1));
$existing_user_permissions = $existing_user_permissions[0]->permissions;
$existing_user_permissions = json_decode($existing_user_permissions, true);
$existing_user_permissions['usercp.profile_banner'] = 1;

$existing_mod_permissions = $queries->getWhere('groups', array('id', '=', 3));
$existing_mod_permissions = $existing_mod_permissions[0]->permissions;
$existing_mod_permissions = json_decode($existing_mod_permissions, true);
$existing_mod_permissions['modcp.profile_banner_reset'] = 1;
$existing_mod_permissions['usercp.profile_banner'] = 1;

$existing_admin_permissions = $queries->getWhere('groups', array('id', '=', 2));
$existing_admin_permissions = $existing_admin_permissions[0]->permissions;
$existing_admin_permissions = json_decode($existing_admin_permissions, true);
$existing_admin_permissions['admincp.styles.panel_templates'] = 1;
$existing_admin_permissions['modcp.profile_banner_reset'] = 1;
$existing_admin_permissions['usercp.profile_banner'] = 1;

try {
	$queries->update('groups', 1, array(
		'permissions' => json_encode($existing_user_permissions)
	));
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

try {
	$queries->update('groups', 2, array(
		'permissions' => json_encode($existing_admin_permissions)
	));
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

try {
	$queries->update('groups', 3, array(
		'permissions' => json_encode($existing_mod_permissions)
	));
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

// Revamp template
try {
	$revamp_template_exists = $queries->getWhere('templates', array('name', '=', 'DefaultRevamp'));

	if(!count($revamp_template_exists)){
		$queries->create('templates', array(
			'name' => 'DefaultRevamp',
			'enabled' => 0,
			'is_default' => 0
		));
	}
} catch(Exception $e){
	echo $e->getMessage() . '<br />';
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
$version_number_id = $version_number_id[0]->id;

if(count($version_number_id)){
	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr6'
	));
} else {
	$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
	$version_number_id = $version_number_id[0]->id;

	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr6'
	));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));