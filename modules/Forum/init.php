<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum initialisation file
 */

// Ensure module has been installed
$module_installed = $cache->retrieve('module_forum');
if(!$module_installed){
	// Hasn't been installed
	// Need to run the installer
	
	die('Run the installer first!');
	
} else {
	// Installed
}

define('FORUM', true);

// Initialise forum language
$forum_language = new Language('modules/Forum/language', LANGUAGE);

// Define URLs which belong to this module
$pages->add('Forum', '/admin/forums', 'pages/admin/forums.php');
$pages->add('Forum', '/forum', 'pages/forum/index.php');
$pages->add('Forum', '/forum/error', 'pages/forum/error.php');
$pages->add('Forum', '/forum/view_forum', 'pages/forum/view_forum.php');
$pages->add('Forum', '/forum/view_topic', 'pages/forum/view_topic.php');
$pages->add('Forum', '/forum/new_topic', 'pages/forum/new_topic.php');
$pages->add('Forum', '/forum/spam', 'pages/forum/spam.php');
$pages->add('Forum', '/forum/report', 'pages/forum/report.php');
$pages->add('Forum', '/forum/get_quotes', 'pages/forum/get_quotes.php');
$pages->add('Forum', '/forum/delete_post', 'pages/forum/delete_post.php');
$pages->add('Forum', '/forum/delete', 'pages/forum/delete.php');
$pages->add('Forum', '/forum/move', 'pages/forum/move.php');
$pages->add('Forum', '/forum/merge', 'pages/forum/merge.php');
$pages->add('Forum', '/forum/edit', 'pages/forum/edit.php');
$pages->add('Forum', '/forum/lock', 'pages/forum/lock.php');
$pages->add('Forum', '/forum/stick', 'pages/forum/stick.php');
$pages->add('Forum', '/forum/reactions', 'pages/forum/reactions.php');

// Add link to navbar
$navigation->add('forum', $forum_language->get('forum', 'forum'), URL::build('/forum'));

// Add link to admin sidebar
if(!isset($admin_sidebar)) $admin_sidebar = array();
$admin_sidebar['forums'] = array(
	'title' => $forum_language->get('forum', 'forums'),
	'url' => URL::build('/admin/forums')
);

// Front page module
if(!isset($front_page_modules)) $front_page_modules = array();
$front_page_modules[] = 'modules/Forum/front_page.php';

// Profile page tab
if(!isset($profile_tabs)) $profile_tabs = array();
$profile_tabs['forum'] = array('title' => $forum_language->get('forum', 'forum'), 'smarty_template' => 'forum/profile_tab.tpl', 'require' => 'modules' . DIRECTORY_SEPARATOR . 'Forum' . DIRECTORY_SEPARATOR . 'profile_tab.php');