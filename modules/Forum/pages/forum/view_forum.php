<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  View forum page
 */

// Always define page name
define('PAGE', 'forum');

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();
$timeago = new Timeago(TIMEZONE);

require(ROOT_PATH . '/core/includes/paginate.php'); // Get number of topics on a page

// Get forum ID
$fid = explode('/', $route);
$fid = $fid[count($fid) - 1];

if(!isset($fid[count($fid) - 1])){
	require_once(ROOT_PATH . '/404.php');
	die();
}

$fid = explode('-', $fid);
if(!is_numeric($fid[0])){
	require_once(ROOT_PATH . '/404.php');
	die();
}
$fid = $fid[0];

// Get user group ID
if($user->isLoggedIn()) {
    $user_group = $user->data()->group_id;
    $secondary_groups = $user->data()->secondary_groups;
} else {
    $user_group = null;
    $secondary_groups = null;
}

// Does the forum exist, and can the user view it?
$list = $forum->canViewForum($fid, $user_group, $secondary_groups);
if(!$list){
	require_once(ROOT_PATH . '/403.php');
	die();
}

// Get data from the database
$forum_query = $queries->getWhere('forums', array('id', '=', $fid));
$forum_query = $forum_query[0];

// Get page
if(isset($_GET['p'])){
    if(!is_numeric($_GET['p'])){
        Redirect::to(URL::build('/forum'));
        die();
    } else {
        if($_GET['p'] == 1){
            // Avoid bug in pagination class
            Redirect::to(URL::build('/forum/view/' . $fid . '-'.  $forum->titleToURL($forum_query->forum_title)));
            die();
        }
        $p = $_GET['p'];
    }
} else {
    $p = 1;
}

$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', '/forum/view'));
if(count($page_metadata)){
	define('PAGE_DESCRIPTION', str_replace(array('{site}', '{forum_title}', '{page}', '{description}'), array(SITE_NAME, Output::getClean($forum_query->forum_title), Output::getClean($p), Output::getClean(strip_tags(Output::getDecoded($forum_query->forum_description)))), $page_metadata[0]->description));
	define('PAGE_KEYWORDS', $page_metadata[0]->tags);
}

