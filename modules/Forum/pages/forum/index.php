<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Forum index page
 */

require_once('modules/Forum/classes/Forum.php');
 
// Always define page name
define('PAGE', 'forum');

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
?>

<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $forum_language->get('forum', 'forum');
	require('core/templates/header.php'); 
	?>
  
  </head>

  <body>
    <?php 
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	
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
	
    // List online users
	// Check cache
	$cache->setCache('online_users');
	if($cache->isCached('online_users')){
		$online_users = $cache->retrieve('online_users');
	} else {
		$online_users = $queries->getWhere('users', array('last_online', '>', strtotime('-10 minutes')));
		$cache->store('online_users', $online_users, 120);
	}
	
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
	
	// Generate latest posts to pass to template
	// Check cache per user's group
	if($user_group){
		$cache_name = 'forum_discussions_' . $user_group . '-' . $secondary_groups;
	} else {
		$cache_name = 'forum_discussions_guest';
	}
	
	$cache->setCache($cache_name);
	
	if($cache->isCached('discussions')){
		$template_array = $cache->retrieve('discussions');
		
	} else {
		$discussions = $forum->getLatestDiscussions($user_group, $secondary_groups);
		
		$n = 0;
		// Calculate the number of discussions to display (10 max)
		if(count($discussions) <= 10){
			$limit = count($discussions);
		} else {
			$limit = 10;
		}

		$template_array = array();
		
		// Generate an array to pass to template
		while($n < $limit){
			// Get the name of the forum from the ID
			$forum_name = $queries->getWhere('forums', array('id', '=', $discussions[$n]['forum_id']));
			$forum_name = Output::getPurified(htmlspecialchars_decode($forum_name[0]->forum_title));
			
			// Get the number of replies
			$posts = $queries->getWhere('posts', array('topic_id', '=', $discussions[$n]['id']));
			$posts = count($posts);
			
			// Get the last reply user's avatar
			$last_reply_avatar = $user->getAvatar($discussions[$n]['topic_last_user'], "../", 64);
			
			// Is there a label?
			if($discussions[$n]['label'] != 0){ // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $discussions[$n]['label']));
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
				'topic_title' => Output::getClean($discussions[$n]['topic_title']),
				'topic_id' => $discussions[$n]['id'],
				'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_date']), $language->getTimeLanguage()),
				'topic_created' => date('d M Y, H:i', $discussions[$n]['topic_date']),
				'topic_created_username' => Output::getClean($user->idToNickname($discussions[$n]['topic_creator'])),
				'topic_created_mcname' => Output::getClean($user->idToName($discussions[$n]['topic_creator'])),
				'topic_created_style' => $user->getGroupClass($discussions[$n]['topic_creator']),
				'locked' => $discussions[$n]['locked'],
				'forum_name' => $forum_name,
				'forum_id' => $discussions[$n]['forum_id'],
				'views' => $discussions[$n]['topic_views'],
				'posts' => $posts,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_reply_date']), $language->getTimeLanguage()),
				'last_reply' => date('d M Y, H:i', $discussions[$n]['topic_reply_date']),
				'last_reply_username' => Output::getClean($user->idToNickname($discussions[$n]['topic_last_user'])),
				'last_reply_mcname' => Output::getClean($user->idToName($discussions[$n]['topic_last_user'])),
				'last_reply_style' => $user->getGroupClass($discussions[$n]['topic_last_user']),
				'label' => $label,
				'link' => URL::build('/forum/topic/' . $discussions[$n]['id'] . '-' . $forum->titleToURL($discussions[$n]['topic_title'])),
				'forum_link' => URL::build('/forum/view/' . $discussions[$n]['forum_id'] . '-' . $forum->titleToURL($forum_name)),
				'author_link' => URL::build('/profile/' . Output::getClean($user->idToName($discussions[$n]['topic_creator']))),
				'last_reply_link' => URL::build('/profile/' . Output::getClean($user->idToName($discussions[$n]['topic_last_user'])))
			);
			
			$n++;
		}
		
		$cache->store('discussions', $template_array, 60);
	}
	
	// Assign to Smarty variable
	$smarty->assign('LATEST_DISCUSSIONS', $template_array);
	
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
		$forums = $forum->listAllForums($user_group, $secondary_groups);
		
		// Loop through to get last poster avatars and to format a date
		if(count($forums)){
			foreach($forums as $key => $item){
				if(count($item['subforums'])){
					foreach($item['subforums'] as $subforum_id => $subforum){
						if(isset($subforum->last_post)){
							$forums[$key]['subforums'][$subforum_id]->last_post->avatar = $user->getAvatar($forums[$key]['subforums'][$subforum_id]->last_post->post_creator, '../', 64);
							$forums[$key]['subforums'][$subforum_id]->last_post->date_friendly = $timeago->inWords($forums[$key]['subforums'][$subforum_id]->last_post->post_date, $language->getTimeLanguage());
							$forums[$key]['subforums'][$subforum_id]->last_post->post_date = date('d M Y, H:i', strtotime($forums[$key]['subforums'][$subforum_id]->last_post->post_date));
							$forums[$key]['subforums'][$subforum_id]->last_post->user_style = $user->getGroupClass($forums[$key]['subforums'][$subforum_id]->last_post->post_creator);
						}
					}
				}
			}
		} else $forums = array();
		
		$cache->store('forums', $forums, 60);
	}
	
	$smarty->assign('FORUMS', $forums);
	
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
	$smarty->assign('FORUM_INDEX_LINK', URL::build('/forum'));
	
	// Load Smarty template
	$smarty->display('custom/templates/' . TEMPLATE . '/forum/forum_index.tpl'); 
	
	require('core/templates/scripts.php'); 
	?>
  </body>
</html>