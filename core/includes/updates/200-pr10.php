<?php
// 2.0.0 pr-10 to 2.0.0 pr-11 updater
try {
    $db_engine = Config::get('mysql/engine');
} catch (Exception $e) {
    // unable to retrieve from config
    echo $e->getMessage() . '<br />';
}
if (!$db_engine || ($db_engine != 'MyISAM' && $db_engine != 'InnoDB'))
    $db_engine = 'InnoDB';

try {
    $db_charset = Config::get('mysql/charset');
} catch (Exception $e) {
    // unable to retrieve from config
    echo $e->getMessage() . '<br />';
}
if (!$db_charset || ($db_charset != 'utf8mb4' && $db_charset != 'latin1'))
    $db_charset = 'latin1';

// Drop old placeholder tables & re-create
try {
    DB::getInstance()->createQuery('DROP TABLE `nl2_users_placeholders`');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('DROP TABLE `nl2_placeholders_settings`');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    $queries->createTable('placeholders_settings', " `server_id` int(4) NOT NULL, `name` varchar(186) NOT NULL, `friendly_name` varchar(256) NULL DEFAULT NULL, `show_on_profile` tinyint(1) NOT NULL DEFAULT '1', `show_on_forum` tinyint(1) NOT NULL DEFAULT '1', `leaderboard` tinyint(1) NOT NULL DEFAULT '0', `leaderboard_title` varchar(36) NULL DEFAULT NULL, `leaderboard_sort` varchar(4) NOT NULL DEFAULT 'DESC'", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
    DB::getInstance()->createQuery('ALTER TABLE `nl2_placeholders_settings` ADD PRIMARY KEY(`server_id`, `name`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    $queries->createTable('users_placeholders', ' `server_id` int(4) NOT NULL, `uuid` varbinary(16) NOT NULL, `name` varchar(186) NOT NULL, `value` TEXT NOT NULL, `last_updated` int(11) NOT NULL', "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_placeholders` ADD PRIMARY KEY(`server_id`, `uuid`, `name`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// New indexes - kept in from previous release (forgot to add them to site initialisation!)
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_groups` ADD INDEX `nl2_groups_idx_staff` (`staff`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users` ADD INDEX `nl2_users_idx_id_last_online` (`id`,`last_online`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_groups` ADD INDEX `nl2_users_groups_idx_group_id` (`group_id`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_groups` ADD INDEX `nl2_users_groups_idx_user_id` (`user_id`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_posts` ADD INDEX `nl2_posts_idx_topic_id` (`topic_id`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Drop extra column from query results
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_query_results` DROP COLUMN `extra`');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

if (count($version_number_id)) {
    $version_number_id = $version_number_id[0]->id;
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr11'
    ));
} else {
    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
    $version_number_id = $version_number_id[0]->id;

    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr11'
    ));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
