<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum redirects for old links
 *
 * @var string $route
 */

if (str_contains($route, 'view_forum')) {
    // Build new forum URL
    if (isset($_GET['fid']) && is_numeric($_GET['fid'])) {
        $url = URL::build('/forum/view/' . urlencode($_GET['fid']));
    } else {
        $url = URL::build('/forum');
    }
} else
    // Build new topic URL
    if (isset($_GET['tid']) && is_numeric($_GET['tid']) && str_contains($route, 'view_topic')) {
        $url = URL::build('/forum/topic/' . urlencode($_GET['tid']));
    } else {
        $url = URL::build('/forum');
    }

header('Location: ' . $url, true, 301);
die();
