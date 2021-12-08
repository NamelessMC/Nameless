<?php
// 2.0.0 pr-4 to 2.0.0 pr-5 updater
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

// New tables
try {
	$queries->createTable("topics_following", "`id` int(11) NOT NULL AUTO_INCREMENT, `topic_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `existing_alerts` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch(Exception $e){
	// unable to create table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->createTable("page_descriptions", " `id` int(11) NOT NULL AUTO_INCREMENT, `page` varchar(64) NOT NULL, `description` varchar(500) DEFAULT NULL, `tags` text, PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch(Exception $e){
	// unable to create table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->createTable("privacy_terms", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(8) NOT NULL, `value` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch(Exception $e){
	// unable to create table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->createTable("group_sync", " `id` int(11) NOT NULL AUTO_INCREMENT, `ingame_rank_name` varchar(64) NOT NULL, `website_group_id` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch(Exception $e){
	// unable to create table
	echo $e->getMessage() . '<br />';
}

// New columns
try {
	$queries->alterTable('profile_fields', 'editable', "tinyint(1) NOT NULL DEFAULT '1'");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('forums', 'icon', "varchar(256) DEFAULT NULL");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('custom_pages', 'sitemap', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('widgets', '`order`', "int(11) NOT NULL DEFAULT '10'");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('groups', '`order`', "int(11) NOT NULL DEFAULT '1'");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('infractions', 'created', "int(11) DEFAULT NULL");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('reports', 'reported', "int(11) DEFAULT NULL");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('reports', 'updated', "int(11) DEFAULT NULL");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	$queries->alterTable('reports_comments', 'date', "int(11) DEFAULT NULL");
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

try {
	DB::getInstance()->createQuery('ALTER TABLE nl2_alerts MODIFY content_short VARCHAR(128) NOT NULL', array());
} catch(Exception $e){
	// unable to update table
	echo $e->getMessage() . '<br />';
}

// Disable all modules + templates that aren't default/forum/default themes
$cache->setCache('modulescache');
$enabled_modules = $cache->retrieve('enabled_modules');

foreach($enabled_modules as $module){
	if($module['name'] == 'Forum'){
		$forum_exists = true;
		break;
	}
}

$enabled_modules = array(
	array(
		'name' => 'Core',
		'priority' => 1
	)
);

if(isset($forum_exists))
	$enabled_modules[] = array(
		'name' => 'Forum',
		'priority' => 4
	);

$cache->store('enabled_modules', $enabled_modules);
$cache->store('module_core', true);
$cache->store('module_forum', true);

$modules = $queries->getWhere('modules', array('enabled', '=', 1));

foreach($modules as $item){
	if($item->name != 'Core' && $item->name != 'Forum'){
		$queries->update('modules', $item->id, array('enabled' => 0));
	}
}

$cache->setCache('templatecache');
$cache->store('default', 'Default');

$default_template = $queries->getWhere('templates', array('is_default', '=', 1));
if($default_template[0]->name != 'Default'){
	$queries->update('templates', $default_template[0]->id, array(
		'is_default' => 0
	));
	$default_template = $queries->getWhere('templates', array('name', '=', 'Default'));
	$queries->update('templates', $default_template[0]->id, array(
		'is_default' => 1
	));
}

$enabled_templates = $queries->getWhere('templates', array('enabled', '=', 1));
foreach($enabled_templates as $template){
	if($template->name != 'Default'){
		$queries->update('templates', $template->id, array(
			'enabled' => 0
		));
	}
}

// New settings
$queries->create('settings', array(
	'name' => 'status_page',
	'value' => '1'
));

// Permissions
$queries->update('groups', 2, array(
	'permissions' => '{"admincp.core":1,"admincp.core.api":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.integrations":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.pages.metadata":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.sitemap":1,"admincp.styles":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"profile.private.bypass":1, "admincp.security.all":1}'
));

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
$version_number_id = $version_number_id[0]->id;

if(count($version_number_id)){
	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr5'
	));
} else {
	$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
	$version_number_id = $version_number_id[0]->id;

	$queries->update('settings', $version_number_id, array(
		'value' => '2.0.0-pr5'
	));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));