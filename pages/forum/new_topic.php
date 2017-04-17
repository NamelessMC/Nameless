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

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

$forum = new Forum();
$mentionsParser = new MentionsParser();

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

// Can the user post a topic in this forum?
$can_reply = $forum->canPostTopic($fid, $user->data()->group_id);
if(!$can_reply){
	Redirect::to('/forum/view_forum/?fid=' . $fid);
	die();
}

// Deal with any inputted data
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
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
				$queries->create("topics", array(
					'forum_id' => $fid,
					'topic_title' => Input::get('title'),
					'topic_creator' => $user->data()->id,
					'topic_last_user' => $user->data()->id,
					'topic_date' => date('U'),
					'topic_reply_date' => date('U'),
					'label' => Input::get('topic_label')
				));
				$topic_id = $queries->getLastId();
				$queries->create("posts", array(
					'forum_id' => $fid,
					'topic_id' => $topic_id,
					'post_creator' => $user->data()->id,
					'post_content' => htmlspecialchars(Input::get('content')),
					'post_date' => date('Y-m-d H:i:s')
				));
				
				// Get last post ID
				$last_post_id = $queries->getLastId();
				$content = $mentionsParser->parse(Input::get('content'), $topic_id, $last_post_id, $user_language);
				
				$queries->update("posts", $last_post_id, array(
					'post_content' => $content
				));
				
				$queries->update("forums", $fid, array(
					'last_post_date' => date('Y-m-d H:i:s'),
					'last_user_posted' => $user->data()->id,
					'last_topic_posted' => $topic_id
				));
				Session::flash('success_post', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $forum_language['topic_created'] . '</div>');
				
				echo '<script data-cfasync="false">window.location.replace("/forum/view_topic/?tid=' . $topic_id . '");</script>';
				die();
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			foreach($validation->errors() as $error) {
				$error_string .= ucfirst($error) . '<br />';
			}
			Session::flash('failure_post', '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $error_string . '</div>');
		}
	} else {
		// Invalid token
		Session::flash('failure_post', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
	}
}

// Generate a token
$token = Token::generate();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> Forum - new topic in <?php echo htmlspecialchars($forum_query->forum_title); ?>">
    <meta name="author" content="<?php echo $sitename; ?>">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'] . ' - ' . $forum_language['new_topic'];
	
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
	
	// Generate content for template
	if(Session::exists('failure_post')){
      $smarty->assign('SESSION', Session::flash('failure_post'));
	} else {
	  $smarty->assign('SESSION', '');
	}
	
	$creating_topic_in = $forum_language['creating_topic_in_'] . htmlspecialchars($forum->getForumTitle($fid));
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
		$labels .= '<input type="radio" name="topic_label" value="' . $query[0]->id . '"> <span class="label label-' . htmlspecialchars($query[0]->label) . '">' . htmlspecialchars($query[0]->name) . '</span>&nbsp;&nbsp;';
	}
	
	$labels .= '</h4>';
	
	$form_content = '<form action="" method="post">' . PHP_EOL .
					'  <div class="form-group">' . PHP_EOL .
					'    <input type="text" class="form-control input-lg" name="title" placeholder="' . $forum_language['thread_title'] . '">' . PHP_EOL .
					'  </div>' . PHP_EOL .
					'  <div class="well well-sm"><strong>' . $forum_language['label'] . '</strong><br /><input type="radio" name="topic_label" value="0" checked>' . $general_language['none'] . ' ' . $labels . '</div>' . PHP_EOL .
					'  <div class="form-group">' . PHP_EOL .
					'    <textarea name="content" id="reply" rows="3">' . htmlspecialchars(Input::get('content')) . '</textarea>' . PHP_EOL .
					'  </div>' . PHP_EOL .
					'  <input type="hidden" name="token" value="' . $token . '">' . PHP_EOL .
					'  <input type="submit" class="btn btn-primary" value="' . $general_language['submit'] . '">' . PHP_EOL .
					'  <a class="btn btn-danger" href="/forum" onclick="return confirm(\'' . $forum_language['confirm_cancellation'] . '\')">' . $general_language['cancel'] . '</a>' . PHP_EOL . 
					'</form>';
	$smarty->assign('FORM_CONTENT', $form_content);
	
	// Display template
	$smarty->display('styles/templates/' . $template . '/forum_create_topic.tpl');

	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	
	<script src="/core/assets/js/ckeditor.js"></script>
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
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
		} );
		CKEDITOR.config.disableNativeSpellChecker = false;
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	</script>
  </body>
</html>
