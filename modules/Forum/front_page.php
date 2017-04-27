<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum module - front page module
 */

require('modules/Forum/classes/Forum.php');
$forum = new Forum();
$timeago = new Timeago();
$latest_news = $forum->getLatestNews(5); // Get latest 5 items

$news = array();

require('core/includes/emojione/autoload.php'); // Emojione
$emojione = new Emojione\Client(new Emojione\Ruleset());

foreach($latest_news as $item){
	$news[] = array(
		'id' => $item['topic_id'],
		'url' => URL::build('/forum/view_topic/', 'tid=' . $item['topic_id']),
		'date' => date('d M Y, H:i', strtotime($item['topic_date'])),
		'time_ago' => $timeago->inWords($item['topic_date'], $language->getTimeLanguage()),
		'title' => Output::getClean($item['topic_title']),
		'views' => $item['topic_views'],
		'replies' => $item['replies'],
		'author_url' => URL::build('/profile/' . $user->idToName($item['author'])),
		'author_style' => $user->getGroupClass($item['author']),
		'author_name' => Output::getClean($user->idToName($item['author'])),
		'author_nickname' => Output::getClean($user->idToNickname($item['author'])),
		'author_avatar' => $user->getAvatar($item["author"], "../", 25),
		'content' => Output::getPurified($emojione->unicodeToImage(htmlspecialchars_decode($item['content'])))
	);
}

$smarty->assign('LATEST_ANNOUNCEMENTS', $forum_language->get('forum', 'latest_announcements'));
$smarty->assign('READ_FULL_POST', $forum_language->get('forum', 'read_full_post'));
$smarty->assign('NEWS', array_reverse($news));
