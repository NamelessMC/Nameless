<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  New topic page
 */

// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'true'){
	// Maintenance mode is enabled, only admins can view
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		require('modules/Forum/pages/forum/maintenance.php');
		die();
	}
}
 
// Always define page name
define('PAGE', 'forum');

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/forum'));
	die();
}

require('modules/Forum/classes/Forum.php');
$forum = new Forum();
$mentionsParser = new MentionsParser();

require('core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML

if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

$fid = (int) $_GET['fid'];

// Get user group ID
if($user->isLoggedIn()) $user_group = $user->data()->group_id; else $user_group = null;

// Does the forum exist, and can the user view it?
$list = $forum->forumExist($fid, $user_group);
if(!$list){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

// Can the user post a topic in this forum?
$can_reply = $forum->canPostTopic($fid, $user_group);
if(!$can_reply){
	Redirect::to(URL::build('/forum/view_forum/', 'fid=' . $fid));
	die();
}

// Deal with any inputted data
if(Input::exists()) {
	if(Token::check(Input::get('token'))){
		// Check post limits
		$last_post = $queries->orderWhere('posts', 'post_creator = ' . $user->data()->id, 'post_date', 'DESC LIMIT 1');
		if(count($last_post)){
			if(strtotime($last_post[0]->post_date) > strtotime("-30 seconds")){
				$spam_check = true;
			}
		}
		
		if(!isset($spam_check)){
			// Spam check passed
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'title' => array(
					'required' => true,
					'min' => 2,
					'max' => 64
				),
				'content' => array(
					'required' => true,
					'min' => 2,
					'max' => 20480
				)
			));
			if($validation->passed()){
				try {
					if(isset($_POST['topic_label']) && !empty($_POST['topic_label']))
						$label = Input::get('topic_label');
					else $label = null;
					
					$queries->create("topics", array(
						'forum_id' => $fid,
						'topic_title' => Input::get('title'),
						'topic_creator' => $user->data()->id,
						'topic_last_user' => $user->data()->id,
						'topic_date' => date('U'),
						'topic_reply_date' => date('U'),
						'label' => $label
					));
					$topic_id = $queries->getLastId();
					
					// Parse markdown
					$cache->setCache('post_formatting');
					$formatting = $cache->retrieve('formatting');
					
					if($formatting == 'markdown'){
						$content = Michelf\Markdown::defaultTransform(Input::get('content'));
						$content = Output::getClean($content);
					} else $content = Output::getClean(Input::get('content'));
					
					$queries->create("posts", array(
						'forum_id' => $fid,
						'topic_id' => $topic_id,
						'post_creator' => $user->data()->id,
						'post_content' => $content,
						'post_date' => date('Y-m-d H:i:s')
					));
					
					// Get last post ID
					$last_post_id = $queries->getLastId();
					$content = $mentionsParser->parse($user->data()->id, $content, $topic_id, $last_post_id, $forum_language->get('forum', 'user_tag'), $forum_language->get('forum', 'user_tag_info'));
					
					$queries->update("posts", $last_post_id, array(
						'post_content' => $content
					));
					
					$queries->update("forums", $fid, array(
						'last_post_date' => date('U'),
						'last_user_posted' => $user->data()->id,
						'last_topic_posted' => $topic_id
					));
					
					Session::flash('success_post', $forum_language->get('forum', 'post_successful'));
					
					Redirect::to(URL::build('/forum/view_topic/', 'tid=' . $topic_id));
					die();
					
				} catch(Exception $e){
					die($e->getMessage());
				}
			} else {
				$error = array();
				foreach($validation->errors() as $item){
					if(strpos($item, 'is required') !== false){
						switch($item){
							case (strpos($item, 'title') !== false):
								$error[] = $forum_language->get('forum', 'title_required');
							break;
							case (strpos($item, 'content') !== false):
								$error[] = $forum_language->get('forum', 'content_required');
							break;
						}
					} else if(strpos($item, 'minimum') !== false){
						switch($item){
							case (strpos($item, 'title') !== false):
								$error[] = $forum_language->get('forum', 'title_min_2');
							break;
							case (strpos($item, 'content') !== false):
								$error[] = $forum_language->get('forum', 'content_min_2');
							break;
						}
					} else if(strpos($item, 'maximum') !== false){
						switch($item){
							case (strpos($item, 'title') !== false):
								$error[] = $forum_language->get('forum', 'title_max_64');
							break;
							case (strpos($item, 'content') !== false):
								$error[] = $forum_language->get('forum', 'content_max_20480');
							break;
						}
					}
				}
			}
		} else {
			$error = array(str_replace('{x}', (strtotime($last_post[0]->post_date) - strtotime("-30 seconds")), $forum_language->get('forum', 'spam_wait')));
		}
	} else {
		$error = array($language->get('general', 'invalid_token'));
	}
}

