<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum initialisation file
 */

// Ensure module has been installed
$cache->setCache('modulescache');
$module_installed = $cache->retrieve('module_forum');
if (!$module_installed) {
    // Hasn't been installed
    // Need to run the installer

    $exists = DB::getInstance()->showTables('forums');
    if (empty($exists)) {
        die('Run the installer first!');
    }

    $cache->store('module_forum', true);
}

const FORUM = true;

// Initialise forum language
$forum_language = new Language(ROOT_PATH . '/modules/Forum/language');

/*
 *  Temp methods for front page module, profile page tab + admin sidebar; likely to change in the future
 */
// Front page module
if (!isset($front_page_modules)) {
    $front_page_modules = [];
}
$front_page_modules[] = 'modules/Forum/front_page.php';

// Profile page tab
if (!isset($profile_tabs)) {
    $profile_tabs = [];
}
$profile_tabs['forum'] = ['title' => $forum_language->get('forum', 'forum'), 'smarty_template' => 'forum/profile_tab.tpl', 'require' => ROOT_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Forum' . DIRECTORY_SEPARATOR . 'profile_tab.php'];

// Following topics UserCP sidebar
$cc_nav->add('cc_following_topics', $forum_language->get('forum', 'following_topics'), URL::build('/user/following_topics'));

// Initialise module
require_once(ROOT_PATH . '/modules/Forum/module.php');
$module = new Forum_Module($language, $forum_language, $pages);
