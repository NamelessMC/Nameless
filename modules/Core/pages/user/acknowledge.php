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

if(!$user->isLoggedIn()){
    Redirect::to(URL::build('/'));
    die();
}

// Get warning ID
$wid = explode('/', $route);
$wid = $wid[count($wid) - 1];

if(!isset($wid[count($wid) - 1]) || !is_numeric($wid)){
    Redirect::to(URL::build('/'));
    die();
}

// Ensure warning belongs to user
$warning = $queries->getWhere('infractions', array('id', '=', $wid));
if(count($warning)){
    if($warning[0]->acknowledged == 0 && $warning[0]->punished == $user->data()->id){
        $queries->update('infractions', $warning[0]->id, array(
            'acknowledged' => 1
        ));

       Log::getInstance()->log(Log::Action('user/acknowledge'), $result);
    }
}

Redirect::to(URL::build('/'));
die();