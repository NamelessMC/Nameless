<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum redirects for old links
 */

if (strpos($route, 'view_forum') !== false) {
    // Build new forum URL
    if (isset($_GET['fid']) && is_numeric($_GET['fid'])) {
        $url = URL::build('/forum/view/' . Output::getClean($_GET['fid']));
    } else {
        $url = URL::build('/forum');
    }
} else if (strpos($route, 'view_topic') !== false) {
    // Build new topic URL
    if (isset($_GET['tid']) && is_numeric($_GET['tid'])) {
        $url = URL::build('/forum/topic/' . Output::getClean($_GET['tid']));
    } else {
        $url = URL::build('/forum');
    }
} else {
    $url = URL::build('/forum');
}

header("Location: " . $url, TRUE, 301);
die();
