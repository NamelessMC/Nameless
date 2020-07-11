<?php
// 2.0.0 pr-7 to 2.0.0 pr-8 updater
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

// Edit Topics forum permission
try {
    $queries->alterTable('forum_permissions', '`edit_topic`', "tinyint(1) NOT NULL DEFAULT '0'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Custom pages basic setting
try {
    $queries->alterTable('custom_pages', '`basic`', "tinyint(1) NOT NULL DEFAULT '0'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Topic Updates 
try {
    $queries->alterTable('users', '`topic_updates`', "tinyint(1) NOT NULL DEFAULT '1'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Announcements
try {
    DB::getInstance()->query("CREATE TABLE `nl2_custom_announcements` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pages` varchar(128) NOT NULL,
        `groups` varchar(128) NOT NULL,
        `text_colour` varchar(7) NOT NULL,
        `background_colour` varchar(7) NOT NULL,
        `icon` varchar(64) NOT NULL,
        `closable` tinyint(1) NOT NULL DEFAULT '0',
        `header` varchar(64) NOT NULL,
        `message` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=$db_engine AUTO_INCREMENT=17 DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}