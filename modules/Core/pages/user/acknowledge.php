<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  User warning acknowledgement page
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Get warning ID
$wid = explode('/', $route);
$wid = $wid[count($wid) - 1];

if (!is_numeric($wid)) {
    Redirect::to(URL::build('/'));
}

// Ensure warning belongs to user
$warning = DB::getInstance()->get('infractions', ['id', $wid])->results();
if (count($warning)) {
    if ($warning[0]->acknowledged == 0 && $warning[0]->punished == $user->data()->id) {
        DB::getInstance()->update('infractions', $warning[0]->id, [
            'acknowledged' => true,
        ]);

        Log::getInstance()->log(Log::Action('user/acknowledge'));
    }
}

Redirect::to(URL::build('/'));
