<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Set the page name for the active link in navbar
$page = "forum";

$forum = new Forum();
$timeago = new Timeago();
$pagination = new Pagination();

require('core/includes/paginate.php'); // Get number of topics on a page

if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

$fid = (int) $_GET['fid'];

// Does the forum exist, and can the user view it?
$list = $forum->forumExist($fid, $user->data()->group_id);
if(!$list){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}


// Get page
if(isset($_GET['p'])){
	if(!is_numeric($_GET['p'])){
		Redirect::to("/forum");
		die();
	} else {
		if($_GET['p'] == 1){ 
			// Avoid bug in pagination class
			Redirect::to('/forum/view_forum/?fid=' . $fid);
			die();
		}
		$p = $_GET['p'];
	}
} else {
	$p = 1;
}

// Get data from the database
$forum_query = $queries->getWhere("forums", array("id", "=", $fid));
$forum_query = $forum_query[0];

// Is it a parent forum? If so, the user shouldn't be browsing it
if($forum_query->parent == 0){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

// Get all topics
$topics = $queries->orderWhere("topics", "forum_id = ". $fid . " AND sticky = 0", "topic_reply_date", "DESC");

// Get sticky topics
$stickies = $queries->orderWhere("topics", "forum_id = " . $fid . " AND sticky = 1", "topic_reply_date", "DESC");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> Forum - <?php echo htmlspecialchars($forum_query->forum_title); ?>">
    <meta name="author" content="Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo $navbar_language['forum'] . ' - ' . htmlspecialchars($forum_query->forum_title); ?></title>
	
	<?php
	// Generate header and navbar content
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>

  <body>

	<?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	
	if(Session::exists('success_post')){
		echo '<div class="container"><center>' . Session::flash('success_post') . '</center></div>';
	}
	
	// Get forum layout (latest discussions or table view)
	$forum_layout = $queries->getWhere("settings", array("name", "=", "forum_layout"));
	$forum_layout = $forum_layout[0]->value;
	if($forum_layout == '1'){
		if(!count($stickies) && !count($topics)){
			// Any subforums?
			$subforums = $queries->getWhere('forums', array('parent', '=', $fid));
			if(count($subforums)){
				// string to contain list of subforums
				$subforum_string = '';
				
				// append subforums to string
				foreach($subforums as $subforum){
					// Get number of topics
					$subforum_topics = $queries->getWhere('topics', array('forum_id', '=', $subforum->id));
					$subforum_topics = count($subforum_topics);
					
					if($forum->forumExist($subforum->id, $user->data()->group_id)){
						$subforum_string .= '<a href="/forum/view_forum/?fid=' . $subforum->id . '">' . htmlspecialchars($subforum->forum_title) . '</a> <span class="badge" rel="tooltip" data-trigger="hover" data-original-title="' . $subforum_topics . ' ' . $forum_language['topics'] . '">' . $subforum_topics . '</span>, ';
					}
					
					$subforum_string = rtrim($subforum_string, ", "); // remove the last comma
					
				}
			} else {
				$subforum_string = '';
			}
			
			$smarty->assign('SUBFORUMS', $subforum_string);
			$smarty->assign('SUBFORUMS_LANGUAGE', $forum_language['subforums']);
			
			// No topics yet
			$smarty->assign('FORUM_TITLE', htmlspecialchars($forum_query->forum_title));
			$smarty->assign('NO_TOPICS', $forum_language['no_topics']);
			
			if($user->isLoggedIn() && $forum->canPostTopic($fid, $user->data()->group_id)){ 
				$smarty->assign('NEW_TOPIC_BUTTON', '<a style="display: inline;" href="/forum/new_topic/?fid=' . $fid . '" class="btn btn-primary">' . $forum_language['new_topic'] . '</a>');
			} else {
				$smarty->assign('NEW_TOPIC_BUTTON', '');
			}

			$smarty->assign('FORUMS', $forum_language['forums']);
			
			// Forums sidebar
			$forums = $forum->listAllForums($user->data()->group_id, true); // second parameter states we're in latest discussions view
			$sidebar_forums = array();
			foreach($forums as $key => $item){
				$item = array_filter($item);
				if(!empty($item)){
					foreach($item as $sub_forum){
						// Get forum ID
						$forum_id = $queries->getWhere('forums', array('forum_title', '=', $sub_forum));
						$forum_id = $forum_id[0]->id;
						
						$sidebar_forums[$key][] = array(
							'id' => $forum_id,
							'title' => $sub_forum
						);
					}
				}
			}
			
			$smarty->assign('SIDEBAR_FORUMS', $sidebar_forums);
			$smarty->assign('STATISTICS', $forum_language['statistics']);
			$smarty->assign('OVERVIEW', $forum_language['overview']);
			$smarty->assign('LATEST_DISCUSSIONS_TITLE', $forum_language['latest_discussions']);
			
			// Statistics
			$users_query = $queries->orderAll('users', 'joined', 'DESC');
			$users_registered = '<strong>' . $forum_language['users_registered'] . '</strong> ' . count($users_query);
			$latest_member = '<strong>' . $forum_language['latest_member'] . '</strong> <a href="/profile/' . htmlspecialchars($users_query[0]->mcname) . '">' . htmlspecialchars($users_query[0]->username) . '</a>';
			$users_query = null;
			
			$smarty->assign('USERS_REGISTERED', $users_registered);
			$smarty->assign('LATEST_MEMBER', $latest_member);
			
			$smarty->display('styles/templates/' . $template . '/view_forum_no_discussions.tpl');
		} else {
			// Any subforums?
			$subforums = $queries->getWhere('forums', array('parent', '=', $fid));
			if(count($subforums)){
				// string to contain list of subforums
				$subforum_string = '';
				
				// append subforums to string
				foreach($subforums as $subforum){
					// Get number of topics
					$subforum_topics = $queries->getWhere('topics', array('forum_id', '=', $subforum->id));
					$subforum_topics = count($subforum_topics);
					
					if($forum->forumExist($subforum->id, $user->data()->group_id)){
						$subforum_string .= '<a href="/forum/view_forum/?fid=' . $subforum->id . '">' . htmlspecialchars($subforum->forum_title) . '</a> <span class="badge" rel="tooltip" data-trigger="hover" data-original-title="' . $subforum_topics . ' ' . $forum_language['topics'] . '">' . $subforum_topics . '</span>, ';
					}
				}
				
				$subforum_string = rtrim($subforum_string, ", "); // remove the last comma
				
			} else {
				$subforum_string = '';
			}
			
			$smarty->assign('SUBFORUMS', $subforum_string);
			$smarty->assign('SUBFORUMS_LANGUAGE', $forum_language['subforums']);
			
			// Can the user post here?
			if($user->isLoggedIn() && $forum->canPostTopic($fid, $user->data()->group_id)){ 
				$smarty->assign('NEW_TOPIC_BUTTON', '<a style="display: inline;" href="/forum/new_topic/?fid=' . $fid . '" class="btn btn-primary">' . $forum_language['new_topic'] . '</a>');
			} else {
				$smarty->assign('NEW_TOPIC_BUTTON', '');
			}
			
			$sticky_array = array();
			// Assign sticky threads to smarty variable
			foreach($stickies as $sticky){
				// Get number of replies to a topic
				$replies = $queries->getWhere("posts", array("topic_id", "=", $sticky->id));
				$replies = count($replies);
				
				// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
				$last_user_avatar = $queries->getWhere('users', array("id", "=", $sticky->topic_last_user));
				$last_user_avatar = $last_user_avatar[0]->has_avatar;
				if($last_user_avatar == '0'){ 
					$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . htmlspecialchars($user->IdToMCName($sticky->topic_last_user)) . '/30.png" />';
				} else { 
					$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($sticky->topic_last_user, "../") . '" />';
				}
				
				// Is there a label?
				if($sticky->label !== null && $sticky->label !== ''){ // yes
					// Get label
					$label = $queries->getWhere('forums_topic_labels', array('id', '=', $sticky->label));
					$label = '<span class="label label-' . htmlspecialchars($label[0]->label) . '">' . htmlspecialchars($label[0]->name) . '</span>';
				} else { // no
					$label = '';
				}
				
				// Add to array
				$sticky_array[] = array(
					'topic_title' => htmlspecialchars($sticky->topic_title),
					'topic_id' => $sticky->id,
					'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_date), $time_language),
					'topic_created' => date('d M Y, H:i', $sticky->topic_date),
					'topic_created_username' => htmlspecialchars($user->IdToName($sticky->topic_last_user)),
					'topic_created_mcname' => htmlspecialchars($user->IdToMCName($sticky->topic_last_user)),
					'views' => $sticky->topic_views,
					'locked' => $sticky->locked,
					'posts' => $replies,
					'last_reply_avatar' => $last_reply_avatar,
					'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $sticky->topic_reply_date), $time_language),
					'last_reply' => date('d M Y, H:i', $sticky->topic_reply_date),
					'last_reply_username' => htmlspecialchars($user->IdToName($sticky->topic_last_user)),
					'last_reply_mcname' => htmlspecialchars($user->IdToMCName($sticky->topic_last_user)),
					'label' => $label
				);
			}
			// Clear out variables
			$stickies = null;
			$sticky = null;
			
			// Latest discussions
			// PAGINATION
			// Set current page and number of records
			$pagination->setCurrent($p);
			$pagination->setTotal(count($topics));
			$pagination->alwaysShowPagination();

			// Get number of topics we should display on the page
			$paginate = PaginateArray($p);

			$n = $paginate[0];
			$f = $paginate[1];
			
			// Get the number we need to finish on ($d)
			if(count($topics) > $f){
				$d = $p * 10;
			} else {
				$d = count($topics) - $n;
				$d = $d + $n;
			}
			
			$template_array = array();
			// Get a list of all topics from the forum, and paginate
			while($n < $d){
				// Get number of replies to a topic
				$replies = $queries->getWhere("posts", array("topic_id", "=", $topics[$n]->id));
				$replies = count($replies);
				
				// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
				$last_user_avatar = $queries->getWhere('users', array("id", "=", $topics[$n]->topic_last_user));
				$last_user_avatar = $last_user_avatar[0]->has_avatar;
				if($last_user_avatar == '0'){ 
					$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . htmlspecialchars($user->IdToMCName($topics[$n]->topic_last_user)) . '/30.png" />';
				} else { 
					$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($topics[$n]->topic_last_user, "../") . '" />';
				}
				
				// Is there a label?
				if($topics[$n]->label !== null && $topics[$n]->label !== ''){ // yes
					// Get label
					$label = $queries->getWhere('forums_topic_labels', array('id', '=', $topics[$n]->label));
					$label = '<span class="label label-' . htmlspecialchars($label[0]->label) . '">' . htmlspecialchars($label[0]->name) . '</span>';
				} else { // no
					$label = '';
				}
				
				// Add to array
				$template_array[] = array(
					'topic_title' => htmlspecialchars($topics[$n]->topic_title),
					'topic_id' => $topics[$n]->id,
					'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $topics[$n]->topic_date), $time_language),
					'topic_created' => date('d M Y, H:i', $topics[$n]->topic_date),
					'topic_created_username' => htmlspecialchars($user->IdToName($topics[$n]->topic_last_user)),
					'topic_created_mcname' => htmlspecialchars($user->IdToMCName($topics[$n]->topic_last_user)),
					'locked' => $topics[$n]->locked,
					'views' => $topics[$n]->topic_views,
					'posts' => $replies,
					'last_reply_avatar' => $last_reply_avatar,
					'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $topics[$n]->topic_reply_date), $time_language),
					'last_reply' => date('d M Y, H:i', $topics[$n]->topic_reply_date),
					'last_reply_username' => htmlspecialchars($user->IdToName($topics[$n]->topic_last_user)),
					'last_reply_mcname' => htmlspecialchars($user->IdToMCName($topics[$n]->topic_last_user)),
					'label' => $label
				);
				
				$n++;
			}
			
			// Assign pagination
			$smarty->assign('PAGINATION', $pagination->parse());
			
			// Assign forum title to variable
			$smarty->assign('FORUM_TITLE', htmlspecialchars($forum_query->forum_title));
		
			// Assign to Smarty variable
			$smarty->assign('STICKY_DISCUSSIONS', $sticky_array);
			$smarty->assign('LATEST_DISCUSSIONS', $template_array);
			
			// Assign language variables
			$smarty->assign('FORUMS', $forum_language['forums']);
			$smarty->assign('DISCUSSION', $forum_language['discussion']);
			$smarty->assign('STATS', $forum_language['stats']);
			$smarty->assign('LAST_REPLY', $forum_language['last_reply']);
			$smarty->assign('AGO', ''); // to be removed
			$smarty->assign('BY', $forum_language['by']);
			$smarty->assign('VIEWS', $forum_language['views']);
			$smarty->assign('POSTS', $forum_language['posts']);
			$smarty->assign('STATISTICS', $forum_language['statistics']);
			$smarty->assign('OVERVIEW', $forum_language['overview']);
			$smarty->assign('LATEST_DISCUSSIONS_TITLE', $forum_language['latest_discussions']);
			
			// Forums sidebar
			$forums = $forum->listAllForums($user->data()->group_id, true); // second parameter states we're in latest discussions view
			$sidebar_forums = array();
			foreach($forums as $key => $item){
				$item = array_filter($item);
				if(!empty($item)){
					foreach($item as $sub_forum){
						// Get forum ID
						$forum_id = $queries->getWhere('forums', array('forum_title', '=', $sub_forum));
						$forum_id = $forum_id[0]->id;
						
						$sidebar_forums[$key][] = array(
							'id' => $forum_id,
							'title' => $sub_forum
						);
					}
				}
			}
			
			$smarty->assign('SIDEBAR_FORUMS', $sidebar_forums);
			
			// Statistics
			$users_query = $queries->orderAll('users', 'joined', 'DESC');
			$users_registered = '<strong>' . $forum_language['users_registered'] . '</strong> ' . count($users_query);
			$latest_member = '<strong>' . $forum_language['latest_member'] . '</strong> <a href="/profile/' . htmlspecialchars($users_query[0]->mcname) . '">' . htmlspecialchars($users_query[0]->username) . '</a>';
			$users_query = null;
			
			$smarty->assign('USERS_REGISTERED', $users_registered);
			$smarty->assign('LATEST_MEMBER', $latest_member);
			
			// Load Smarty template
			$smarty->display('styles/templates/' . $template . '/view_forum_latest_discussions.tpl');
		}
	} else {
		// Any subforums?
		$subforums = $queries->getWhere('forums', array('parent', '=', $forum_query->id));
		if(count($subforums)){
			$subforums_exist = 0;
			
			// Loop through subforums and add to array
			$subforum_array = array();
			foreach($subforums as $subforum){
				if($forum->forumExist($subforum->id, $user->data()->group_id)){
					// Get stats
					$topics_count = $queries->getWhere('topics', array('forum_id', '=', $subforum->id));
					$topics_count = count($topics_count);
					$posts_count = $queries->getWhere('posts', array('forum_id', '=', $subforum->id));
					$posts_count = count($posts_count);
					
					// Get last user avatar
					$last_user_avatar = $queries->getWhere('users', array("id", "=", $subforum->last_user_posted));
					$last_user_avatar = $last_user_avatar[0]->has_avatar;
					if($last_user_avatar == '0'){ 
						$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . htmlspecialchars($user->IdToMCName($subforum->last_user_posted)) . '/30.png" />';
					} else { 
						$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($subforum->last_user_posted, "../") . '" />';
					}
					
					// Get last topic name
					$last_topic = $queries->getWhere('topics', array('id', '=', $subforum->last_topic_posted));
					$last_topic = $last_topic[0]->topic_title;
					
					$subforums_exist = 1;
					$subforum_array[] = array(
						'forum_id' => $subforum->id,
						'forum_title' => htmlspecialchars($subforum->forum_title),
						'forum_description' => htmlspecialchars($subforum->forum_description),
						'forum_topics' => $topics_count,
						'forum_posts' => $posts_count,
						'last_reply_avatar' => $last_reply_avatar,
						'last_reply_username' => htmlspecialchars($user->idToName($subforum->last_user_posted)),
						'last_reply_mcname' => htmlspecialchars($user->idToMCName($subforum->last_user_posted)),
						'last_topic_id' => $subforum->last_topic_posted,
						'last_topic_name' => htmlspecialchars($last_topic),
						'last_topic_time' => date('jS M Y, g:iA', strtotime($subforum->last_post_date))
					);
				}
			}
			
			$smarty->assign('SUBFORUMS_EXIST', 1);
			$smarty->assign('SUBFORUMS', $subforum_array);
		} else {
			// No subforums
			$smarty->assign('SUBFORUMS_EXIST', 0);
			$smarty->assign('SUBFORUMS', '');
		}
		
		$smarty->assign('TOPICS_LANGUAGE', $forum_language['topics']);
		$smarty->assign('NO_TOPICS', $forum_language['no_topics']);
		
		if(!count($stickies) && !count($topics)){
			// No topics yet
			$smarty->assign('FORUM_TITLE', htmlspecialchars($forum_query->forum_title));
			$smarty->assign('NO_TOPICS', $forum_language['no_topics']);
			
			if($user->isLoggedIn() && $forum->canPostTopic($fid, $user->data()->group_id)){ 
				$smarty->assign('NEW_TOPIC_BUTTON', '<a style="display: inline;" href="/forum/new_topic/?fid=' . $fid . '" class="btn btn-primary">' . $forum_language['new_topic'] . '</a>');
			} else {
				$smarty->assign('NEW_TOPIC_BUTTON', '');
			}
			
			$latest = $forum->getLatestDiscussions($user->data()->group_id);
			$latest_posts = array();
			
			$n = 0;
			foreach($latest as $item){
				if($n >= 5){
					break;
				}

				// Get avatar of user
				$last_user_avatar = '';
				if($item['topic_last_user'] != null){
					$last_user_avatar = $queries->getWhere('users', array('id', '=', $item['topic_last_user']));
					$last_user_avatar = $last_user_avatar[0]->has_avatar;
					if($last_user_avatar == '0'){ 
						$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . $user->IdToMCName($item['topic_last_user']) . '/30.png" />';
					} else { 
						$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($item['topic_last_user'], "../") . '" />';
					}
				}
				
				$latest_posts[] = array(
					'topic_id' => $item['id'],
					'topic_title' => htmlspecialchars($item['topic_title']),
					'topic_reply_rough' => $timeago->inWords(date('d M Y, H:i', $item['topic_reply_date']), $time_language),
					'topic_reply_date' => date('d M Y, H:i', $item['topic_reply_date']),
					'topic_last_user_avatar' => $last_reply_avatar,
					'topic_last_user_username' => htmlspecialchars($user->idToName($item['topic_last_user'])),
					'topic_last_user_mcname' => htmlspecialchars($user->idToMCName($item['topic_last_user']))
				);
				$n++;
			}
			// Assign to Smarty variable
			$smarty->assign('postsArray', $latest_posts);
			
			// Statistics
			$users_query = $queries->orderAll('users', 'joined', 'DESC');
			$users_registered = '<strong>' . $forum_language['users_registered'] . '</strong> ' . count($users_query);
			$latest_member = '<strong>' . $forum_language['latest_member'] . '</strong> <a href="/profile/' . htmlspecialchars($users_query[0]->mcname) . '">' . htmlspecialchars($users_query[0]->username) . '</a>';
			$users_query = null;
			
			$smarty->assign('LATEST_POSTS', $forum_language['latest_posts']);
			$smarty->assign('BY', $forum_language['by']);
			$smarty->assign('AGO', ''); // to be removed
			$smarty->assign('USERS_REGISTERED', $users_registered);
			$smarty->assign('LATEST_MEMBER', $latest_member);
			
			$smarty->display('styles/templates/' . $template . '/view_forum_no_discussions_table.tpl');
		} else {
			// Can the user post here?
			if($user->isLoggedIn() && $forum->canPostTopic($fid, $user->data()->group_id)){ 
				$smarty->assign('NEW_TOPIC_BUTTON', '<a style="display: inline;" href="/forum/new_topic/?fid=' . $fid . '" class="btn btn-primary">' . $forum_language['new_topic'] . '</a>');
			} else {
				$smarty->assign('NEW_TOPIC_BUTTON', '');
			}
			
			// Loop through topics and return an array to pass to the template
			$sticky_template_array = array(); // for sticky threads only
			$template_array = array();
			
			foreach($stickies as $item){
				// Get number of replies to a topic
				$replies = $queries->getWhere("posts", array("topic_id", "=", $item->id));
				$replies = count($replies);
				
				// Get avatar of user who last posted
				$last_user_avatar = '';
				if($item->topic_last_user != null){
					$last_user_avatar = $queries->getWhere('users', array('id', '=', $item->topic_last_user));
					$last_user_avatar = $last_user_avatar[0]->has_avatar;
					if($last_user_avatar == '0'){ 
						$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . $user->IdToMCName($item->topic_last_user) . '/30.png" />';
					} else { 
						$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($item->topic_last_user, "../") . '" />';
					}
				}
				
				$sticky_template_array[] = array(
					'topic_id' => $item->id,
					'topic_title' => htmlspecialchars($item->topic_title),
					'topic_poster' => htmlspecialchars($user->idToName($item->topic_creator)),
					'topic_poster_mcname' => htmlspecialchars($user->idToMCName($item->topic_creator)),
					'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $item->topic_date), $time_language),
					'topic_created' => date('d M Y, H:i', $item->topic_date),
					'last_reply_avatar' => $last_reply_avatar,
					'last_reply_username' => htmlspecialchars($user->idToName($item->topic_last_user)),
					'last_reply_mcname' => htmlspecialchars($user->idToMCName($item->topic_last_user)),
					'last_post_rough' => $timeago->inWords(date('d M Y, H:i', $item->topic_reply_date), $time_language),
					'last_post_created' => date('d M Y, H:i', $item->topic_reply_date),
					'views' => $item->topic_views,
					'posts' => $replies,
					'locked' => $item->locked
				);
			}
			
			// Latest discussions
			// PAGINATION
			// Set current page and number of records
			$pagination->setCurrent($p);
			$pagination->setTotal(count($topics));
			$pagination->alwaysShowPagination();

			// Get number of topics we should display on the page
			$paginate = PaginateArray($p);

			$n = $paginate[0];
			$f = $paginate[1];
			
			// Get the number we need to finish on ($d)
			if(count($topics) > $f){
				$d = $p * 10;
			} else {
				$d = count($topics) - $n;
				$d = $d + $n;
			}
			
			// Get a list of all topics from the forum, and paginate
			while($n < $d){
				// Get number of replies to a topic
				$replies = $queries->getWhere("posts", array("topic_id", "=", $topics[$n]->id));
				$replies = count($replies);
				
				// Get avatar of user who last posted
				$last_user_avatar = '';
				if($topics[$n]->topic_last_user != null){
					$last_user_avatar = $queries->getWhere('users', array('id', '=', $topics[$n]->topic_last_user));
					$last_user_avatar = $last_user_avatar[0]->has_avatar;
					if($last_user_avatar == '0'){ 
						$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . $user->IdToMCName($topics[$n]->topic_last_user) . '/30.png" />';
					} else { 
						$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($topics[$n]->topic_last_user, "../") . '" />';
					}
				}
				
				$template_array[] = array(
					'topic_id' => $topics[$n]->id,
					'topic_title' => htmlspecialchars($topics[$n]->topic_title),
					'topic_poster' => htmlspecialchars($user->idToName($topics[$n]->topic_creator)),
					'topic_poster_mcname' => htmlspecialchars($user->idToMCName($topics[$n]->topic_creator)),
					'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $topics[$n]->topic_date), $time_language),
					'topic_created' => date('d M Y, H:i', $topics[$n]->topic_date),
					'last_reply_avatar' => $last_reply_avatar,
					'last_reply_username' => htmlspecialchars($user->idToName($topics[$n]->topic_last_user)),
					'last_reply_mcname' => htmlspecialchars($user->idToMCName($topics[$n]->topic_last_user)),
					'last_post_rough' => $timeago->inWords(date('d M Y, H:i', $topics[$n]->topic_reply_date), $time_language),
					'last_post_created' => date('d M Y, H:i', $topics[$n]->topic_reply_date),
					'views' => $topics[$n]->topic_views,
					'posts' => $replies,
					'locked' => $topics[$n]->locked
				);
				
				$n++;
			}
			
			// Assign topics to variables
			$smarty->assign('STICKIES', $sticky_template_array);
			$smarty->assign('TOPICS', $template_array);
			
			// Assign language variables
			$smarty->assign('FORUM', $forum_language['forum']);
			$smarty->assign('STATS', $forum_language['stats']);
			$smarty->assign('STATISTICS', $forum_language['statistics']);
			$smarty->assign('LAST_POST', $forum_language['last_post']);
			$smarty->assign('POSTS', $forum_language['posts']);
			$smarty->assign('TOPIC', $forum_language['topic']);
			$smarty->assign('VIEWS', $forum_language['views']);
			$smarty->assign('LATEST_POSTS', $forum_language['latest_posts']);
			$smarty->assign('BY', $forum_language['by']);
			$smarty->assign('AGO', ''); // to be removed
			
			$latest = $forum->getLatestDiscussions($user->data()->group_id);
			$latest_posts = array();
			
			$n = 0;
			foreach($latest as $item){
				if($n >= 5){
					break;
				}

				// Get avatar of user
				$last_user_avatar = '';
				if($item['topic_last_user'] != null){
					$last_user_avatar = $queries->getWhere('users', array('id', '=', $item['topic_last_user']));
					$last_user_avatar = $last_user_avatar[0]->has_avatar;
					if($last_user_avatar == '0'){ 
						$last_reply_avatar = '<img class="img-centre img-rounded" src="https://cravatar.eu/avatar/' . $user->IdToMCName($item['topic_last_user']) . '/30.png" />';
					} else { 
						$last_reply_avatar = '<img class="img-centre img-rounded" style="width:30px; height:30px;" src="' .  $user->getAvatar($item['topic_last_user'], "../") . '" />';
					}
				}
				
				$latest_posts[] = array(
					'topic_id' => $item['id'],
					'topic_title' => htmlspecialchars($item['topic_title']),
					'topic_reply_rough' => $timeago->inWords(date('d M Y, H:i', $item['topic_reply_date']), $time_language),
					'topic_reply_date' => date('d M Y, H:i', $item['topic_reply_date']),
					'topic_last_user_avatar' => $last_reply_avatar,
					'topic_last_user_username' => htmlspecialchars($user->idToName($item['topic_last_user'])),
					'topic_last_user_mcname' => htmlspecialchars($user->idToMCName($item['topic_last_user']))
				);
				$n++;
			}
			// Assign to Smarty variable
			$smarty->assign('postsArray', $latest_posts);
			
			// Statistics
			$users_query = $queries->orderAll('users', 'joined', 'DESC');
			$users_registered = '<strong>' . $forum_language['users_registered'] . '</strong> ' . count($users_query);
			$latest_member = '<strong>' . $forum_language['latest_member'] . '</strong> <a href="/profile/' . htmlspecialchars($users_query[0]->mcname) . '">' . htmlspecialchars($users_query[0]->username) . '</a>';
			$users_query = null;
			
			$smarty->assign('USERS_REGISTERED', $users_registered);
			$smarty->assign('LATEST_MEMBER', $latest_member);
			
			$smarty->assign('FORUM_TITLE', htmlspecialchars($forum_query->forum_title));
			$smarty->assign('PAGINATION', $pagination->parse());
			
			// Load Smarty template
			$smarty->display('styles/templates/' . $template . '/view_forum_table.tpl');
		}
	}

	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	<script>
	$(document).ready(function(){
		$("[rel=tooltip]").tooltip({ placement: 'top'});
	});
	</script>
  </body>
</html>