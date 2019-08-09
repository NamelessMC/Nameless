<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Forum index page
 */

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
 
// Always define page name
define('PAGE', 'forum');
$page_title = $forum_language->get('forum', 'forum');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Initialise
$forum = new Forum();
$timeago = new Timeago(TIMEZONE);

// Get user group ID
if($user->isLoggedIn()){
    $user_group = $user->data()->group_id;
    $secondary_groups = $user->data()->secondary_groups;
} else {
    $user_group = null;
    $secondary_groups = null;
}

// Breadcrumbs and search bar - same for latest discussions view + table view
$smarty->assign('BREADCRUMB_URL', URL::build('/forum'));
$smarty->assign('BREADCRUMB_TEXT', $forum_language->get('forum', 'forum_index'));
// Search bar
$smarty->assign(array(
	'SEARCH_URL' => URL::build('/forum/search'),
	'SEARCH' => $language->get('general', 'search'),
	'TOKEN' => Token::get()
));

// Server status module
if(isset($status_enabled->value) && $status_enabled->value == 'true'){
	// Todo
	$smarty->assign('SERVER_STATUS', '');

} else {
	// Module disabled, assign empty values
	$smarty->assign('SERVER_STATUS', '');
}

// Check session
if(Session::exists('spam_info')){
	$smarty->assign('SPAM_INFO', Session::flash('spam_info'));
}

// Assign language variables
$smarty->assign('FORUMS_TITLE', $forum_language->get('forum', 'forums'));
$smarty->assign('DISCUSSION', $forum_language->get('forum', 'discussion'));
$smarty->assign('TOPIC', $forum_language->get('forum', 'topic'));
$smarty->assign('STATS', $forum_language->get('forum', 'stats'));
$smarty->assign('LAST_REPLY', $forum_language->get('forum', 'last_reply'));
$smarty->assign('BY', $forum_language->get('forum', 'by'));
$smarty->assign('IN', $forum_language->get('forum', 'in'));
$smarty->assign('VIEWS', $forum_language->get('forum', 'views'));
$smarty->assign('TOPICS', $forum_language->get('forum', 'topics'));
$smarty->assign('POSTS', $forum_language->get('forum', 'posts'));
$smarty->assign('STATISTICS', $forum_language->get('forum', 'statistics'));
$smarty->assign('OVERVIEW', $forum_language->get('forum', 'overview'));
$smarty->assign('LATEST_DISCUSSIONS_TITLE', $forum_language->get('forum', 'latest_discussions'));
$smarty->assign('NO_TOPICS', $forum_language->get('forum', 'no_topics_short'));

// Get forums
// Check cache per user's group
if($user_group){
	$cache_name = 'forum_forums_' . $user_group . '-' . $secondary_groups;
} else {
	$cache_name = 'forum_forums_guest';
}

$cache->setCache($cache_name);

if($cache->isCached('forums')){
	$forums = $cache->retrieve('forums');

} else {
	$forums = $forum->listAllForums($user_group, $secondary_groups, ($user->isLoggedIn() ? $user->data()->id : 0));

	// Loop through to get last poster avatars and to format a date
	if(count($forums)){
		foreach($forums as $key => $item){
			if(count($item['subforums'])){
				foreach($item['subforums'] as $subforum_id => $subforum){
					if(isset($subforum->last_post)){
						$forums[$key]['subforums'][$subforum_id]->last_post->avatar = $user->getAvatar($forums[$key]['subforums'][$subforum_id]->last_post->post_creator, '../', 64);

						if(is_null($forums[$key]['subforums'][$subforum_id]->last_post->created)){
						  $forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeago->inWords($forums[$key]['subforums'][$subforum_id]->last_post->post_date, $language->getTimeLanguage());
						  $forums[$key]['subforums'][$subforum_id]->last_post->post_date = date('d M Y, H:i', strtotime($forums[$key]['subforums'][$subforum_id]->last_post->post_date));
						} else {
						  $forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeago->inWords(date('d M Y, H:i', $forums[$key]['subforums'][$subforum_id]->last_post->created), $language->getTimeLanguage());
						  $forums[$key]['subforums'][$subforum_id]->last_post->post_date = date('d M Y, H:i', $forums[$key]['subforums'][$subforum_id]->last_post->created);
						}

						$forums[$key]['subforums'][$subforum_id]->last_post->user_style = $user->getGroupClass($forums[$key]['subforums'][$subforum_id]->last_post->post_creator);
					}

					if($forums[$key]['subforums'][$subforum_id]->redirect_forum == 1)
						$forums[$key]['subforums'][$subforum_id]->redirect_confirm = str_replace('{x}', $forums[$key]['subforums'][$subforum_id]->redirect_to, $forum_language->get('forum', 'forum_redirect_warning'));
				}
			}
		}
	} else $forums = array();

	$cache->store('forums', $forums, 60);
}

$smarty->assign('FORUMS', $forums);
$smarty->assign('YES', $language->get('general', 'yes'));
$smarty->assign('NO', $language->get('general', 'no'));
$smarty->assign('SUBFORUMS', $forum_language->get('forum', 'subforums'));

$smarty->assign('FORUM_INDEX_LINK', URL::build('/forum'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

$smarty->assign('WIDGETS', $widgets->getWidgets());

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/forum_index.tpl', $smarty);
