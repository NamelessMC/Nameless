<?php
// 2.0.0 pr-1 to 2.0.0 pr-2 updater

// Database changes
try {
    DB::getInstance()->addColumn('custom_pages', 'icon', "varchar(64) DEFAULT NULL");
} catch (Exception $e) {
    // Error, may have already been created - continue anyway
}

try {
    DB::getInstance()->createTable("forums_labels", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) NOT NULL, `html` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch (Exception $e) {
    // Error
}

try {
    DB::getInstance()->addColumn('forums_topic_labels', 'gids', "varchar(64) DEFAULT NULL");
} catch (Exception $e) {
    // Error
}

try {
    DB::getInstance()->createTable("mc_servers", " `id` int(11) NOT NULL AUTO_INCREMENT, `ip` varchar(64) NOT NULL, `query_ip` varchar(64) NOT NULL, `name` varchar(20) NOT NULL, `is_default` tinyint(1) NOT NULL DEFAULT '0', `display` tinyint(1) NOT NULL DEFAULT '1', `pre` tinyint(1) NOT NULL DEFAULT '0', `player_list` tinyint(1) NOT NULL DEFAULT '1', `parent_server` int(11) NOT NULL DEFAULT '0', `bungee` tinyint(1) NOT NULL DEFAULT '0', `port` int(11) DEFAULT NULL, `query_port` int(11) DEFAULT '25565', `banner_background` varchar(32) NOT NULL DEFAULT 'background.png', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch (Exception $e) {
    // Error
    try {
        DB::getInstance()->addColumn('mc_servers', 'banner_background', "varchar(32) NOT NULL DEFAULT 'background.png'");
    } catch (Exception $ex) {
        // Error
    }
}

try {
    DB::getInstance()->createTable("email_errors", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `content` text NOT NULL, `at` int(11) NOT NULL, `user_id` int(11) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
} catch (Exception $e) {
    // Error
}

// New settings
$update_check = DB::getInstance()->get('settings', array('name', 'maintenance_message'))->results();
if (!count($update_check)) {
    $queries->create('settings', array(
        'name' => 'maintenance_message',
        'value' => 'This website is currently in maintenance mode.'
    ));
    $cache->setCache('maintenance_cache');
    $cache->store('maintenance', array('maintenance' => 'false', 'message' => 'This website is currently in maintenance mode.'));
}

$update_check = DB::getInstance()->get('settings', array('name', 'authme'))->results();
if (!count($update_check)) {
    $queries->create('settings', array(
        'name' => 'authme',
        'value' => 0
    ));
}

$update_check = DB::getInstance()->get('settings', array('name', 'authme_db'))->results();
if (!count($update_check)) {
    $queries->create('settings', array(
        'name' => 'authme_db',
        'value' => null
    ));
}

$update_check = DB::getInstance()->get('settings', array('name', 'force_https'))->results();
if (!count($update_check)) {
    $queries->create('settings', array(
        'name' => 'force_https',
        'value' => 'false'
    ));
}

$update_check = DB::getInstance()->get('settings', array('name', 'default_avatar_type'))->results();
if (!count($update_check)) {
    $queries->create('settings', array(
        'name' => 'default_avatar_type',
        'value' => 'minecraft'
    ));
}

$update_check = DB::getInstance()->get('settings', array('name', 'custom_default_avatar'))->results();
if (!count($update_check)) {
    $queries->create('settings', array(
        'name' => 'custom_default_avatar',
        'value' => null
    ));
}

$update_check = null;

// Update version number
$version_number_id = DB::getInstance()->get('settings', array('name', 'nameless_version'))->results();
$version_number_id = $version_number_id[0]->id;

if (count($version_number_id)) {
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr2'
    ));
} else {
    $version_number_id = DB::getInstance()->get('settings', array('name', 'version'))->results();
    $version_number_id = $version_number_id[0]->id;

    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr2'
    ));
}

$version_update_id = DB::getInstance()->get('settings', array('name', 'version_update'))->results();
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
