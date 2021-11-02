<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel update execute page
 */

$queries =  new Queries();

// Ensure an update is needed
$update_needed = $queries->getWhere('settings', ['name', '=', 'version_update']);
$update_needed = $update_needed[0]->value;

if ($update_needed != 'true' && $update_needed != 'urgent') {
    Redirect::to(URL::build('/panel/update'));
    die();
}

$cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);

// Get the current version
$current_version = $queries->getWhere('settings', ['name', '=', 'nameless_version']);
$current_version = $current_version[0]->value;

// Perform the update
$upgradeScript = UpgradeScript::get($current_version);
if ($upgradeScript instanceof UpgradeScript) {
    $upgradeScript->run();
}

$cache->setCache('update_check');
if ($cache->isCached('update_check')) {
    $cache->erase('update_check');
}

Redirect::to(URL::build('/panel/update'));
die();
