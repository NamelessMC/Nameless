<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
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
$warning = $queries->getWhere('infractions', ['id', '=', $wid]);
if (count($warning)) {
    if ($warning[0]->acknowledged == 0 && $warning[0]->punished == $user->data()->id) {
        $queries->update('infractions', $warning[0]->id, [
            'acknowledged' => 1
        ]);

        Log::getInstance()->log(Log::Action('user/acknowledge'), $result);
    }
}

Redirect::to(URL::build('/'));