// Generate a token
$token = Token::generate();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="robots" content="noindex">

    <!-- Site Properties -->
	<?php 
	$title = $forum_language->get('forum', 'new_topic');
	require('core/templates/header.php'); 
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/css/emojionearea.min.css"/>
  </head>

  <body>
	<?php
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	
	// Generate content for template
	if(isset($error)){
		$smarty->assign('ERROR', $error);
	}
	
	$creating_topic_in = str_replace('{x}', Output::getPurified(htmlspecialchars_decode($forum->getForumTitle($fid))), $forum_language->get('forum', 'creating_topic_in_x'));
	$smarty->assign('CREATING_TOPIC_IN', $creating_topic_in);
	
	// Get labels available
	$labels = '<h4 style="display:inline;">';
	$labels_query = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
	
	$available_label_ids = array();
	
	foreach($labels_query as $label){
		$forum_ids = explode(',', $label->fids);
		if(in_array($fid, $forum_ids)){
			$available_label_ids[] = $label->id;
		}
	}
	
	foreach($available_label_ids as $label){
		$query = $queries->getWhere('forums_topic_labels', array('id', '=', $label));
		$labels .= '<input type="radio" name="topic_label" value="' . $query[0]->id . '"> <span class="tag tag-' . Output::getClean($query[0]->label) . '">' . Output::getClean($query[0]->name) . '</span>&nbsp;&nbsp;';
	}
	
	$labels .= '</h4>';
	
	$smarty->assign('TOPIC_TITLE', $forum_language->get('forum', 'topic_title'));
	$smarty->assign('LABEL', $forum_language->get('forum', 'label'));
	$smarty->assign('SUBMIT', $language->get('general', 'submit'));
	$smarty->assign('CANCEL', $language->get('general', 'cancel'));
	$smarty->assign('CLOSE', $language->get('general', 'close'));
	$smarty->assign('CONFIRM_CANCEL', $language->get('general', 'confirm_cancel'));
	$smarty->assign('TOKEN', '<input type="hidden" name="token" value="' . $token . '">');
	$smarty->assign('FORUM_LINK', URL::build('/forum'));
	$smarty->assign('CONTENT', Output::getPurified(Input::get('content')));
	
	// Get post formatting type (HTML or Markdown)
	$cache->setCache('post_formatting');
	$formatting = $cache->retrieve('formatting');
	
	if($formatting == 'markdown'){
		// Markdown
		$smarty->assign('MARKDOWN', true);
		$smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
	}
	
	// Display template
	$smarty->display('custom/templates/' . TEMPLATE . '/forum/new_topic.tpl'); 

	require('core/templates/scripts.php');
	
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
	<?php } else { ?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
	
	<script type="text/javascript">
		CKEDITOR.replace( 'reply', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"styles","groups":["styles"]},
				{"name":"colors","groups":["colors"]},
				{"name":"links","groups":["links"]},
				{"name":"insert","groups":["insert"]}
				//{"name" : "pbckcode"}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
		} );
	</script>
	<?php } ?>
  </body>
</html>
