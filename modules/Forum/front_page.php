<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Forum module - front page module
 */

$cache->setCache('news_cache');
if($cache->isCached('news')){
	$news = $cache->retrieve('news');
} else {
	require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
	$forum = new Forum();
	$timeago = new Timeago(TIMEZONE);

	$latest_news = $forum->getLatestNews(5); // Get latest 5 items

	$news = array();

	foreach($latest_news as $item){
		$news[] = array(
			'id' => $item['topic_id'],
			'url' => URL::build('/forum/topic/' . $item['topic_id'] . '-' . $forum->titleToURL($item['topic_title'])),
			'date' => date('d M Y, H:i', strtotime($item['topic_date'])),
			'time_ago' => $timeago->inWords($item['topic_date'], $language->getTimeLanguage()),
			'title' => Output::getClean($item['topic_title']),
			'views' => $item['topic_views'],
			'replies' => $item['replies'],
			'author_id' => Output::getClean($item['author']),
			'author_url' => URL::build('/profile/' . $user->idToName($item['author'])),
			'author_style' => $user->getGroupClass($item['author']),
			'author_name' => Output::getClean($user->idToName($item['author'])),
			'author_nickname' => Output::getClean($user->idToNickname($item['author'])),
			'author_avatar' => $user->getAvatar($item["author"], "../", 64),
			'author_group' => $user->getGroupName($user->getGroup($item['author'])),
			'content' => Output::getPurified($item['content']),
			'label' => $item['label']
		);
	}

	$cache->store('news', $news, 60);
}

$smarty->assign('LATEST_ANNOUNCEMENTS', $forum_language->get('forum', 'latest_announcements'));
$smarty->assign('READ_FULL_POST', $forum_language->get('forum', 'read_full_post'));
$smarty->assign('NEWS', $news);