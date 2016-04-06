<?php
// 1.0.6 -> 1.0.7 updater

// Database changes:
// Changes to User table
$queries->alterTable('users', 'last_username_update', "int(11) DEFAULT '0'");
$queries->alterTable('users', 'user_title', "varchar(64) DEFAULT NULL");
$queries->alterTable('users', 'birthday', "date DEFAULT NULL");
$queries->alterTable('users', 'location', "varchar(128) DEFAULT NULL");
$queries->alterTable('users', 'display_age', "tinyint(1) NOT NULL DEFAULT '1'");

// New user wall tables
$queries->createTable("user_profile_wall_posts", "  `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
$queries->createTable("user_profile_wall_posts_likes", " `id` int(11) NOT NULL AUTO_INCREMENT, `post_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
$queries->createTable("user_profile_wall_posts_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `post_id` int(11) NOT NULL, `author_id` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");

// Followers setting
$queries->create('settings', array(
	'name' => 'followers',
	'value' => '0'
));

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
$version_number_id = $version_number_id[0]->id;

$queries->update('settings', $version_number_id, array(
	'value' => '1.0.7'
));

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
	'value' => 'false'
));