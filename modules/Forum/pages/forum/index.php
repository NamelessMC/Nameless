<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum index page
 */

// Always define page name
const PAGE = 'forum';
$page_title = $forum_language->get('forum', 'forum');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Initialise
$forum = new Forum();
$timeago = new TimeAgo(TIMEZONE);

// Get user group IDs
$groups = $user->getAllGroupIds();

// Breadcrumbs and search bar - same for latest discussions view + table view
$smarty->assign('BREADCRUMB_URL', URL::build('/forum'));
$smarty->assign('BREADCRUMB_TEXT', $forum_language->get('forum', 'forum_index'));
// Search bar
$smarty->assign([
    'SEARCH_URL' => URL::build('/forum/search'),
    'SEARCH' => $language->get('general', 'search'),
    'TOKEN' => Token::get()
]);

// Server status module
$smarty->assign('SERVER_STATUS', '');

// Check session
if (Session::exists('spam_info')) {
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
$cache_name = 'forum_forums_' . rtrim(implode('-', $groups), '-');
$cache->setCache($cache_name);

if ($cache->isCached('forums')) {
    $forums = $cache->retrieve('forums');
} else {
    $forums = $forum->listAllForums($groups, ($user->isLoggedIn() ? $user->data()->id : 0));

    // Loop through to get last poster avatars and to format a date
    if (count($forums)) {
        foreach ($forums as $key => $item) {
            $forums[$key]['link'] = URL::build('/forum/view/' . urlencode($key) . '-' . $forum->titleToURL($item['title']));
            if (isset($item['subforums']) && count($item['subforums'])) {
                foreach ($item['subforums'] as $subforum_id => $subforum) {
                    if (isset($subforum->last_post)) {
                        $last_post_user = new User($forums[$key]['subforums'][$subforum_id]->last_post->post_creator);

                        $forums[$key]['subforums'][$subforum_id]->last_post->avatar = $last_post_user->getAvatar(64);
                        $forums[$key]['subforums'][$subforum_id]->last_post->user_style = $last_post_user->getGroupStyle();
                        $forums[$key]['subforums'][$subforum_id]->last_post->username = $last_post_user->getDisplayname();
                        $forums[$key]['subforums'][$subforum_id]->last_post->profile = $last_post_user->getProfileURL();

                        if (is_null($forums[$key]['subforums'][$subforum_id]->last_post->created)) {
                            $forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeago->inWords($forums[$key]['subforums'][$subforum_id]->last_post->post_date, $language);
                            $forums[$key]['subforums'][$subforum_id]->last_post->post_date = date(DATE_FORMAT, strtotime($forums[$key]['subforums'][$subforum_id]->last_post->post_date));
                        } else {
                            $forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeago->inWords($forums[$key]['subforums'][$subforum_id]->last_post->created, $language);
                            $forums[$key]['subforums'][$subforum_id]->last_post->post_date = date(DATE_FORMAT, $forums[$key]['subforums'][$subforum_id]->last_post->created);
                        }
                    }

                    if ($forums[$key]['subforums'][$subforum_id]->redirect_forum == 1 && URL::isExternalURL($forums[$key]['subforums'][$subforum_id]->redirect_url)) {
                        $forums[$key]['subforums'][$subforum_id]->redirect_confirm = $forum_language->get('forum', 'forum_redirect_warning', ['url' => $forums[$key]['subforums'][$subforum_id]->redirect_to]);
                    }
                }
            }
        }
    } else {
        $forums = [];
    }

    $cache->store('forums', $forums, 60);
}

$smarty->assign('FORUMS', $forums);
$smarty->assign('YES', $language->get('general', 'yes'));
$smarty->assign('NO', $language->get('general', 'no'));
$smarty->assign('SUBFORUMS', $forum_language->get('forum', 'subforums'));

$smarty->assign('FORUM_INDEX_LINK', URL::build('/forum'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/forum_index.tpl', $smarty);
