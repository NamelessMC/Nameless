<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Forum initialisation file
 */

// Ensure module has been installed
$cache->setCache('modulescache');
$module_installed = $cache->retrieve('module_forum');
if(!$module_installed){
    // Hasn't been installed
    // Need to run the installer

    $exists = $queries->tableExists('forums');
    if(empty($exists)) {
        die('Run the installer first!');
    } else {
        $cache->store('module_forum', true);
    }

}

define('FORUM', true);

// Initialise forum language
$forum_language = new Language(ROOT_PATH . '/modules/Forum/language', LANGUAGE);

// Define URLs which belong to this module
$pages->add('Forum', '/admin/forums', 'pages/admin/forums.php');
$pages->add('Forum', '/forum', 'pages/forum/index.php', 'forum', true);
$pages->add('Forum', '/forum/error', 'pages/forum/error.php');
$pages->add('Forum', '/forum/view', 'pages/forum/view_forum.php');
$pages->add('Forum', '/forum/topic', 'pages/forum/view_topic.php');
$pages->add('Forum', '/forum/new', 'pages/forum/new_topic.php');
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
$pages->add('Forum', '/forum/search', 'pages/forum/search.php');

// Redirects
$pages->add('Forum', '/forum/view_topic', 'pages/forum/redirect.php');
$pages->add('Forum', '/forum/view_forum', 'pages/forum/redirect.php');

if(!isset($_GET['route']) || (isset($_GET['route']) && rtrim($_GET['route'], '/') != '/admin/update_execute')){
	// Add link to navbar
	$cache->setCache('navbar_order');
	if(!$cache->isCached('forum_order')){
		$forum_order = 2;
		$cache->store('forum_order', 2);
	} else {
		$forum_order = $cache->retrieve('forum_order');
	}
	$navigation->add('forum', $forum_language->get('forum', 'forum'), URL::build('/forum'), 'top', null, $forum_order);

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

	// Global variables if user is logged in
	if($user->isLoggedIn()){
		// Basic user variables
		$topic_count = $queries->getWhere('topics', array('topic_creator', '=', $user->data()->id));
		$topic_count = count($topic_count);
		$post_count = $queries->getWhere('posts', array('post_creator', '=', $user->data()->id));
		$post_count = count($post_count);
		$smarty->assign('LOGGED_IN_USER_FORUM', array(
			'topic_count' => $topic_count,
			'post_count' => $post_count
		));
	}

	// Widgets
	// Latest posts
	require_once(ROOT_PATH . '/modules/Forum/widgets/LatestPostsWidget.php');
	$module_pages = $widgets->getPages('Latest Posts');

	require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
	$forum = new Forum();
	$timeago = new Timeago(TIMEZONE);

	if($user->isLoggedIn()) {
		$user_group = $user->data()->group_id;
		$secondary_groups = $user->data()->secondary_groups;
	} else {
		$user_group = null;
		$secondary_groups = null;
	}

	if($user_group){
		$cache_name = 'forum_discussions_' . $user_group . '_' . $secondary_groups;
	} else {
		$cache_name = 'forum_discussions_guest';
	}

	$cache->setCache($cache_name);

	if($cache->isCached('latest_posts')){
		$template_array = $cache->retrieve('latest_posts');

	} else {
		// Generate latest posts
		$discussions = $forum->getLatestDiscussions($user_group, $secondary_groups);

		$n = 0;
		// Calculate the number of discussions to display (5 max)
		if(count($discussions) <= 5){
			$limit = count($discussions);
		} else {
			$limit = 5;
		}

		$template_array = array();

		// Generate an array to pass to template
		while($n < $limit){
			// Get the name of the forum from the ID
			$forum_name = $queries->getWhere('forums', array('id', '=', $discussions[$n]['forum_id']));
			$forum_name = Output::getPurified(htmlspecialchars_decode($forum_name[0]->forum_title));

			// Get the number of replies
			$posts = $queries->getWhere('posts', array('topic_id', '=', $discussions[$n]['id']));
			$posts = count($posts);

			// Get the last reply user's avatar
			$last_reply_avatar = $user->getAvatar($discussions[$n]['topic_last_user'], "../", 64);

			// Is there a label?
			if($discussions[$n]['label'] != 0){ // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $discussions[$n]['label']));
				if(count($label)){
					$label = $label[0];

					$label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
					if(count($label_html)){
						$label_html = $label_html[0]->html;
						$label = str_replace('{x}', Output::getClean($label->name), $label_html);
					} else $label = '';
				} else $label = '';
			} else { // no
				$label = '';
			}

			// Add to array
			$template_array[] = array(
				'topic_title' => Output::getClean($discussions[$n]['topic_title']),
				'topic_id' => $discussions[$n]['id'],
				'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_date']), $language->getTimeLanguage()),
				'topic_created' => date('d M Y, H:i', $discussions[$n]['topic_date']),
				'topic_created_username' => Output::getClean($user->idToNickname($discussions[$n]['topic_creator'])),
				'topic_created_mcname' => Output::getClean($user->idToName($discussions[$n]['topic_creator'])),
				'topic_created_style' => $user->getGroupClass($discussions[$n]['topic_creator']),
				'locked' => $discussions[$n]['locked'],
				'forum_name' => $forum_name,
				'forum_id' => $discussions[$n]['forum_id'],
				'views' => $discussions[$n]['topic_views'],
				'posts' => $posts,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_reply_date']), $language->getTimeLanguage()),
				'last_reply' => date('d M Y, H:i', $discussions[$n]['topic_reply_date']),
				'last_reply_username' => Output::getClean($user->idToNickname($discussions[$n]['topic_last_user'])),
				'last_reply_mcname' => Output::getClean($user->idToName($discussions[$n]['topic_last_user'])),
				'last_reply_style' => $user->getGroupClass($discussions[$n]['topic_last_user']),
				'label' => $label,
				'link' => URL::build('/forum/topic/' . $discussions[$n]['id'] . '-' . $forum->titleToURL($discussions[$n]['topic_title'])),
				'forum_link' => URL::build('/forum/forum/' . $discussions[$n]['forum_id']),
				'author_link' => URL::build('/profile/' . Output::getClean($user->idToName($discussions[$n]['topic_creator']))),
				'last_reply_profile_link' => URL::build('/profile/' . Output::getClean($user->idToName($discussions[$n]['topic_last_user']))),
				'last_reply_link' => URL::build('/forum/topic/' . $discussions[$n]['id'] . '-' . $forum->titleToURL($discussions[$n]['topic_title']), 'pid=' . $discussions[$n]['last_post_id'])
			);

			$n++;
		}

		$cache->store('latest_posts_widget_' . ($user->isLoggedIn() ? $user->data()->group_id . '_' . $user->data()->secondary_groups : 0), $template_array, 60);
	}
	$widgets->add(new LatestPostsWidget($module_pages, $template_array, $forum_language->get('forum', 'latest_posts'), $forum_language->get('forum', 'by'), $smarty));
}