$page_title = $forum_language->get('forum', 'forum');
if(isset($p)) $page_title .= ' - ' . str_replace('{x}', $p, $language->get('general', 'page_x'));
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Redirect forum?
if($forum_query->redirect_forum == 1){
	$smarty->assign(array(
		'CONFIRM_REDIRECT' => str_replace('{x}', $forum_query->redirect_url, $forum_language->get('forum', 'forum_redirect_warning')),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no'),
		'REDIRECT_URL' => Output::getClean(htmlspecialchars_decode($forum_query->redirect_url)),
		'FORUM_INDEX' => URL::build('/forum')
	));

	// Load modules + template
	Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

	$page_load = microtime(true) - $start;
	define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	$template->onPageLoad();

	$smarty->assign('WIDGETS', $widgets->getWidgets());

	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	// Display template
	$template->displayTemplate('forum/view_forum_confirm_redirect.tpl', $smarty);

} else {
	// Get all topics
	if ($user->isLoggedIn())
		$user_id = $user->data()->id;
	else
		$user_id = 0;

	if ($forum->canViewOtherTopics($fid, $user_group, $secondary_groups))
		$topics = $queries->orderWhere("topics", "forum_id = " . $fid . " AND sticky = 0 AND deleted = 0", "topic_reply_date", "DESC");
	else
		$topics = $queries->orderWhere("topics", "forum_id = " . $fid . " AND sticky = 0 AND deleted = 0 AND topic_creator = " . $user_id, "topic_reply_date", "DESC");

	// Get sticky topics
	$stickies = $queries->orderWhere("topics", "forum_id = " . $fid . " AND sticky = 1 AND deleted = 0", "topic_reply_date", "DESC");

	// Search bar
	$smarty->assign(array(
		'SEARCH_URL' => URL::build('/forum/search'),
		'SEARCH' => $language->get('general', 'search'),
		'TOKEN' => Token::get()
	));

	// Breadcrumbs and search bar - same for latest discussions view + table view
	$parent_category = $queries->getWhere('forums', array('id', '=', $forum_query->parent));
	$breadcrumbs = array(0 => array(
		'id' => $forum_query->id,
		'forum_title' => Output::getClean($forum_query->forum_title),
		'active' => 1,
		'link' => URL::build('/forum/view/' . $forum_query->id . '-' . $forum->titleToURL($forum_query->forum_title))
	));
	if (!empty($parent_category) && $parent_category[0]->parent == 0) {
		// Category
		$breadcrumbs[] = array(
			'id' => $parent_category[0]->id,
			'forum_title' => Output::getClean($parent_category[0]->forum_title),
			'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
		);
	} else if (!empty($parent_category)) {
		// Parent forum, get its category
		$breadcrumbs[] = array(
			'id' => $parent_category[0]->id,
			'forum_title' => Output::getClean($parent_category[0]->forum_title),
			'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
		);
		$parent = false;
		while ($parent == false) {
			$parent_category = $queries->getWhere('forums', array('id', '=', $parent_category[0]->parent));
			$breadcrumbs[] = array(
				'id' => $parent_category[0]->id,
				'forum_title' => Output::getClean($parent_category[0]->forum_title),
				'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
			);
			if ($parent_category[0]->parent == 0) {
				$parent = true;
			}
		}
	}

	$breadcrumbs[] = array(
		'id' => 'index',
		'forum_title' => $forum_language->get('forum', 'forum_index'),
		'link' => URL::build('/forum')
	);

	$smarty->assign('BREADCRUMBS', array_reverse($breadcrumbs));

	// Server status module
	if (isset($status_enabled->value) && $status_enabled->value == 'true') {
		// Todo
		$smarty->assign('SERVER_STATUS', '');

	} else {
		// Module disabled, assign empty values
		$smarty->assign('SERVER_STATUS', '');
	}

	// Assignments
	$smarty->assign('FORUM_INDEX_LINK', URL::build('/forum'));

	// Any subforums?
	$subforums = $queries->getWhere('forums', array('parent', '=', $fid));

	$subforum_array = array();

	if (count($subforums)) {
		// append subforums to string
		foreach ($subforums as $subforum) {
			// Get number of topics
			if ($forum->forumExist($subforum->id, $user_group, $secondary_groups)) {
				if ($forum->canViewOtherTopics($subforum->id, $user_group, $secondary_groups))
					$latest_post = $queries->orderWhere('topics', 'forum_id = ' . $subforum->id . ' AND deleted = 0', 'topic_reply_date', 'DESC');
				else
					$latest_post = $queries->orderWhere('topics', 'forum_id = ' . $subforum->id . ' AND deleted = 0 AND topic_creator = ' . $user_id, 'topic_reply_date', 'DESC');

				$subforum_topics = count($latest_post);
				if (count($latest_post)) {
					foreach ($latest_post as $item) {
						if ($item->deleted == 0) {
							$latest_post = $item;
							break;
						}
					}

					$latest_post_link = URL::build('/forum/topic/' . $latest_post->id . '-' . $forum->titleToURL($latest_post->topic_title));
					$latest_post_avatar = $user->getAvatar($latest_post->topic_last_user, "../", 128);
					$latest_post_title = Output::getClean($latest_post->topic_title);
					$latest_post_user = Output::getClean($user->idToNickname($latest_post->topic_last_user));
					$latest_post_user_link = URL::build('/profile/' . $user->idToName($latest_post->topic_last_user));
					$latest_post_style = $user->getGroupClass($latest_post->topic_last_user);
					$latest_post_date_timeago = $timeago->inWords(date('d M Y, H:i', $latest_post->topic_reply_date), $language->getTimeLanguage());
					$latest_post_time = date('d M Y, H:i', $latest_post->topic_reply_date);
					$latest_post_user_id = Output::getClean($latest_post->topic_last_user);

					$latest_post = array(
						'link' => $latest_post_link,
						'title' => $latest_post_title,
						'last_user_avatar' => $latest_post_avatar,
						'last_user' => $latest_post_user,
						'last_user_style' => $latest_post_style,
						'last_user_link' => $latest_post_user_link,
						'timeago' => $latest_post_date_timeago,
						'time' => $latest_post_time,
						'last_user_id' => $latest_post_user_id
					);
				} else $latest_post = array();

				$subforum_array[] = array(
					'id' => $subforum->id,
					'title' => Output::getPurified(Output::getDecoded($subforum->forum_title)),
					'description' => Output::getPurified(Output::getDecoded($subforum->forum_description)),
					'topics' => $subforum_topics,
					'link' => URL::build('/forum/view/' . $subforum->id . '-' . $forum->titleToURL($subforum->forum_title)),
					'latest_post' => $latest_post,
					'icon' => Output::getDecoded($subforum->icon),
					'redirect' => $subforum->redirect_forum
				);
			}
		}
	}

	// Assign language variables
	$smarty->assign('FORUMS', $forum_language->get('forum', 'forums'));
	$smarty->assign('DISCUSSION', $forum_language->get('forum', 'discussion'));
	$smarty->assign('TOPIC', $forum_language->get('forum', 'topic'));
	$smarty->assign('STATS', $forum_language->get('forum', 'stats'));
	$smarty->assign('LAST_REPLY', $forum_language->get('forum', 'last_reply'));
	$smarty->assign('BY', $forum_language->get('forum', 'by'));
	$smarty->assign('VIEWS', $forum_language->get('forum', 'views'));
	$smarty->assign('POSTS', $forum_language->get('forum', 'posts'));
	$smarty->assign('STATISTICS', $forum_language->get('forum', 'stats'));
	$smarty->assign('OVERVIEW', $forum_language->get('forum', 'overview'));
	$smarty->assign('LATEST_DISCUSSIONS_TITLE', $forum_language->get('forum', 'latest_discussions'));
	$smarty->assign('TOPICS', $forum_language->get('forum', 'topics'));
	$smarty->assign('NO_TOPICS', $forum_language->get('forum', 'no_topics_short'));
	$smarty->assign('SUBFORUMS', $subforum_array);
	$smarty->assign('SUBFORUM_LANGUAGE', $forum_language->get('forum', 'subforums'));
	$smarty->assign('FORUM_TITLE', Output::getPurified(htmlspecialchars_decode($forum_query->forum_title)));
	$smarty->assign('FORUM_ICON', htmlspecialchars_decode($forum_query->icon));
	$smarty->assign('STICKY_TOPICS', $forum_language->get('forum', 'sticky_topics'));

	// Can the user post here?
	if ($user->isLoggedIn() && $forum->canPostTopic($fid, $user_group, $secondary_groups)) {
		$smarty->assign('NEW_TOPIC_BUTTON', URL::build('/forum/new/', 'fid=' . $fid));
	} else {
		$smarty->assign('NEW_TOPIC_BUTTON', false);
	}

	$smarty->assign('NEW_TOPIC', $forum_language->get('forum', 'new_topic'));

	// Topics
	if (!count($stickies) && !count($topics)) {
		// No topics yet
		$smarty->assign('NO_TOPICS_FULL', $forum_language->get('forum', 'no_topics'));

		if ($user->isLoggedIn() && $forum->canPostTopic($fid, $user_group, $secondary_groups)) {
			$smarty->assign('NEW_TOPIC_BUTTON', URL::build('/forum/new/', 'fid=' . $fid));
		} else {
			$smarty->assign('NEW_TOPIC_BUTTON', false);
		}

		$no_topics_exist = true;

	} else {
		// Topics/sticky topics exist

		$sticky_array = array();
		// Assign sticky threads to smarty variable
		foreach ($stickies as $sticky) {
			// Get number of replies to a topic
			$replies = $queries->getWhere('posts', array('topic_id', '=', $sticky->id));
			$replies = count($replies);

			// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
			$last_reply_avatar = $user->getAvatar($sticky->topic_last_user, "../", 128);

			// Is there a label?
			if ($sticky->label != 0) { // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $sticky->label));
				if (count($label)) {
					$label = $label[0];

					$label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
					if (count($label_html)) {
						$label_html = $label_html[0]->html;
						$label = str_replace('{x}', Output::getClean($label->name), $label_html);
					} else $label = '';
				} else $label = '';
			} else { // no
				$label = '';
			}

			// Add to array
			$sticky_array[] = array(
				'topic_title' => Output::getClean($sticky->topic_title),
				'topic_id' => $sticky->id,
				'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_date), $language->getTimeLanguage()),
				'topic_created' => date('d M Y, H:i', $sticky->topic_date),
				'topic_created_username' => Output::getClean($user->idToNickname($sticky->topic_creator)),
				'topic_created_mcname' => Output::getClean($user->idToName($sticky->topic_creator)),
				'topic_created_style' => $user->getGroupClass($sticky->topic_creator),
				'topic_created_user_id' => Output::getClean($sticky->topic_creator),
				'views' => $sticky->topic_views,
				'locked' => $sticky->locked,
				'posts' => $replies,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_reply_date), $language->getTimeLanguage()),
				'last_reply' => date('d M Y, H:i', $sticky->topic_reply_date),
				'last_reply_username' => Output::getClean($user->idToNickname($sticky->topic_last_user)),
				'last_reply_mcname' => Output::getClean($user->idToName($sticky->topic_last_user)),
				'last_reply_style' => $user->getGroupClass($sticky->topic_last_user),
				'last_reply_user_id' => Output::getClean($sticky->topic_last_user),
				'label' => $label,
				'author_link' => URL::build('/profile/' . Output::getClean($user->idToName($sticky->topic_creator))),
				'link' => URL::build('/forum/topic/' . $sticky->id . '-' . $forum->titleToURL($sticky->topic_title)),
				'last_reply_link' => URL::build('/profile/' . Output::getClean($user->idToName($sticky->topic_last_user)))
			);
		}
		// Clear out variables
		$stickies = null;
		$sticky = null;

		// Latest discussions
		// Pagination
		$paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
		$results = $paginator->getLimited($topics, 10, $p, count($topics));
		$pagination = $paginator->generate(7, URL::build('/forum/view/' . $fid . '-' . $forum->titleToURL($forum_query->forum_title), true));

		if (count($topics))
			$smarty->assign('PAGINATION', $pagination);
		else
			$smarty->assign('PAGINATION', '');

		$template_array = array();
		// Get a list of all topics from the forum, and paginate
		for ($n = 0; $n < count($results->data); $n++) {
			// Get number of replies to a topic
			$replies = $queries->getWhere("posts", array("topic_id", "=", $results->data[$n]->id));
			$replies = count($replies);

			// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
			$last_reply_avatar = $user->getAvatar($results->data[$n]->topic_last_user, "../", 128);

			// Is there a label?
			if ($results->data[$n]->label != 0) { // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $results->data[$n]->label));
				if (count($label)) {
					$label = $label[0];

					$label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
					if (count($label_html)) {
						$label_html = $label_html[0]->html;
						$label = str_replace('{x}', Output::getClean($label->name), $label_html);
					} else $label = '';
				} else $label = '';
			} else { // no
				$label = '';
			}

			// Add to array
			$template_array[] = array(
				'topic_title' => Output::getClean($results->data[$n]->topic_title),
				'topic_id' => $results->data[$n]->id,
				'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->topic_date), $language->getTimeLanguage()),
				'topic_created' => date('d M Y, H:i', $results->data[$n]->topic_date),
				'topic_created_username' => Output::getClean($user->idToNickname($results->data[$n]->topic_creator)),
				'topic_created_mcname' => Output::getClean($user->idToName($results->data[$n]->topic_creator)),
				'topic_created_style' => $user->getGroupClass($results->data[$n]->topic_creator),
				'topic_created_user_id' => Output::getClean($results->data[$n]->topic_creator),
				'locked' => $results->data[$n]->locked,
				'views' => $results->data[$n]->topic_views,
				'posts' => $replies,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->topic_reply_date), $language->getTimeLanguage()),
				'last_reply' => date('d M Y, H:i', $results->data[$n]->topic_reply_date),
				'last_reply_username' => Output::getClean($user->idToNickname($results->data[$n]->topic_last_user)),
				'last_reply_mcname' => Output::getClean($user->idToName($results->data[$n]->topic_last_user)),
				'last_reply_style' => $user->getGroupClass($results->data[$n]->topic_last_user),
				'label' => $label,
				'author_link' => URL::build('/profile/' . Output::getClean($user->idToName($results->data[$n]->topic_creator))),
				'link' => URL::build('/forum/topic/' . $results->data[$n]->id . '-' . $forum->titleToURL($results->data[$n]->topic_title)),
				'last_reply_link' => URL::build('/profile/' . Output::getClean($user->idToName($results->data[$n]->topic_last_user))),
				'last_reply_user_id' => Output::getClean($results->data[$n]->topic_last_user)
			);
		}

		// Assign to Smarty variable
		$smarty->assign('STICKY_DISCUSSIONS', $sticky_array);
		$smarty->assign('LATEST_DISCUSSIONS', $template_array);
	}

	// Load modules + template
	Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

	$page_load = microtime(true) - $start;
	define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	$template->onPageLoad();

	$smarty->assign('WIDGETS', $widgets->getWidgets());

	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	// Display template
	if(isset($no_topics_exist))
		$template->displayTemplate('forum/view_forum_no_discussions.tpl', $smarty);
	else
		$template->displayTemplate('forum/view_forum.tpl', $smarty);
}
