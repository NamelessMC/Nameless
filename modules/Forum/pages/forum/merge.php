<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Merge two topics together
 */

require_once('modules/Forum/classes/Forum.php');
$forum = new Forum();
 
// Set the page name for the active link in navbar
$page = "forum";

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}


if(!isset($_GET["tid"]) || !is_numeric($_GET["tid"])){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
} else {
	$topic_id = $_GET["tid"];
	$forum_id = $queries->getWhere('topics', array('id', '=', $topic_id));
	$forum_id = $forum_id[0]->forum_id;
}

if($forum->canModerateForum($user->data()->group_id, $forum_id, $user->data()->secondary_groups)){
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'merge' => array(
					'required' => true
				)
			));
			$posts_to_move = $queries->getWhere('posts', array('topic_id', '=', $topic_id));
			if($validation->passed()){
				try {
					foreach($posts_to_move as $post_to_move){
						$queries->update('posts', $post_to_move->id, array(
							'topic_id' => Input::get('merge')
						));
					}
					$queries->delete('topics', array('id', '=' , $topic_id));

					// Update latest posts in categories
					$forum->updateForumLatestPosts();
					$forum->updateTopicLatestPosts();

					Redirect::to(URL::build('/forum/topic/' . Input::get('merge')));
					die();
				} catch(Exception $e){
					die($e->getMessage());
				}
			} else {
				echo 'Error processing that action. <a href="' . URL::build('/forum') . '">Forum index</a>';
				die();
			}
		}
	}
} else {
	Redirect::to(URL::build("/forum"));
	die();
}

$token = Token::get();
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> - merging topics">
	<meta name="robots" content="noindex">
	
    <!-- Site Properties -->
	<?php 
	$title = $forum_language->get('forum', 'merge_topics');
	require('core/templates/header.php'); 
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
	// Generate navbar and footer
	require('core/templates/navbar.php');
	require('core/templates/footer.php');
	
	// Get topics
	$topics = $queries->orderWhere('topics', 'forum_id = ' . $forum_id . ' AND deleted = 0 AND id <> ' . $topic_id, 'id', 'ASC');
	
	// Smarty
	$smarty->assign(array(
		'MERGE_TOPICS' => $forum_language->get('forum', 'merge_topics'),
		'MERGE_INSTRUCTIONS' => $forum_language->get('forum', 'merge_instructions'),
		'TOKEN' => Token::get(),
		'SUBMIT' => $language->get('general', 'submit'),
		'CANCEL' => $language->get('general', 'cancel'),
		'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
		'CANCEL_LINK' => URL::build('/forum/topic/' . $topic_id),
		'TOPICS' => $topics
	));
	
	// Load template
	$smarty->display('custom/templates/' . TEMPLATE . '/forum/merge.tpl');
	
	// Scripts
	require('core/templates/scripts.php');
	?>
  </body>
</html>
