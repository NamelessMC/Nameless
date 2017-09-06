<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  View topic page
 */

require_once('modules/Forum/classes/Forum.php');
 
// Set the page name for the active link in navbar
define('PAGE', 'forum');

$forum = new Forum();
$timeago = new Timeago(TIMEZONE);
$mentionsParser = new MentionsParser();

require('core/includes/paginate.php'); // Get number of replies on a page
require('core/includes/emojione/autoload.php'); // Emojione
require('core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML
$emojione = new Emojione\Client(new Emojione\Ruleset());

// Get topic ID
$tid = explode('/', $route);
$tid = $tid[count($tid) - 1];

if(!isset($tid[count($tid) - 1])){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

$tid = explode('-', $tid);
if(!is_numeric($tid[0])){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}
$tid = $tid[0];

// Does the topic exist, and can the user view it?
if($user->isLoggedIn()){
	$group_id = $user->data()->group_id;
	$secondary_groups = $user->data()->secondary_groups;
} else {
    $group_id = null;
    $secondary_groups = null;
}

$list = $forum->topicExist($tid, $group_id, $secondary_groups);
if(!$list){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

// Get the topic information
$topic = $queries->getWhere('topics', array('id', '=', $tid));
$topic = $topic[0];

if($topic->deleted == 1){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

// Get page
if(isset($_GET['p'])){
	if(!is_numeric($_GET['p'])){
		Redirect::to(URL::build('/forum'));
		die();
	} else {
		if($_GET['p'] == 1){ 
			// Avoid bug in pagination class
			Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)));
			die();
		}
		$p = $_GET['p'];
	}
} else {
	$p = 1;
}

// Is the URL pointing to a specific post?
if(isset($_GET['pid'])){
	$posts = $queries->getWhere('posts', array('topic_id', '=', $tid));
	if(count($posts)){
		$i = 0;
		while($i < count($posts)){
			if($posts[$i]->id == $_GET['pid']){
				$output = $i + 1;
				break;
			}
			$i++;
		}
		if(ceil($output / 10) != $p){
			Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'p=' . ceil($output / 10)) . '#post-' . $_GET['pid']);
			die();
		} else {
			Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)) . '#post-' . $_GET['pid']);
			die();
		}
		
	} else {
		Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
		die();
	}
}

// Assign author + title to Smarty variables
$smarty->assign(array(
	'TOPIC_TITLE' => Output::getClean($topic->topic_title),
	'TOPIC_AUTHOR_USERNAME' => Output::getClean($user->idToName($topic->topic_creator)),
	'TOPIC_AUTHOR_MCNAME' => Output::getClean($user->idToName($topic->topic_creator)),
	'TOPIC_ID' => $topic->id,
	'FORUM_ID' => $topic->forum_id
));

// Get all posts in the topic
$posts = $forum->getPosts($tid);

// Can the user post a reply in this topic?
if($user->isLoggedIn()){
	// Topic locked?
	if($topic->locked == 0 || $forum->canModerateForum($group_id, $topic->forum_id, $secondary_groups)){
		$can_reply = $forum->canPostReply($topic->forum_id, $group_id, $secondary_groups);
	} else {
		$can_reply = false;
	}
} else {
	$can_reply = false;
}

