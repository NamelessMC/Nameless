<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel update execute page
 */

// Ensure an update is needed
$update_needed = Util::getSetting('version_update');

if ($update_needed !== 'true' && $update_needed !== 'urgent') {
    Redirect::to(URL::build('/panel/update'));
}

$cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);

// Perform the update
$upgradeScript = UpgradeScript::get(Util::getSetting('nameless_version'));
if ($upgradeScript instanceof UpgradeScript) {
    $upgradeScript->run();
}

$cache->setCache('update_check');
if ($cache->isCached('update_check')) {
    $cache->erase('update_check');
}

Redirect::to(URL::build('/panel/update'));
