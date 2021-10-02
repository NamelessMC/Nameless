<?php
// 2.0.0 pr-9 to 2.0.0 pr-10 updater
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

try {
    DB::getInstance()->createQuery('ALTER TABLE nl2_query_results MODIFY groups TEXT');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->addPermissionGroup(2, 'admincp.security.group_sync');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    $recaptcha_type = DB::getInstance()->query('SELECT id FROM nl2_settings WHERE `name` = ? AND `value` = ?', array('recaptcha_type', 'reCaptcha'));
    if ($recaptcha_type->count()) {
        $configuration->set('Core', 'recaptcha_type', 'Recaptcha2');
    }
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// New indexes
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_groups` ADD INDEX `nl2_groups_idx_staff` (`staff`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_groups` ADD INDEX `nl2_groups_idx_id` (`id`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users` ADD INDEX `nl2_users_idx_id_last_online` (`id`,`last_online`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_groups` ADD INDEX `nl2_users_groups_idx_group_id` (`group_id`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_groups` ADD INDEX `nl2_users_groups_idx_user_id` (`user_id`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// plugin -> website placeholders
try {
    DB::getInstance()->createTable('users_placeholders', ' `server_id` int(11) NOT NULL, `uuid` varchar(32) NOT NULL, `name` varchar(256) NOT NULL, `value` TEXT NOT NULL, `last_updated` int(11) NOT NULL', "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
    DB::getInstance()->createTable('placeholders_settings', " `server_id` int(11) NOT NULL, `name` varchar(256) NOT NULL, `friendly_name` varchar(256) NULL DEFAULT NULL, `show_on_profile` tinyint(1) NOT NULL DEFAULT '1', `show_on_forum` tinyint(1) NOT NULL DEFAULT '1', `leaderboard` tinyint(1) NOT NULL DEFAULT '0', `leaderboard_title` varchar(36) NULL DEFAULT NULL, `leaderboard_sort` varchar(4) NOT NULL DEFAULT 'DESC'", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");

    $queries->addPermissionGroup(2, 'admincp.core.placeholders');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_placeholders` ADD PRIMARY KEY(`server_id`, `uuid`, `name`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_placeholders_settings` ADD PRIMARY KEY(`server_id`, `name`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// announcement ordering
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_custom_announcements` ADD `order` int(11) NOT NULL');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Captcha
$captcha = $queries->getWhere('settings', array('name', '=', 'recaptcha'));
$captcha_login = $queries->getWhere('settings', array('name', '=', 'recaptcha_login'));
if ($captcha[0]->value == 'true' || $captcha_login[0]->value == 'true') {
    try {
        Config::set('core/captcha', true);
    } catch (Exception $e) {
        echo $e->getMessage() . '<br />';
    }
}

// Force HTTPS
$cache->setCache('force_https_cache');
if ($cache->isCached('force_https')) {
    $force_https = $cache->retrieve('force_https');
    if ($force_https == 'true') {
        try {
            Config::set('core/force_https', true);
        } catch (Exception $e) {
            echo $e->getMessage() . '<br />';
        }
    }
}

// Force WWW
$cache->setCache('force_www_cache');
if ($cache->isCached('force_www')) {
    $force_www = $cache->retrieve('force_www');
    if ($force_www == 'true') {
        try {
            Config::set('core/force_www', true);
        } catch (Exception $e) {
            echo $e->getMessage() . '<br />';
        }
    }
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

if (count($version_number_id)) {
    $version_number_id = $version_number_id[0]->id;
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr10'
    ));
} else {
    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
    $version_number_id = $version_number_id[0]->id;

    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr10'
    ));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