// Quick reply
if(Input::exists()) {
	if(!$user->isLoggedIn() && !$can_reply){ 
		Redirect::to(URL::build('/forum'));
		die();
	}
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'content' => array(
				'required' => true,
				'min' => 2,
				'max' => 20480
			)
		));
		if($validation->passed()){
			try {
				$cache->setCache('post_formatting');
				$formatting = $cache->retrieve('formatting');
				
				if($formatting == 'markdown'){
					$content = Michelf\Markdown::defaultTransform(Input::get('content'));
					$content = Output::getClean($content);
				} else $content = Output::getClean(Input::get('content'));
				
				$queries->create("posts", array(
					'forum_id' => $topic->forum_id,
					'topic_id' => $tid,
					'post_creator' => $user->data()->id,
					'post_content' => $content,
					'post_date' => date('Y-m-d H:i:s')
				));
				
				// Get last post ID
				$last_post_id = $queries->getLastId();
				$content = $mentionsParser->parse($user->data()->id, $content, $tid, $last_post_id, $forum_language->get('forum', 'user_tag'), $forum_language->get('forum', 'user_tag_info'));

				$queries->update("posts", $last_post_id, array(
					'post_content' => $content
				));
				
				$queries->update("forums", $topic->forum_id, array(
					'last_topic_posted' => $tid,
					'last_user_posted' => $user->data()->id,
					'last_post_date' => date('U')
				));
				$queries->update("topics", $tid, array(
					'topic_last_user' => $user->data()->id,
					'topic_reply_date' => date('U')
				));
				Session::flash('success_post', $forum_language->get('forum', 'post_successful'));
				Redirect::to(URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $last_post_id));
				die();
				
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			$error_string = "";
			foreach($validation->errors() as $error) {
				$error_string .= ucfirst($error) . '<br />';
			}
			Session::flash('failure_post', $error_string);
		}
	} else {
		// Invalid token - TODO: improve
		//echo 'Invalid token';

	}
}

// Generate a post token
if($user->isLoggedIn()){
	$token = Token::get();
}

// View count
if($user->isLoggedIn() || Cookie::exists('alert-box')){
	if(!Cookie::exists('nl-topic-' . $tid)) {
		$queries->increment("topics", $tid, "topic_views");
		Cookie::put("nl-topic-" . $tid, "true", 3600);
	}
} else {
	if(!Session::exists('nl-topic-' . $tid)){
		$queries->increment("topics", $tid, "topic_views");
		Session::put("nl-topic-" . $tid, "true");
	}
}
?>

