<?php
/**
 * Forum - front page module
 *
 * @author Samerton
 * @license MIT
 * @version 2.3.0
 *
 * @var Cache $cache
 * @var Language $forum_language
 * @var Language $language
 * @var TemplateBase $template
 * @var User $user
 */

$groups_key = implode('-', $user->getAllGroupIds());
$cache->setCache('news_cache');
$news = $cache->fetch('news-' . $groups_key, function () use ($user) {
    $forum = new Forum();

    $latest_news = $forum->getLatestNews(
        Settings::get('news_items_front_page', 5, 'forum'),
        $user->getAllGroupIds()
    ); // Get latest 5 items

    $news = [];

    foreach ($latest_news as $item) {
        $post_user = new User($item['author']);
        $render_event = new RenderContentEvent($item['content']);
        EventHandler::executeEvent($render_event);

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
            'author_name' => $post_user->getDisplayname(true),
            'author_nickname' => $post_user->getDisplayname(),
            'author_avatar' => $post_user->getAvatar(64),
            'author_group' => Output::getClean($post_user->getMainGroup()->name),
            'author_group_html' => $post_user->getMainGroup()->group_html,
            'content' => $render_event->content,
            'label' => $item['label'],
            'labels' => $item['labels']
        ];
    }

    return $news;
}, 60);

$timeAgo = new TimeAgo(TIMEZONE);
foreach ($news as $key => $item) {
    $news[$key]['time_ago'] = $timeAgo->inWords($item['time_ago'], $language);
}

$template->getEngine()->addVariables([
    'LATEST_ANNOUNCEMENTS' => $forum_language->get('forum', 'latest_announcements'),
    'READ_FULL_POST' => $forum_language->get('forum', 'read_full_post'),
    'NEWS' => $news,
    'NO_NEWS' => $forum_language->get('forum', 'no_news'),
]);
