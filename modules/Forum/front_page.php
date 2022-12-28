<?php
declare(strict_types=1);

/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  License: MIT
 *
 *  Forum module - front page module
 *
 * @var Cache $cache
 * @var Language $language
 * @var Smarty $smarty
 * @var Language $forum_language
 */

use GuzzleHttp\Exception\GuzzleException;

$cache->setCacheName('news_cache');
if ($cache->hasCashedData('news')) {
    $news = $cache->retrieve('news');
} else {
    $forum = new Forum();

    $latest_news = $forum->getLatestNews(); // Get latest 5 items

    $news = [];

    foreach ($latest_news as $item) {
        try {
            $post_user = new User($item['author']);
        } catch (GuzzleException $ignored) {
        }

        try {
            $news[] = [
                'id' => $item['topic_id'],
                'url' => URL::build('/forum/topic/' . urlencode($item['topic_id']) . '-' . $forum->titleToURL($item['topic_title'])),
                'date' => date(DATE_FORMAT, strtotime($item['topic_date'])),
                'time_ago' => $item['topic_date'],
                'title' => Output::getClean($item['topic_title']),
                'views' => $item['topic_views'],
                'replies' => $item['replies'],
                'author_id' => Output::getClean($item['author']),
                'author_url' => $post_user->getProfileURL(),
                'author_style' => $post_user->getGroupStyle(),
                'author_name' => $post_user->getDisplayName(true),
                'author_nickname' => $post_user->getDisplayName(),
                'author_avatar' => $post_user->getAvatar(64),
                'author_group' => Output::getClean($post_user->getMainGroup()->name),
                'author_group_html' => $post_user->getMainGroup()->group_html,
                'content' => EventHandler::executeEvent('renderPost', ['content' => $item['content']])['content'],
                'label' => $item['label'],
                'labels' => $item['labels']
            ];
        } catch (GuzzleException $ignored) {
        }
    }

    $cache->store('news', $news, 60);
}

$time_ago = new TimeAgo(TIMEZONE);
foreach ($news as $key => $item) {
    $news[$key]['time_ago'] = $time_ago->inWords($item['time_ago'], $language);
}

$smarty->assign('LATEST_ANNOUNCEMENTS', $forum_language->get('forum', 'latest_announcements'));
$smarty->assign('READ_FULL_POST', $forum_language->get('forum', 'read_full_post'));
$smarty->assign('NEWS', $news);
$smarty->assign('NO_NEWS', $forum_language->get('forum', 'no_news'));
