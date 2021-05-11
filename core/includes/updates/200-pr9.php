<?php
// 2.0.0 pr-9 to 2.0.0 pr-10(?) updater
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
        $id = $recaptcha_type->first()->id;
        DB::getInstance()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE id = ?', array('Recaptcha2', $id));
    }
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// plugin -> website placeholders
try {
    DB::getInstance()->createTable('users_placeholders', '`sever_id` int(11) NOT NULL, `uuid` varchar(32) NOT NULL, `name` varchar(256) NOT NULL, `value` TEXT NOT NULL, `last_updated` int(11) NOT NULL', "ENGINE=$engine DEFAULT CHARSET=$charset");
    DB::getInstance()->query('ALTER TABLE `nl2_users_placeholders` ADD PRIMARY KEY(`server_id`, `uuid`, `name`)');

    DB::getInstance()->createTable('placeholders_settings', "`name` varchar(256) NOT NULL, `friendly_name` varchar(256) NULL DEFAULT NULL, `show_on_profile` tinyint(1) NOT NULL DEFAULT '1', `show_on_forum` tinyint(1) NOT NULL DEFAULT '1', `leaderboard` tinyint(1) NOT NULL DEFAULT '0', `leaderboard_title` varchar(36) NULL DEFAULT NULL, `leaderboard_sort` varchar(4) NOT NULL DEFAULT 'DESC'", "ENGINE=$engine DEFAULT CHARSET=$charset");
    DB::getInstance()->query('ALTER TABLE `nl2_placeholders_settings` ADD PRIMARY KEY(`name`)');

    $queries->addPermissionGroup(2, 'admincp.core.placeholders');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

// TODO: pre 10?
//if (count($version_number_id)) {
//    $version_number_id = $version_number_id[0]->id;
//    $queries->update('settings', $version_number_id, array(
//        'value' => '2.0.0-pr9'
//    ));
//} else {
//    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
//    $version_number_id = $version_number_id[0]->id;
//
//    $queries->update('settings', $version_number_id, array(
//        'value' => '2.0.0-pr9'
//    ));
//}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
