<?php
// 2.0.0 pr-10 to 2.0.0 updater
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

// Drop primary keys + add new ID columns
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_placeholders` DROP PRIMARY KEY');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_placeholders` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_placeholders_settings` DROP PRIMARY KEY');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_placeholders_settings` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`)');
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

// Update version number
//$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
//
//if (count($version_number_id)) {
//    $version_number_id = $version_number_id[0]->id;
//    $queries->update('settings', $version_number_id, array(
//        'value' => '2.0.0'
//    ));
//} else {
//    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
//    $version_number_id = $version_number_id[0]->id;
//
//    $queries->update('settings', $version_number_id, array(
//        'value' => '2.0.0'
//    ));
//}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