<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> Forum - Topic: <?php echo Output::getClean($topic->topic_title); ?> - Page <?php echo $p; ?>">

    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
  $title = ((strlen(Output::getClean($topic->topic_title)) > 20) ? Output::getClean(substr($topic->topic_title, 0, 20)) . '...' : Output::getClean($topic->topic_title)) . ' - ' . str_replace('{x}', $p, $language->get('general', 'page_x'));
	require('core/templates/header.php'); 
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.sprites.css"/>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/css/emojionearea.min.css"/>
	
	<?php if($user->isLoggedIn()){ ?><script>var quotedPosts =[];</script><?php } ?>
  </head>

  <body>
	<?php 
	// Load navbar
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	
	// Assign Smarty variables to pass to template
	$forum_parent = $queries->getWhere('forums', array('id', '=', $topic->forum_id));
	$parent_category = $queries->getWhere('forums', array('id', '=', $forum_parent[0]->parent));
	
	$breadcrumbs = array(
		0 => array(
			'id' => 0,
			'forum_title' => Output::getClean($topic->topic_title),
			'active' => 1,
			'link' => URL::build('/forum/view/' . $topic->id . '-' . $forum->titleToURL($topic->topic_title))
		),
		1 => array(
			'id' => $forum_parent[0]->id,
			'forum_title' => Output::getClean($forum_parent[0]->forum_title),
			'link' => URL::build('/forum/view/'. $forum_parent[0]->id . '-' . $forum->titleToURL($forum_parent[0]->forum_title))
		)
	);
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
			'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
		);
		$parent = false;
		while($parent == false){
			$parent_category = $queries->getWhere('forums', array('id', '=', $parent_category[0]->parent));
			$breadcrumbs[] = array(
				'id' => $parent_category[0]->id,
				'forum_title' => Output::getClean($parent_category[0]->forum_title),
				'link' => URL::build('/forum/view/' . $parent_category[0]->id . '-' . $forum->titleToURL($parent_category[0]->forum_title))
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
	
	// Display session messages
	if(Session::exists('success_post')){
		$smarty->assign('SESSION_SUCCESS_POST', Session::flash('success_post'));
	}
	if(Session::exists('failure_post')){
		$smarty->assign('SESSION_FAILURE_POST', Session::flash('failure_post'));
	}
	
	// Display "new reply" button and "mod actions" if the user has access to them
	
	// Can the user post a reply?
	if($user->isLoggedIn() && $can_reply){
		$smarty->assign('CAN_REPLY', true);
		
		// Is the topic locked?
		if($topic->locked != 1){ // Not locked
			$smarty->assign('NEW_REPLY', $forum_language->get('forum', 'new_reply'));
		} else { // Locked
			$smarty->assign('LOCKED', true);
			if($forum->canModerateForum($group_id, $forum_parent[0]->id, $secondary_groups)){
				// Can post anyway
				$smarty->assign('NEW_REPLY', $forum_language->get('forum', 'new_reply'));
			} else {
				// Can't post
				$smarty->assign('NEW_REPLY', $forum_language->get('forum', 'topic_locked'));
			}
		}
	}
	
	// Is the user a moderator?
	$buttons = '<span class="pull-right">';
	if($user->isLoggedIn() && $forum->canModerateForum($group_id, $forum_parent[0]->id, $secondary_groups)){
		$smarty->assign(array(
			'CAN_MODERATE' => true,
			'MOD_ACTIONS' => $forum_language->get('forum', 'mod_actions'),
			'LOCK_URL' => URL::build('/forum/lock/', 'tid=' . $tid),
			'LOCK' => (($topic->locked == 1) ? $forum_language->get('forum', 'unlock_topic') : $forum_language->get('forum', 'lock_topic')),
			'MERGE_URL' => URL::build('/forum/merge/', 'tid=' . $tid),
			'MERGE' => $forum_language->get('forum', 'merge_topic'),
			'DELETE_URL' => URL::build('/forum/delete/', 'tid=' . $tid),
			'CONFIRM_DELETE' => $forum_language->get('forum', 'confirm_delete_topic'),
			'CONFIRM_DELETE_SHORT' => $language->get('general', 'confirm_delete'),
			'CONFIRM_DELETE_POST' => $forum_language->get('forum', 'confirm_delete_post'),
			'DELETE' => $forum_language->get('forum', 'delete_topic'),
			'MOVE_URL' => URL::build('/forum/move/', 'tid=' . $tid),
			'MOVE' => $forum_language->get('forum', 'move_topic'),
			'STICK_URL' => URL::build('/forum/stick/', 'tid=' . $tid),
			'STICK' => (($topic->sticky == 1) ? $forum_language->get('forum', 'unstick_topic') : $forum_language->get('forum', 'stick_topic')),
			'MARK_AS_SPAM' => $language->get('moderator', 'mark_as_spam'),
			'CONFIRM_SPAM_POST' => $language->get('moderator', 'confirm_spam')
		));

	}
	
	// Sharing
	$smarty->assign(array(
		'SHARE' => $forum_language->get('forum', 'share'),
		'SHARE_TWITTER' => $forum_language->get('forum', 'share_twitter'),
		'SHARE_TWITTER_URL' => 'https://twitter.com/intent/tweet?text=' . Output::getClean(Util::getSelfURL()) . URL::build('forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title)),
		'SHARE_FACEBOOK' => $forum_language->get('forum', 'share_facebook'),
		'SHARE_FACEBOOK_URL' => 'https://www.facebook.com/sharer/sharer.php?u=' . Output::getClean(Util::getSelfURL()) . URL::build('forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title))
	));
	
	// Pagination
	$paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
	$results = $paginator->getLimited($posts, 10, $p, count($posts));
	$pagination = $paginator->generate(7, URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), true));
	
	$smarty->assign('PAGINATION', $pagination);
	
	// Is Minecraft integration enabled?
	$mc_integration = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
	
	// Replies
	$replies = array();
	// Display the correct number of posts	
	for($n = 0; $n < count($results->data); $n++){
	  	// Get user's group HTML formatting and their signature
	  	$user_groups = $user->getAllGroups($results->data[$n]->post_creator, 'true');
		$signature = $user->getSignature($results->data[$n]->post_creator);
	
		// Panel heading content
		$url = URL::build('/forum/topic/' . $tid . '-' . $forum->titleToURL($topic->topic_title), 'pid=' . $results->data[$n]->id);
		
		if($n != 0) $heading = $forum_language->get('forum', 're') . Output::getClean($topic->topic_title);
		else $heading = Output::getClean($topic->topic_title);
		
		// Avatar
		$post_user = $queries->getWhere('users', array('id', '=', $results->data[$n]->post_creator));
		$avatar = $user->getAvatar($results->data[$n]->post_creator, '../', 500);
		
		// Which buttons do we need to display?
		$buttons = array();
		
		if($user->isLoggedIn()){
			// Assign token
			$smarty->assign('TOKEN', $token);
			
			// Edit button
			if($forum->canModerateForum($group_id, $forum_parent[0]->id, $secondary_groups)){
				$buttons['edit'] = array(
					'URL' => URL::build('/forum/edit/', 'pid=' . $results->data[$n]->id . '&amp;tid=' . $tid),
					'TEXT' => $forum_language->get('forum', 'edit')
				);
			} else if($user->data()->id == $results->data[$n]->post_creator) { 
				if($topic->locked != 1){ // Can't edit if topic is locked
					$buttons['edit'] = array(
						'URL' => URL::build('/forum/edit/', 'pid=' . $results->data[$n]->id . '&amp;tid=' . $tid),
						'TEXT' => $forum_language->get('forum', 'edit')
					);
				}
			} 
			
			// Delete button
			if($forum->canModerateForum($group_id, $forum_parent[0]->id, $secondary_groups)){
				$buttons['delete'] = array(
					'URL' => URL::build('/forum/delete_post/', 'pid=' . $results->data[$n]->id . '&amp;tid=' . $tid),
					'TEXT' => $language->get('general', 'delete'),
					'NUMBER' => $n
				);
				$buttons['spam'] = array(
					'URL' => URL::build('/forum/spam/'),
					'TEXT' => $language->get('moderator', 'spam')
				);
			}
			
			// Report button
			$buttons['report'] = array(
				'URL' => URL::build('/forum/report/'),
				'REPORT_TEXT' => $language->get('user', 'report_post_content'),
				'TEXT' => $language->get('general', 'report')
			);
			
			// Quote button
			if($can_reply){
				if($forum->canModerateForum($group_id, $forum_parent[0]->id, $secondary_groups) || $topic->locked != 1){
					$buttons['quote'] = array(
						'TEXT' => $forum_language->get('forum', 'quote')
					);
				}
			}
		}
	
		// Profile fields
		$fields = $user->getProfileFields($post_user[0]->id, true, true);
		
		if($mc_integration[0]->value == '1') $fields[] = array('name' => 'IGN', 'value' => Output::getClean($post_user[0]->username));
		
		// Get post reactions
		$post_reactions = array();
		$total_karma = 0;
		
		$post_reactions_query = $queries->getWhere('forums_reactions', array('post_id', '=', $results->data[$n]->id));
		
		if(count($post_reactions_query)){
			foreach($post_reactions_query as $item){
				if(!isset($post_reactions[$item->reaction_id])){
					$post_reactions[$item->reaction_id]['count'] = 1;
					
					$reaction = $queries->getWhere('reactions', array('id', '=', $item->reaction_id));
					$post_reactions[$item->reaction_id]['html'] = $reaction[0]->html;
					$post_reactions[$item->reaction_id]['name'] = $reaction[0]->name;
					
					if($reaction[0]->type == 2) $total_karma++;
					else if($reaction[0]->type == 0) $total_karma--;
				} else {
					$post_reactions[$item->reaction_id]['count']++;
				}
				
				$post_reactions[$item->reaction_id]['users'][] = array(
					'username' => Output::getClean($user->idToName($item->user_given)),
					'nickname' => Output::getClean($user->idToNickname($item->user_given)),
					'style' => $user->getGroupClass($item->user_given),
					'avatar' => $user->getAvatar($item->user_given, '../', 500),
					'profile' => URL::build('/profile/' . Output::getClean($user->idToName($item->user_given)))
				);
			}
		}
		
		// Purify post content
		$content = htmlspecialchars_decode($results->data[$n]->post_content);
		$content = $emojione->unicodeToImage($content);
		$content = Output::getPurified($content);
		
		$replies[] = array(
			'url' => $url,
			'heading' => $heading,
			'id' => $results->data[$n]->id,
			'user_id' => $post_user[0]->id,
			'avatar' => $avatar,
			'username' => htmlspecialchars($post_user[0]->nickname),
			'mcname' => htmlspecialchars($post_user[0]->username),
			'user_title' => Output::getClean($post_user[0]->user_title),
			'profile' => URL::build('/profile/' . htmlspecialchars($post_user[0]->username)),
			'user_style' => $user->getGroupClass($post_user[0]->id),
			'user_groups' => $user_groups,
			'user_posts_count' => count($queries->getWhere('posts', array('post_creator', '=', $results->data[$n]->post_creator))),
			'user_reputation' => $post_user[0]->reputation,
			'post_date_rough' => $timeago->inWords($results->data[$n]->post_date, $language->getTimeLanguage()),
			'post_date' => date('d M Y, H:i', strtotime($results->data[$n]->post_date)),
			'buttons' => $buttons,
			'content' => $content,
			'signature' => Output::getPurified(htmlspecialchars_decode($signature)),
			'fields' => (empty($fields) ? array() : $fields),
			'edited' => (is_null($results->data[$n]->last_edited) ? null : str_replace('{x}', $timeago->inWords(date('Y-m-d H:i:s', $results->data[$n]->last_edited), $language->getTimeLanguage()), $forum_language->get('forum', 'last_edited'))),
			'edited_full' => (is_null($results->data[$n]->last_edited) ? null : date('d M Y, H:i', $results->data[$n]->last_edited)),
			'post_reactions' => $post_reactions,
			'karma' => $total_karma
		);
	}
	
	$smarty->assign('REPLIES', $replies);
	
	if($user->isLoggedIn()){
		// Reactions
		$reactions = $queries->getWhere('reactions', array('enabled', '=', 1));
		if(!count($reactions)) $reactions = array();
		
		$smarty->assign('REACTIONS', $reactions);
		$smarty->assign('REACTIONS_URL', URL::build('/forum/reactions'));
	}
	
	$smarty->assign('REACTIONS_TEXT', $language->get('user', 'reactions'));
	
	// Quick reply
	if($user->isLoggedIn() && $can_reply){
		if($forum->canModerateForum($group_id, $forum_parent[0]->id, $secondary_groups) || $topic->locked != 1){
			if($topic->locked == 1){
				$smarty->assign('TOPIC_LOCKED_NOTICE', $forum_language->get('forum', 'topic_locked_notice'));
			}
			
			$smarty->assign(array(
				'CONTENT' => Output::getClean(Input::get('content')),
				'SUBMIT' => $language->get('general', 'submit')
			));
		}
	}
	
	// Assign Smarty language variables
	$smarty->assign(array(
		'POSTS' => $forum_language->get('forum', 'posts'),
		'BY' => ucfirst($forum_language->get('forum', 'by')),
		'CANCEL' => $language->get('general', 'cancel'),
		'USER_ID' => (($user->isLoggedIn()) ? $user->data()->id  : 0)
	));
	
	// Get post formatting type (HTML or Markdown)
	$cache->setCache('post_formatting');
	$formatting = $cache->retrieve('formatting');
	
	if($formatting == 'markdown'){
		// Markdown
		$smarty->assign('MARKDOWN', true);
		$smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
	}
	
	// Display page template
	$smarty->display('custom/templates/' . TEMPLATE . '/forum/view_topic.tpl'); 
	
	// Scripts 
	require('core/templates/scripts.php'); 
	?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/jquery-ui.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/jquery.cookie.js"></script>
	
	<?php
	if($formatting == 'markdown'){
	?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/js/emojionearea.min.js"></script>
	
	<script type="text/javascript">
	  $(document).ready(function() {
	    var el = $("#markdown").emojioneArea({
			pickerPosition: "bottom"
		});
	  });
	</script>
	<?php
	} else {
	?>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
	<?php
	}
	?>
	
	<script type="text/javascript">
		$(document).ready(function() {
			var hash = window.location.hash.substring(1);
			$("#" + hash).effect("highlight", {}, 2000);
			(function() {
			    if (document.location.hash) {
			        setTimeout(function() {
			            window.scrollTo(window.scrollX, window.scrollY - 110);
			        }, 10);
			    }
			})();
			
			<?php if($user->isLoggedIn()){ ?>
			if(typeof $.cookie('<?php echo $tid; ?>-quoted') === 'undefined'){
				$("#quoteButton").hide();
			}
			<?php } ?>
		});
		
		<?php
		if($user->isLoggedIn()){ 
			if($formatting != 'markdown'){
				echo Input::createEditor('quickreply');
			}
			?>
		
		// Add post to quoted posts array
		function quote(post){
			var index = quotedPosts.indexOf(post);
			
			if(index > -1){
				quotedPosts.splice(index, 1);
				
				toastr.options.onclick = function () {};
				toastr.options.progressBar = true;
				toastr.options.closeButton = true;
				toastr.options.positionClass = 'toast-bottom-left'
				toastr.info('<?php echo $forum_language->get('forum', 'removed_quoted_post'); ?>');
			}
			else {
				quotedPosts.push(post);
				
				toastr.options.onclick = function () {};
				toastr.options.progressBar = true;
				toastr.options.closeButton = true;
				toastr.options.positionClass = 'toast-bottom-left'
				toastr.info('<?php echo $forum_language->get('forum', 'quoted_post'); ?>');
			}
			
			if(quotedPosts.length == 0){
				// Delete cookie
				$.removeCookie('<?php echo $tid; ?>-quoted');
				
				// Hide insert quote button
				$("#quoteButton").hide();
			} else {
				// Create cookie
				$.cookie('<?php echo $tid; ?>-quoted', JSON.stringify(quotedPosts));
				
				// Show insert quote button
				$("#quoteButton").show();
			}
		}
		
		// Insert quoted posts to editor
		function insertQuotes(){
			var postData = {
				"posts": JSON.parse($.cookie('<?php echo $tid; ?>-quoted')),
				"topic": <?php echo $tid; ?>
			};
			
			toastr.options.onclick = function () {};
			toastr.options.progressBar = true;
			toastr.options.closeButton = true;
			toastr.options.positionClass = 'toast-bottom-left'
			toastr.info('<?php echo $forum_language->get('forum', 'quoting_posts'); ?>');
		
			var getQuotes = $.ajax({
				  type: "POST",
				  url: "<?php echo URL::build('/forum/get_quotes'); ?>",
				  data: postData,
				  dataType: "json",
				  success: function(resultData){
					  for(var item in resultData){
						  if(resultData.hasOwnProperty(item)){
							  <?php
							  if($formatting == 'markdown'){
						      ?>
							  var el = $("#markdown").emojioneArea();
							  el[0].emojioneArea.setText($('#markdown').val() + "\n> [" + resultData[item].author_nickname + "](" + resultData[item].link + ")\n");
							  <?php
							  } else {
							  ?>
						      CKEDITOR.instances.quickreply.insertHtml('<blockquote class="blockquote"><a href="' + resultData[item].link + '">' + resultData[item].author_nickname + ':</a><br />' + resultData[item].content + '</blockquote><br />');
						      <?php } ?>
						  }
					  }
					  
					  // Remove cookie containing quoted posts, and hide quote button
					  $.removeCookie('<?php echo $tid; ?>-quoted');
					  $("#quoteButton").hide();
				  },
				  error: function(data){
					  toastr.options.onclick = function () {};
					  toastr.options.progressBar = true;
					  toastr.options.closeButton = true;
					  toastr.options.positionClass = 'toast-bottom-left'
					  toastr.error('<?php echo $forum_language->get('forum', 'error_quoting_posts'); ?>');
				  }
			});
		}
		<?php } ?>
	</script>
  </body>
</html>
