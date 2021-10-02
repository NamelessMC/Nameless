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
    $queries->alterTable('forums_permissions', '`edit_topic`', "tinyint(1) NOT NULL DEFAULT '0'");
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

// Discord Integration
try {
    DB::getInstance()->createQuery("CREATE TABLE `nl2_discord_verifications` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `token` varchar(23) NOT NULL,
        `user_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->create('settings', array(
        'name' => 'discord_integration',
        'value' => 0,
    ));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->create('settings', array(
        'name' => 'discord_bot_url',
        'value' => null
    ));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->create('settings', array(
        'name' => 'discord_bot_username',
        'value' => null
    ));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('group_sync', '`discord_role_id`', "bigint(18) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('users', '`discord_id`', "bigint(18) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('users', '`discord_username` ', "varchar(128) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->addPermissionGroup(2, 'admincp.discord');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->addPermissionGroup(2, 'admincp.security.discord');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// New group system
try {
    $queries->createTable("users_groups", " `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `group_id` int(11) NOT NULL, `received` int(11) NOT NULL DEFAULT '0', `expire` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('groups', '`deleted`', "tinyint(1) NOT NULL DEFAULT '0'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Announcements

// Reset panel_sidebar cache so that the orders do not interfere on upgrade
try {
    unlink(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('panel_sidebar') . '.cache');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    DB::getInstance()->createQuery("CREATE TABLE `nl2_custom_announcements` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pages` varchar(1024) NOT NULL,
        `groups` varchar(1024) NOT NULL,
        `text_colour` varchar(7) NOT NULL,
        `background_colour` varchar(7) NOT NULL,
        `icon` varchar(64) NOT NULL,
        `closable` tinyint(1) NOT NULL DEFAULT '0',
        `header` varchar(64) NOT NULL,
        `message` varchar(1024) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->addPermissionGroup(2, 'admincp.core.announcements');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Group Username Colour + Group CSS
try {
    DB::getInstance()->createQuery("ALTER TABLE `nl2_groups` CHANGE `group_username_css` `group_username_color` VARCHAR(256) CHARACTER SET $db_charset NULL DEFAULT NULL;");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('groups', '`group_username_css`', "varchar(256) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Multiple webhooks
try {
    if (!empty($queries->tableExists('hooks'))) {
        $queries->alterTable('hooks', '`name`', "varchar(128) NULL DEFAULT NULL");
    } else {
        $queries->createTable("hooks", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(128) NOT NULL, `action` int(11) NOT NULL, `url` varchar(2048) NOT NULL, `events` varchar(2048) NOT NULL, PRIMARY KEY (`id`)", "");
    }
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->delete('settings', array('name', '=', 'forum_new_topic_hooks'));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('forums', '`hooks`', "varchar(512) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->addPermissionGroup(2, 'admincp.core.hooks');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}


// Force group TFA
try {
    $queries->alterTable('groups', '`force_tfa`', "tinyint(1) NOT NULL DEFAULT '0'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Email Mass Message
try {
    $queries->addPermissionGroup(2, 'admincp.core.emails_mass_message');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->addPermissionGroup(2, 'admincp.security.emails');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Widget Locations
try {
    $queries->alterTable('widgets', '`location`', "varchar(5) NOT NULL DEFAULT 'right'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Ingame group dropdown
try {
    $queries->alterTable('query_results', '`groups`', "varchar(256) NOT NULL DEFAULT '[]'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Labels
try {
    DB::getInstance()->createQuery("ALTER TABLE `nl2_forums_labels` CHANGE `html` `html` VARCHAR(1024) CHARACTER SET $db_charset NULL DEFAULT NULL;");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    DB::getInstance()->createQuery("ALTER TABLE `nl2_forums_labels` CHANGE `name` `name` VARCHAR(32) CHARACTER SET $db_charset NULL DEFAULT NULL;");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    DB::getInstance()->createQuery("ALTER TABLE `nl2_forums_topic_labels` CHANGE `gids` `gids` VARCHAR(256) CHARACTER SET $db_charset NULL DEFAULT NULL;");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Convert user groups
try {
    $users = DB::getInstance()->query('SELECT id, group_id, secondary_groups FROM nl2_users')->results();
    $query = 'INSERT INTO nl2_users_groups (user_id, group_id) VALUES ';
    foreach ($users as $item) {
        $inserts = array('(' . Output::getClean($item->id) . ',' . Output::getClean($item->group_id) . '),');
        $groups = json_decode($item->secondary_groups);
        if (count($groups)) {
            foreach ($groups as $group) {
                $inserts[] = '(' . Output::getClean($item->id) . ',' . Output::getClean($group) . '),';
            }
        }
        $query .= implode('', $inserts);
    }
    DB::getInstance()->createQuery(rtrim($query, ','));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Delete "group_id" from nl2_users table to prevent issues of it not being set
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users` DROP COLUMN `group_id`;');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

if (count($version_number_id)) {
    $version_number_id = $version_number_id[0]->id;
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr8'
    ));
} else {
    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
    $version_number_id = $version_number_id[0]->id;

    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr8'
    ));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
