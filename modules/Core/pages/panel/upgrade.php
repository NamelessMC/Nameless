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

// Enqueue the update
$task = (new Upgrade())->fromNew(
    Module::getIdFromName('Core'),
    'Upgrade NamelessMC',
    null,
    date('U'),
);

Queue::schedule($task);

Redirect::to(URL::build('/panel/core/queue/&view=task&id=' . DB::getInstance()->lastId()));
