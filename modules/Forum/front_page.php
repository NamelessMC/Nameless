<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum module - front page module
 */

$cache->setCache('news_cache');
if ($cache->isCached('news')) {
    $news = $cache->retrieve('news');
} else {
    require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
    $forum = new Forum();
    $timeago = new Timeago(TIMEZONE);

    $latest_news = $forum->getLatestNews(5); // Get latest 5 items

    $news = array();

    foreach ($latest_news as $item) {
        $post_user = new User($item['author']);

        $news[] = array(
            'id' => $item['topic_id'],
            'url' => URL::build('/forum/topic/' . $item['topic_id'] . '-' . $forum->titleToURL($item['topic_title'])),
            'date' => date('d M Y, H:i', strtotime($item['topic_date'])),
            'time_ago' => $timeago->inWords($item['topic_date'], $language->getTimeLanguage()),
            'title' => Output::getClean($item['topic_title']),
            'views' => $item['topic_views'],
            'replies' => $item['replies'],
            'author_id' => Output::getClean($item['author']),
            'author_url' => $post_user->getProfileURL(),
            'author_style' => $post_user->getGroupClass(),
            'author_name' => $post_user->getDisplayname(true),
            'author_nickname' => $post_user->getDisplayname(),
            'author_avatar' => $post_user->getAvatar(64),
            'author_group' => Output::getClean($post_user->getMainGroup()->name),
            'author_group_html' => $post_user->getMainGroup()->group_html,
            'content' => Output::getPurified($item['content']),
            'label' => $item['label'],
            'labels' => $item['labels']
        );
    }

    $cache->store('news', $news, 60);
}

$smarty->assign('LATEST_ANNOUNCEMENTS', $forum_language->get('forum', 'latest_announcements'));
$smarty->assign('READ_FULL_POST', $forum_language->get('forum', 'read_full_post'));
$smarty->assign('NEWS', $news);
