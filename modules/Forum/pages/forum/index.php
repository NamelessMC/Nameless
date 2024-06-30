<?php
/**
 * Forum index page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $forum_language
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

// Always define page name
const PAGE = 'forum';
$page_title = $forum_language->get('forum', 'forum');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

// Initialise
$forum = new Forum();
$timeAgo = new TimeAgo(TIMEZONE);

// Get user group IDs
$groups = $user->getAllGroupIds();

// Breadcrumbs and search bar - same for latest discussions view + table view
$template->getEngine()->addVariables([
    'BREADCRUMB_URL' => URL::build('/forum'),
    'BREADCRUMB_TEXT' => $forum_language->get('forum', 'forum_index'),
    'SEARCH_URL' => URL::build('/forum/search'),
    'SEARCH' => $language->get('general', 'search'),
    'TOKEN' => Token::get(),
]);

// Check session
if (Session::exists('spam_info')) {
    $template->getEngine()->addVariable('SPAM_INFO', Session::flash('spam_info'));
}

// Assign language variables
$template->getEngine()->addVariables([
    'FORUMS_TITLE' => $forum_language->get('forum', 'forums'),
    'DISCUSSION' => $forum_language->get('forum', 'discussion'),
    'TOPIC' => $forum_language->get('forum', 'topic'),
    'STATS' => $forum_language->get('forum', 'stats'),
    'LAST_REPLY' => $forum_language->get('forum', 'last_reply'),
    'BY' => $forum_language->get('forum', 'by'),
    'IN' => $forum_language->get('forum', 'in'),
    'VIEWS' => $forum_language->get('forum', 'views'),
    'TOPICS' => $forum_language->get('forum', 'topics'),
    'POSTS' => $forum_language->get('forum', 'posts'),
    'STATISTICS' => $forum_language->get('forum', 'statistics'),
    'OVERVIEW' => $forum_language->get('forum', 'overview'),
    'LATEST_DISCUSSIONS_TITLE' => $forum_language->get('forum', 'latest_discussions'),
    'NO_TOPICS' => $forum_language->get('forum', 'no_topics_short'),
]);

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
                            $forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeAgo->inWords($forums[$key]['subforums'][$subforum_id]->last_post->post_date, $language);
                            $forums[$key]['subforums'][$subforum_id]->last_post->post_date = date(DATE_FORMAT, strtotime($forums[$key]['subforums'][$subforum_id]->last_post->post_date));
                        } else {
                            $forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeAgo->inWords($forums[$key]['subforums'][$subforum_id]->last_post->created, $language);
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

$template->getEngine()->addVariables([
    'FORUMS' => $forums,
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'SUBFORUMS' => $forum_language->get('forum', 'subforums'),
    'FORUM_INDEX_LINK' => URL::build('/forum'),
    'FORUM_SPAM_WARNING_TITLE' => $language->get('general', 'warning'),
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

$template->getEngine()->addVariables([
    'WIDGETS_LEFT' => $widgets->getWidgets('left'),
    'WIDGETS_RIGHT' => $widgets->getWidgets('right'),
]);

require ROOT_PATH . '/core/templates/navbar.php';
require ROOT_PATH . '/core/templates/footer.php';

// Display template
$template->displayTemplate('forum/forum_index');
