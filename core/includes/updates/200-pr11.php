<?php
// 2.0.0 pr-11 to 2.0.0 pr-12 updater
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

// Change empty values to null
try {
    DB::getInstance()->createQuery("ALTER TABLE `nl2_group_sync` CHANGE `ingame_rank_name` `ingame_rank_name` VARCHAR(64) CHARACTER SET $db_charset NULL DEFAULT NULL;");
    DB::getInstance()->createQuery('UPDATE `nl2_group_sync` SET `ingame_rank_name` = NULL WHERE `ingame_rank_name` = \'\';');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Set CustomPages url and title length to 255
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_custom_pages` MODIFY `url` varchar(255)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_custom_pages` MODIFY `title` varchar(255)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Add placeholder enabled setting to settings table, Auto enable if placeholders contain data
try {
    $placeholders_exist = $queries->getWhere('settings', array('name', '=', 'placeholders'));
    if(!count($placeholders_exist)) {
        $placeholders = $queries->getWhere('placeholders_settings', array('id', '<>', '0'));

        $queries->create('settings', array(
            'name' => 'placeholders',
            'value' => (count($placeholders) ? '1' : '0')
        ));
    }
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    $recaptcha_type = DB::getInstance()->query('SELECT id FROM nl2_settings WHERE `name` = ? AND `value` = ?', array('recaptcha_type', 'Recaptcha2'));
    if ($recaptcha_type->count()) {
        $cache->setCache('configuration');
        $cache->store('recaptcha_type', 'Recaptcha2');
    }
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

if (count($version_number_id)) {
    $version_number_id = $version_number_id[0]->id;
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr12'
    ));
} else {
    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
    $version_number_id = $version_number_id[0]->id;

    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr12'
    ));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
