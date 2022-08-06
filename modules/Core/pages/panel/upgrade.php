<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  License: MIT
 *
 *  Panel update execute page
 */

// Ensure an update is needed
$update_needed = DB::getInstance()->query('SELECT `value` FROM nl2_settings WHERE `name` = \'version_update\'')->first();

if (!$update_needed || ($update_needed->value !== 'true' && $update_needed->value !== 'urgent')) {
    Redirect::to(URL::build('/panel/update'));
}

$cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);

$version = DB::getInstance()->query('SELECT `value` FROM nl2_settings WHERE `name` = \'nameless_version\'')->first();

if ($version) {
    // Perform the update
    $upgradeScript = UpgradeScript::get($version->value);
    if ($upgradeScript instanceof UpgradeScript) {
        $upgradeScript->run();
    }

    $cache->setCache('update_check');
    if ($cache->isCached('update_check')) {
        $cache->erase('update_check');
    }
}

Redirect::to(URL::build('/panel/update'));
