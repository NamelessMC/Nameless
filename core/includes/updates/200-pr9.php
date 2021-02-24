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
