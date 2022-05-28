<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  User "following topics" page
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_following_topics';
$page_title = $forum_language->get('forum', 'following_topics');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$forum = new Forum();
$timeago = new TimeAgo(TIMEZONE);

if (Input::exists() && Input::get('action') == 'purge') {
    if (Token::check(Input::get('token'))) {
        DB::getInstance()->query('DELETE FROM nl2_topics_following WHERE user_id = ?', [$user->data()->id]);
        Session::flash('success_post', $forum_language->get('forum', 'all_topics_unfollowed'));
    }
}

$groups = '(';
foreach ($user->getAllGroupIds() as $group_id) {
    $groups .= Output::getClean($group_id) . ',';
}
$groups = rtrim($groups, ',') . ')';
$topics = DB::getInstance()->query('SELECT nl2_topics.id AS id, nl2_topics.topic_title AS topic_title, nl2_topics.topic_creator AS topic_creator, nl2_topics.topic_date AS topic_date, nl2_topics.topic_last_user AS topic_last_user, nl2_topics.topic_reply_date AS topic_reply_date, nl2_topics_following.existing_alerts AS existing_alerts FROM nl2_topics LEFT JOIN nl2_topics_following ON nl2_topics.id = nl2_topics_following.topic_id WHERE deleted = 0 AND nl2_topics.id IN (SELECT topic_id FROM nl2_topics_following WHERE user_id = ?) AND forum_id IN (SELECT forum_id FROM nl2_forums_permissions WHERE group_id IN ' . $groups . ' AND `view` = 1) ORDER BY nl2_topics.topic_reply_date DESC', [$user->data()->id])->results();

// Pagination
$p = (isset($_GET['p']) && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
$paginator = new Paginator(
    $template_pagination ?? null,
    $template_pagination_left ?? null,
    $template_pagination_right ?? null
);
$results = $paginator->getLimited($topics, 10, $p, count($topics));
$pagination = $paginator->generate(7, URL::build('/user/following_topics/'));

if (count($topics)) {
    $smarty->assign('PAGINATION', $pagination);
} else {
    $smarty->assign('PAGINATION', '');
}

$template_array = [];
$authors = [];

foreach ($results->data as $nValue) {
    $topic = $nValue;

    // Topic author/last poster
    if (!array_key_exists($topic->topic_creator, $authors)) {
        $authors[$topic->topic_creator] = new User($topic->topic_creator);
    }
    if (!array_key_exists($topic->topic_last_user, $authors)) {
        $authors[$topic->topic_last_user] = new User($topic->topic_last_user);
    }

    $last_post = DB::getInstance()->query('SELECT id FROM nl2_posts WHERE deleted = 0 AND topic_id = ? ORDER BY created DESC LIMIT 1', [$topic->id])->first();

    $template_array[] = [
        'topic_title' => Output::getClean($topic->topic_title),
        'topic_date' => $timeago->inWords($topic->topic_date, $language),
        'topic_date_full' => date(DATE_FORMAT, $topic->topic_date),
        'topic_author_id' => Output::getClean($authors[$topic->topic_creator]->data()->id),
        'topic_author_nickname' => $authors[$topic->topic_creator]->getDisplayname(),
        'topic_author_username' => $authors[$topic->topic_creator]->getDisplayname(true),
        'topic_author_avatar' => $authors[$topic->topic_creator]->getAvatar(),
        'topic_author_style' => $authors[$topic->topic_creator]->getGroupStyle(),
        'topic_author_link' => URL::build('/profile/' . Output::getClean($authors[$topic->topic_creator]->getDisplayname(true))),
        'reply_author_id' => Output::getClean($authors[$topic->topic_last_user]->data()->id),
        'reply_author_nickname' => $authors[$topic->topic_last_user]->getDisplayname(),
        'reply_author_username' => $authors[$topic->topic_last_user]->getDisplayname(true),
        'reply_author_avatar' => $authors[$topic->topic_last_user]->getAvatar(),
        'reply_author_style' => $authors[$topic->topic_last_user]->getGroupStyle(),
        'reply_author_link' => URL::build('/profile/' . Output::getClean($authors[$topic->topic_last_user]->getDisplayname(true))),
        'reply_date' => $timeago->inWords($topic->topic_reply_date, $language),
        'reply_date_full' => date(DATE_FORMAT, $topic->topic_reply_date),
        'topic_link' => URL::build('/forum/topic/' . $topic->id . '-' . $forum->titleToURL($topic->topic_title)),
        'last_post_link' => URL::build('/forum/topic/' . $topic->id . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post->id),
        'unread' => $topic->existing_alerts == 1,
        'unfollow_link' => URL::build('/forum/topic/' . $topic->id, 'action=unfollow&return=list')
    ];
}

if (Session::exists('success_post')) {
    $smarty->assign('SUCCESS_MESSAGE', Session::flash('success_post'));
}

// Language values
$smarty->assign([
    'USER_CP' => $language->get('user', 'user_cp'),
    'FOLLOWING_TOPICS' => $forum_language->get('forum', 'following_topics'),
    'TOPICS_LIST' => $template_array,
    'UNFOLLOW_TOPIC' => $forum_language->get('forum', 'unfollow'),
    'UNFOLLOW_ALL' => $forum_language->get('forum', 'unfollow_all_topics'),
    'CONFIRM_UNFOLLOW' => $forum_language->get('forum', 'confirm_unfollow_all_topics'),
    'CLICK_TO_VIEW' => $language->get('user', 'click_here_to_view'),
    'NO_TOPICS' => $forum_language->get('forum', 'not_following_any_topics'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'SUCCESS' => $language->get('general', 'success'),
    'TOKEN' => Token::get()
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/following_topics.tpl', $smarty);
