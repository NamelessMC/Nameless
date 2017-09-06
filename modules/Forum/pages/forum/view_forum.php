<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  View forum page
 */

// Always define page name
define('PAGE', 'forum');

require_once('modules/Forum/classes/Forum.php');
$forum = new Forum();
$timeago = new Timeago(TIMEZONE);

require('core/includes/paginate.php'); // Get number of topics on a page

// Get forum ID
$fid = explode('/', $route);
$fid = $fid[count($fid) - 1];

if(!isset($fid[count($fid) - 1])){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

$fid = explode('-', $fid);
if(!is_numeric($fid[0])){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
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
$list = $forum->forumExist($fid, $user_group, $secondary_groups);
if(!$list){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
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

// Get all topics
$topics = $queries->orderWhere("topics", "forum_id = ". $fid . " AND sticky = 0 AND deleted = 0", "topic_reply_date", "DESC");

// Get sticky topics
$stickies = $queries->orderWhere("topics", "forum_id = " . $fid . " AND sticky = 1 AND deleted = 0", "topic_reply_date", "DESC");

?>

<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <meta name="description" content="<?php echo SITE_NAME; ?> Forum - Viewing forum: <?php echo Output::getClean($forum_query->forum_title); ?> - Page <?php echo $p; ?>">

    <!-- Site Properties -->
	<?php 
	$title = Output::getClean($forum_query->forum_title) . ' - ' . str_replace('{x}', $p, $language->get('general', 'page_x'));
	require('core/templates/header.php'); 
	?>
  
  </head>

  <body>
	<?php
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	
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
		'link' => URL::build('/forum/view/' . $forum_query->id . '-'. $forum->titleToURL($forum_query->forum_title))
	));
	if(!empty($parent_category) && $parent_category[0]->parent == 0){
		// Category
		$breadcrumbs[] = array(
			'id' => $parent_category[0]->id,
			'forum_title' => Output::getClean($parent_category[0]->forum_title),
			'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
		);
	} else if(!empty($parent_category)){
		// Parent forum, get its category
		$breadcrumbs[] = array(
			'id' => $parent_category[0]->id,
			'forum_title' => Output::getClean($parent_category[0]->forum_title),
			'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum-titleToURL($parent_category[0]->forum_title))
		);
		$parent = false;
		while($parent == false){
			$parent_category = $queries->getWhere('forums', array('id', '=', $parent_category[0]->parent));
			$breadcrumbs[] = array(
				'id' => $parent_category[0]->id,
				'forum_title' => Output::getClean($parent_category[0]->forum_title),
				'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum-titleToURL($parent_category[0]->forum_title))
			);
			if($parent_category[0]->parent == 0){
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
	if(isset($status_enabled->value) && $status_enabled->value == 'true'){
		// Todo
		$smarty->assign('SERVER_STATUS', '');
		
	} else {
		// Module disabled, assign empty values
		$smarty->assign('SERVER_STATUS', '');
	}
	
    // List online users
	// Todo: cache this
    $online_users = $queries->getWhere('users', array('last_online', '>', strtotime("-10 minutes")));
    if(count($online_users)){
	    $online_users_string = '';
	    foreach($online_users as $online_user){
		    $online_users_string .= '<a style="' . $user->getGroupClass($online_user->id) . '" href="' . URL::build('/profile/' . Output::getClean($online_user->username)) . '">' . Output::getClean($online_user->nickname) . '</a>, ';
	    }
	    $smarty->assign('ONLINE_USERS_LIST', rtrim($online_users_string, ', '));
    } else {
	    // Nobody online
	    $smarty->assign('ONLINE_USERS_LIST', $forum_language->get('forum', 'no_users_online'));
    }
	$smarty->assign('ONLINE_USERS', $forum_language->get('forum', 'online_users'));

	// Assignments
	$smarty->assign('FORUM_INDEX_LINK', URL::build('/forum'));
	
	// Any subforums?
	$subforums = $queries->getWhere('forums', array('parent', '=', $fid));
	
	$subforum_array = array();
	
	if(count($subforums)){
		// append subforums to string
		foreach($subforums as $subforum){
			// Get number of topics
			$latest_post = $queries->orderWhere('topics', 'forum_id = ' . $subforum->id . ' AND deleted = 0', 'topic_reply_date', 'DESC');
			$subforum_topics = count($latest_post);
			
			if($forum->forumExist($subforum->id, $user_group, $secondary_groups)){
				if(count($latest_post)){
					foreach($latest_post as $item){
						if($item->deleted == 0){
							$latest_post = $item;
							break;
						}
					}

					$latest_post_link = URL::build('/forum/topic/' . $latest_post->id . '-' . $forum->titleToURL($latest_post->topic_title));
					$latest_post_avatar = $user->getAvatar($latest_post->topic_last_user, "../", 30);
					$latest_post_title = Output::getClean($latest_post->topic_title);
					$latest_post_user = Output::getClean($user->idToNickname($latest_post->topic_last_user));
					$latest_post_user_link = URL::build('/profile/' . $user->idToName($latest_post->topic_last_user));
					$latest_post_style = $user->getGroupClass($latest_post->topic_last_user);
					$latest_post_date_timeago = $timeago->inWords(date('d M Y, H:i', $latest_post->topic_reply_date), $language->getTimeLanguage());
					$latest_post_time = date('d M Y, H:i', $latest_post->topic_reply_date);
					
					$latest_post = array(
						'link' => $latest_post_link,
						'title' => $latest_post_title,
						'last_user_avatar' => $latest_post_avatar,
						'last_user' => $latest_post_user,
						'last_user_style' => $latest_post_style,
						'last_user_link' => $latest_post_user_link,
						'timeago' => $latest_post_date_timeago,
						'time' => $latest_post_time
					);
				} else $latest_post = array();
				
				$subforum_array[] = array(
					'id' => $subforum->id,
					'title' => Output::getPurified(htmlspecialchars_decode($subforum->forum_title)),
					'topics' => $subforum_topics,
					'link' => URL::build('/forum/view/' . $subforum->id . '-' . $forum->titleToURL($subforum->forum_title)),
					'latest_post' => $latest_post
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
	
	// Can the user post here?
	if($user->isLoggedIn() && $forum->canPostTopic($fid, $user_group, $secondary_groups)){
		$smarty->assign('NEW_TOPIC_BUTTON', URL::build('/forum/new/', 'fid=' . $fid));
	} else {
		$smarty->assign('NEW_TOPIC_BUTTON', false);
	}
	
	$smarty->assign('NEW_TOPIC', $forum_language->get('forum', 'new_topic'));
	
	// Topics
	if(!count($stickies) && !count($topics)){
		// No topics yet
		$smarty->assign('NO_TOPICS_FULL', $forum_language->get('forum', 'no_topics'));
		
		if($user->isLoggedIn() && $forum->canPostTopic($fid, $user_group, $secondary_groups)){
			$smarty->assign('NEW_TOPIC_BUTTON', URL::build('/forum/new/', 'fid=' . $fid));
		} else {
			$smarty->assign('NEW_TOPIC_BUTTON', false);
		}
		
		$no_topics_exist = true;
		
	} else {
		// Topics/sticky topics exist
		
		$sticky_array = array();
		// Assign sticky threads to smarty variable
		foreach($stickies as $sticky){
			// Get number of replies to a topic
			$replies = $queries->getWhere('posts', array('topic_id', '=', $sticky->id));
			$replies = count($replies);
			
			// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
			$last_reply_avatar = $user->getAvatar($sticky->topic_last_user, "../", 30);
			
			// Is there a label?
			if($sticky->label != 0){ // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $sticky->label));
				if(count($label)){
					$label = $label[0];
				
					$label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
					if(count($label_html)){
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
				'views' => $sticky->topic_views,
				'locked' => $sticky->locked,
				'posts' => $replies,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_reply_date), $language->getTimeLanguage()),
				'last_reply' => date('d M Y, H:i', $sticky->topic_reply_date),
				'last_reply_username' => Output::getClean($user->idToNickname($sticky->topic_last_user)),
				'last_reply_mcname' => Output::getClean($user->idToName($sticky->topic_last_user)),
				'last_reply_style' => $user->getGroupClass($sticky->topic_last_user),
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
		
		if(count($topics))
			$smarty->assign('PAGINATION', $pagination);
		else
			$smarty->assign('PAGINATION', '');
		
		$template_array = array();
		// Get a list of all topics from the forum, and paginate
		for($n = 0; $n < count($results->data); $n++){
			// Get number of replies to a topic
			$replies = $queries->getWhere("posts", array("topic_id", "=", $topics[$n]->id));
			$replies = count($replies);
			
			// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
			$last_reply_avatar = $user->getAvatar($topics[$n]->topic_last_user, "../", 30);

			// Is there a label?
			if($topics[$n]->label != 0){ // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $topics[$n]->label));
				if(count($label)){
					$label = $label[0];
				
					$label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
					if(count($label_html)){
						$label_html = $label_html[0]->html;
						$label = str_replace('{x}', Output::getClean($label->name), $label_html);
					} else $label = '';
				} else $label = '';
			} else { // no
				$label = '';
			}
			
			// Add to array
			$template_array[] = array(
				'topic_title' => Output::getClean($topics[$n]->topic_title),
				'topic_id' => $topics[$n]->id,
				'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $topics[$n]->topic_date), $language->getTimeLanguage()),
				'topic_created' => date('d M Y, H:i', $topics[$n]->topic_date),
				'topic_created_username' => Output::getClean($user->idToNickname($topics[$n]->topic_creator)),
				'topic_created_mcname' => Output::getClean($user->idToName($topics[$n]->topic_creator)),
				'topic_created_style' => $user->getGroupClass($topics[$n]->topic_creator),
				'locked' => $topics[$n]->locked,
				'views' => $topics[$n]->topic_views,
				'posts' => $replies,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $topics[$n]->topic_reply_date), $language->getTimeLanguage()),
				'last_reply' => date('d M Y, H:i', $topics[$n]->topic_reply_date),
				'last_reply_username' => Output::getClean($user->idToNickname($topics[$n]->topic_last_user)),
				'last_reply_mcname' => Output::getClean($user->idToName($topics[$n]->topic_last_user)),
				'last_reply_style' => $user->getGroupClass($topics[$n]->topic_last_user),
				'label' => $label,
				'author_link' => URL::build('/profile/' . Output::getClean($user->idToName($topics[$n]->topic_creator))),
				'link' => URL::build('/forum/topic/' . $topics[$n]->id . '-' . $forum->titleToURL($topics[$n]->topic_title)),
				'last_reply_link' => URL::build('/profile/' . Output::getClean($user->idToName($topics[$n]->topic_last_user)))
			);
		}
	
		// Assign to Smarty variable
		$smarty->assign('STICKY_DISCUSSIONS', $sticky_array);
		$smarty->assign('LATEST_DISCUSSIONS', $template_array);
	}
	
	// Statistics
	// Check cache
	$cache->setCache('forum_stats');
	
	if($cache->isCached('stats')){
		$latest_member = $cache->retrieve('stats');
		$users_registered = $latest_member['users_registered'];
		$latest_member = $latest_member['latest_member'];
	} else {
		$users_query = $queries->orderAll('users', 'joined', 'DESC');
		$users_registered = str_replace('{x}', count($users_query), $forum_language->get('forum', 'users_registered'));
		$latest_member = str_replace('{x}', '<a style="' . $user->getGroupClass($users_query[0]->id) . '" href="' . URL::build('/profile/' . Output::getClean($users_query[0]->username)) . '">' . Output::getClean($users_query[0]->nickname) . '</a>', $forum_language->get('forum', 'latest_member'));
		$users_query = null;
		
		$cache->store('stats', array(
			'users_registered' => $users_registered,
			'latest_member' => $latest_member
		), 120);
	}
	
	$smarty->assign('USERS_REGISTERED', $users_registered);
	$smarty->assign('LATEST_MEMBER', $latest_member);
	
	// Load Smarty template
	if(isset($no_topics_exist)) $smarty->display('custom/templates/' . TEMPLATE . '/forum/view_forum_no_discussions.tpl'); 
	else $smarty->display('custom/templates/' . TEMPLATE . '/forum/view_forum.tpl');

	require('core/templates/scripts.php'); 
	?>
  </body>
</html>