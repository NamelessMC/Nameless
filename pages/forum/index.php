<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'true'){
	// Maintenance mode is enabled, only admins can view
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		require('pages/forum/maintenance.php');
		die();
	}
}
 
// Set the page name for the active link in navbar
$page = "forum";

// Initialise
$forum = new Forum();
$timeago = new Timeago();

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

if($user->isLoggedIn())
	$group_id = $user->data()->group_id;
else 
	$group_id = 0;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> Forum Index">
    <meta name="author" content="<?php echo $sitename; ?>">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'];
	
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

	// Get forum layout (latest discussions or table view)
	$forum_layout = $queries->getWhere("settings", array("name", "=", "forum_layout"));
	$forum_layout = $forum_layout[0]->value;
	
	// Breadcrumbs and search bar - same for latest discussions view + table view
	$breadcrumbs = '
	<ol class="breadcrumb">
	  <li><a href="/forum">' . $forum_language['home'] . '</a></li>
	</ol>';
	$smarty->assign('BREADCRUMBS', $breadcrumbs);
	
	// Search bar
	$search = '
	<form class="form-horizontal" role="form" method="post" action="/forum/search/">
	  <div class="input-group">
	    <input type="text" class="form-control input-sm" name="forum_search" placeholder="' . $general_language['search'] . '">
		<input type="hidden" name="token" value="' . Token::generate() . '">
	    <span class="input-group-btn">
		  <button type="submit" class="btn btn-default btn-sm">
            <i class="fa fa-search"></i>
          </button>
	    </span>
	  </div>
	</form>
	';
	$smarty->assign('SEARCH_FORM', $search);
	
	// Server status module
	if(isset($status_enabled->value) && $status_enabled->value == 'true'){
		// Query the server
		// Get the main IP
		$main_ip = $queries->getWhere('mc_servers', array('is_default', '=', 1));
		$pre17 	 = $main_ip[0]->pre;
		$query_ip = htmlspecialchars($main_ip[0]->query_ip);
		$main_ip = htmlspecialchars($main_ip[0]->ip);
		
		/*
		 *  Get port of Minecraft server
		 */
		$parts = explode(':', $query_ip);
		if(count($parts) == 1){
			$domain = $parts[0];
			$default_ip = $parts[0];
			$default_port = 25565;
		} else if(count($parts) == 2){
			$domain = $parts[0];
			$default_ip = $parts[0];
			$default_port = $parts[1];
			$port = $parts[1];
		} else {
			echo 'Invalid Query IP';
			die();
		}

		// Get IP to display
		$parts = explode(':', $main_ip);
		if(count($parts) == 1){
			$display_domain = $parts[0];
		} else if(count($parts) == 2){
			$display_domain = $parts[0];
			$display_port = $parts[1];
		} else {
			echo 'Invalid Display IP';
			die();
		}

		if((!isset($dsplay_port))||($display_port == "25565")){
			$address = $display_domain;
		} else {
			$address = $display_domain . ':' . $port;
		}
		
		// Query the main IP
		// Are we using the built-in query or an external API?
		$external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
		$external_query = $external_query[0]->value;
		
		if($external_query == 'false'){
			// Built in query, continue as normal
			require('core/integration/status/global.php'); 
		} else {
			// External query
			$cache = new Cache();
			require('core/integration/status/global_external.php');
		}
		
		if(empty($Info)){
			// Unable to query, offline
			$smarty->assign('MAIN_ONLINE', 0);
		} else {
			// Able to query, online
			$smarty->assign('MAIN_ONLINE', 1);
		}
		
		// Player count
		if($pre17 == 0){
			if(empty($Info['players']['max'])){
				$player_count = $Info['players']['online'];
			} else {
				$player_count = $Info['players']['online'] . ' / ' . $Info['players']['max'];
			}
		} else {
			if(empty($Info['MaxPlayers'])){
				$player_count = $Info['Players'];
			} else {
				$player_count = $Info['Players'] . ' / ' . $Info['MaxPlayers'];
			}
		}
		$smarty->assign('PLAYER_COUNT', htmlspecialchars($player_count));
		
		// Assign timer to variable
		if(isset($Timer)){
			$smarty->assign('TIMER', $Timer . $time_language['seconds_short']);
		} else {
			$smarty->assign('TIMER', 'n/a');
		}
		
		$smarty->assign('SERVER_STATUS', $general_language['server_status']);
		$smarty->assign('STATUS', $general_language['status']);
		$smarty->assign('ONLINE', $general_language['online']);
		$smarty->assign('OFFLINE', $general_language['offline']);
		$smarty->assign('PLAYERS_ONLINE', $general_language['players_online']);
		$smarty->assign('QUERIED_IN', $general_language['queried_in']);
		
	} else {
		// Module disabled, assign empty values
		$smarty->assign('SERVER_STATUS', '');
	}
	
    // List online users
    $online_users = $queries->getWhere('users', array('last_online', '>', strtotime("-10 minutes")));
    if(count($online_users)){
	    $online_users_string = '';
	    foreach($online_users as $online_user){
		    $online_users_string .= '<a href="/profile/' . htmlspecialchars($online_user->mcname) . '">' . htmlspecialchars($online_user->username) . '</a>, ';
	    }
	    $smarty->assign('ONLINE_USERS_LIST', rtrim($online_users_string, ', '));
    } else {
	    // Nobody online
	    $smarty->assign('ONLINE_USERS_LIST', $forum_language['no_users_online']);
    }
	$smarty->assign('ONLINE_USERS', $forum_language['online_users']);
	
	if($forum_layout == '1'){
		// Generate latest posts to pass to template
		$discussions = $forum->getLatestDiscussions($group_id);

		$n = 0;
		// Calculate the number of discussions to display (10 max)
		if(count($discussions) <= 10){
			$limit = count($discussions);
		} else {
			$limit = 10;
		}

		$template_array = array();
		
		// Initialise HTMLPurifier
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		// Generate an array to pass to template
		while($n < $limit){
			// Get the name of the forum from the ID
			$forum_name = $queries->getWhere('forums', array('id', '=', $discussions[$n]['forum_id']));
			$forum_name = $purifier->purify(htmlspecialchars_decode($forum_name[0]->forum_title));
			
			// Get the number of replies
			$posts = $queries->getWhere('posts', array('topic_id', '=', $discussions[$n]['id']));
			$posts = count($posts);
			
			// Get a string containing HTML code for a user's avatar. This depends on whether custom avatars are enabled or not, and also which Minecraft avatar source we're using
			$last_reply_avatar = '<img class="img-centre img-rounded" style="max-height:30px;max-width:30px;" src="' .  $user->getAvatar($discussions[$n]['topic_last_user'], "../", 30) . '" />';
			
			// Is there a label?
			if($discussions[$n]['label'] != 0){ // yes
				// Get label
				$label = $queries->getWhere('forums_topic_labels', array('id', '=', $discussions[$n]['label']));
				$label = '<span class="label label-' . htmlspecialchars($label[0]->label) . '">' . htmlspecialchars($label[0]->name) . '</span>';
			} else { // no
				$label = '';
			}
			
			// Add to array
			$template_array[] = array(
				'topic_title' => htmlspecialchars($discussions[$n]['topic_title']),
				'topic_id' => $discussions[$n]['id'],
				'topic_created_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_date']), $time_language),
				'topic_created' => date('d M Y, H:i', $discussions[$n]['topic_date']),
				'topic_created_username' => htmlspecialchars($user->IdToName($discussions[$n]['topic_creator'])),
				'topic_created_mcname' => htmlspecialchars($user->IdToMCName($discussions[$n]['topic_creator'])),
				'locked' => $discussions[$n]['locked'],
				'forum_name' => $forum_name,
				'forum_id' => $discussions[$n]['forum_id'],
				'views' => $discussions[$n]['topic_views'],
				'posts' => $posts,
				'last_reply_avatar' => $last_reply_avatar,
				'last_reply_rough' => $timeago->inWords(date('d M Y, H:i', $discussions[$n]['topic_reply_date']), $time_language),
				'last_reply' => date('d M Y, H:i', $discussions[$n]['topic_reply_date']),
				'last_reply_username' => htmlspecialchars($user->IdToName($discussions[$n]['topic_last_user'])),
				'last_reply_mcname' => htmlspecialchars($user->IdToMCName($discussions[$n]['topic_last_user'])),
				'label' => $label
			);
			
			$n++;
		}
		
		// Assign to Smarty variable
		$smarty->assign('LATEST_DISCUSSIONS', $template_array);
		
		// Assign language variables
		$smarty->assign('FORUMS', $forum_language['forums']);
		$smarty->assign('DISCUSSION', $forum_language['discussion']);
		$smarty->assign('STATS', $forum_language['stats']);
		$smarty->assign('LAST_REPLY', $forum_language['last_reply']);
		$smarty->assign('AGO', ''); // to be removed
		$smarty->assign('BY', $forum_language['by']);
		$smarty->assign('IN', $forum_language['in']);
		$smarty->assign('VIEWS', $forum_language['views']);
		$smarty->assign('POSTS', $forum_language['posts']);
		$smarty->assign('STATISTICS', $forum_language['statistics']);
		$smarty->assign('OVERVIEW', $forum_language['overview']);
		$smarty->assign('LATEST_DISCUSSIONS_TITLE', $forum_language['latest_discussions']);
		
		// Forums sidebar
		$forums = $forum->listAllForums($group_id, true); // second parameter states we're in latest discussions view
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
		$smarty->display('styles/templates/' . $template . '/forum_index_latest_discussions.tpl');
		
	} else {
		// Table view - generate to pass to template
		$forums = $forum->orderAllForums($group_id);
		
		// Loop through forums, get stats and return an array to pass to the template
		$template_array = array();
		
		// Initialise HTMLPurifier
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		foreach($forums['parents'] as $parent){
			if(!isset($template_array[$parent['id']])) $template_array[$parent['id']] = $parent;
			
			foreach($forums['forums'] as $item){
				if($item['parent'] == $parent['id']){
					// Check it't not a subforum
					$parent_forum = $queries->getWhere('forums', array('id', '=', $item['parent']));
					if($parent_forum[0]->parent != 0) continue;
					
					// Not a subforum
					// Get stats
					$topics_count = $queries->getWhere("topics", array("forum_id", "=", $item["id"]));
					$topics_count = count($topics_count);
					
					// New way of couting posts, in this way post of deleted topics are not counted.
					$posts_count = $queries->getWhere("posts", array("forum_id", "=", $item["id"]));
					$count_posts = array();

					foreach ($posts_count as $row){
						// Get topic from posts
						$topic = $queries->getWhere("topics", array("id", "=", $row->topic_id));
						// Check if exists
						if (count($topic) != 0){
							$count_posts[] = $row;
						}
					}

                    // Count posts
					$posts_count = count($count_posts);
				
					// Get avatar of user who last posted
					$last_reply_avatar = '<img class="img-centre img-rounded" style="max-height:30px;max-width:30px;" src="' .  $user->getAvatar($item['last_user_posted'], "../", 30) . '" />';
					
					// Get the last topic posted in
					$last_topic = '';
					if($item['last_topic_posted'] !== null){
						$last_topic = $queries->getWhere('topics', array('id', '=', $item['last_topic_posted']));
						
						if(count($last_topic)){
							// Is there a label?
							if($last_topic[0]->label != 0){ // yes
								// Get label
								$label = $queries->getWhere('forums_topic_labels', array('id', '=', $last_topic[0]->label));
								$label = '<span class="label label-' . htmlspecialchars($label[0]->label) . '">' . htmlspecialchars($label[0]->name) . '</span>';
							} else { // no
								$label = '';
							}
							
							$last_topic = $last_topic[0]->topic_title;
						} else
							$last_topic = null;
					}
					
					// Subforums?
					$subforums = $queries->getWhere('forums', array('parent', '=', $item['id']));
					$subforum_string = '';
					if(count($subforums)){
						foreach($subforums as $subforum){
							if($forum->forumExist($subforum->id, $group_id)){
								$subforum_string .= '<i class="fa fa-folder"></i> <a href="/forum/view_forum/?fid=' . $subforum->id . '">' . htmlspecialchars($subforum->forum_title) . '</a>&nbsp;&nbsp';
							}
						}
					}
					
					$template_array[$parent['id']]['forums'][] = array(
						'forum_id' => $item['id'],
						'forum_type' => htmlspecialchars_decode($item['forum_type']),
						'forum_title' => $purifier->purify(htmlspecialchars_decode($item['forum_title'])),
						'forum_description' => $purifier->purify(htmlspecialchars_decode($item['forum_description'])),
						'forum_topics' => $topics_count,
						'forum_posts' => $posts_count,
						'last_reply_avatar' => $last_reply_avatar,
						'last_reply_username' => htmlspecialchars($user->idToName($item['last_user_posted'])),
						'last_reply_mcname' => htmlspecialchars($user->idToMCName($item['last_user_posted'])),
						'last_topic_id' => $item['last_topic_posted'],
						'last_topic_name' => ((!is_null($last_topic)) ? htmlspecialchars($last_topic) : ''),
						'last_topic_time' => date('jS M Y, g:iA', strtotime($item['last_post_date'])),
						'subforums' => $subforum_string,
						'label' => $label
					);
					
				} else continue;
			}
		}
		
		// Assign forums to variable
		$smarty->assign('FORUMS', $template_array);
		
		// Assign language variables
		$smarty->assign('FORUM', $forum_language['forum']);
		$smarty->assign('STATS', $forum_language['stats']);
		$smarty->assign('STATISTICS', $forum_language['statistics']);
		$smarty->assign('LAST_POST', $forum_language['last_post']);
		$smarty->assign('POSTS', $forum_language['posts']);
		$smarty->assign('TOPICS', $forum_language['topics']);
		$smarty->assign('NO_TOPICS', $forum_language['no_topics']);
		$smarty->assign('LATEST_POSTS', $forum_language['latest_posts']);
		$smarty->assign('BY', $forum_language['by']);
		$smarty->assign('AGO', ''); // to be removed
		
		$latest = $forum->getLatestDiscussions($group_id);
		$latest_posts = array();
		
		$n = 0;
		foreach($latest as $item){
			if($n >= 5){
				break;
			}

			// Get avatar of user
			$last_reply_avatar = '<img class="img-centre img-rounded" style="max-height:30px;max-width:30px;" src="' .  $user->getAvatar($item['topic_last_user'], "../", 30) . '" />';
			
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
		
		// Load Smarty template
		$smarty->display('styles/templates/' . $template . '/forum_index_table.tpl');
	}
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
  </body>
</html>
