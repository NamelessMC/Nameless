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
$page = 'forum';

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

$forum = new Forum();
$mentionsParser = new MentionsParser();

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

if(!isset($_GET['tid']) || !is_numeric($_GET['tid']) || !isset($_GET['fid']) || !is_numeric($_GET['fid'])){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

$tid = (int) $_GET['tid'];
$fid = (int) $_GET['fid'];

// Does the topic exist, and can the user view it?
$list = $forum->topicExist($tid, $user->data()->group_id);
if(!$list){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

// Get the topic information
$topic = $queries->getWhere("topics", array("id", "=", $tid));
$topic = $topic[0];

// Can the user post a reply in this topic?
$can_reply = $forum->canPostReply($topic->forum_id, $user->data()->group_id);
if(!$can_reply){
	Redirect::to('/forum/view_topic/?tid=' . $tid);
	die();
}

// Deal with inputted data
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
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
				$queries->create("posts", array(
					'forum_id' => $fid,
					'topic_id' => $tid,
					'post_creator' => $user->data()->id,
					'post_content' => htmlspecialchars(Input::get('content')),
					'post_date' => date('Y-m-d H:i:s')
				));
				
				// Get last post ID
				$last_post_id = $queries->getLastId();
				$content = $mentionsParser->parse(Input::get('content'), $tid, $last_post_id, $user_language);
				
				$queries->update("posts", $last_post_id, array(
					'post_content' => $content
				));
				
				$queries->update("forums", $fid, array(
					'last_topic_posted' => $tid,
					'last_user_posted' => $user->data()->id,
					'last_post_date' => date('Y-m-d H:i:s')
				));
				$queries->update("topics", $tid, array(
					'topic_last_user' => $user->data()->id,
					'topic_reply_date' => date('U')
				));
				Session::flash('success_post', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $forum_language['post_submitted'] . '</div>');
				Redirect::to('/forum/view_topic/?tid=' . $tid);
				die();
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			$error_string = "";
			foreach($validation->errors() as $error) {
				$error_string .= ucfirst($error) . '<br />';
			}
			Session::flash('failure_post', '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $error_string . '</div>');
		}
	} else {
		Session::flash('failure_post', '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['invalid_token'] . '</div>');
	}
}

// Is there a quote?
if(isset($_GET["qid"])){
	if(is_numeric($_GET["qid"])){
		$quoted_post = $queries->getWhere("posts", array("id", "=", $_GET["qid"]));
		$quoted_post = $quoted_post[0];
	} else {
		Redirect::to('/forum/view_topic/?tid=' . $tid);
		die();
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
    <meta name="description" content="<?php echo $sitename; ?> post creation">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $forum_language['create_post'];
	
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

	if(Session::exists('failure_post')){
		echo '<br /><div class="container"><center>' . Session::flash('failure_post') . '</center></div>';
	}

	if($topic->locked != 1 || ($user->data()->group_id == 2 || $user->data()->group_id == 3)){ // Ensure the topic isn't locked
	  // TODO: Change above IF statement so it's permission based instead of group based
	  
      // Initialise HTML Purifier
	  $config = HTMLPurifier_Config::createDefault();
	  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	  $config->set('URI.DisableExternalResources', false);
	  $config->set('URI.DisableResources', false);
	  $config->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
	  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
	  $config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
	  $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	  $config->set('HTML.SafeIframe', true);
	  $config->set('Core.EscapeInvalidTags', true);
	  $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
	  $purifier = new HTMLPurifier($config);

	  // Generate content for page
	  $creating_post_in = $forum_language['creating_post_in'] . htmlspecialchars($topic->topic_title);

	  if($topic->locked == 1 && ($user->data()->group_id == 2 || $user->data()->group_id == 3)){
	    // TODO: Change above IF statement so it's permission based instead of group based
		$locked_message = '<div class="alert alert-info">' . $forum_language['topic_locked_permission_post'] . '</div>';
	  } else {
		$locked_message = '';
	  }
	  
	  $form_content = 	'<div class="form-group"><textarea name="content" id="reply" rows="3">' . $purifier->purify(Input::get('content')) . '</textarea></div>' . PHP_EOL . 
						'<input type="hidden" name="token" value="' . $token . '">' . PHP_EOL .
						'<button type="submit" class="btn btn-primary">' . $general_language['submit'] . '</button>';
	} else {
	  Redirect::to('/forum/view_topic/?tid=' . $tid);
	  die();
	}

	// Assign Smarty variables
	$smarty->assign('CREATING_POST_IN', $creating_post_in);
	$smarty->assign('LOCKED_MESSAGE', $locked_message);
	$smarty->assign('FORM_CONTENT', $form_content);
	$smarty->assign('TOPIC_ID', $tid);
	$smarty->assign('CANCEL', $general_language['cancel']);
	$smarty->assign('CONFIRM', $forum_language['confirm_cancellation']);
	
	$smarty->display('styles/templates/' . $template . '/forum_create_post.tpl');
		
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
		CKEDITOR.timestamp = '2';
		CKEDITOR.config.disableNativeSpellChecker = false;
		CKEDITOR.config.extraAllowedContent = 'blockquote small';
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	    <?php 
	    // Quote
	    if(isset($quoted_post) && !Session::exists('failure_post')){
	  	  $clean = $purifier->purify(htmlspecialchars_decode($quoted_post->post_content));
	    ?>
		CKEDITOR.on('instanceReady', function(ev) {
		    CKEDITOR.instances.reply.insertHtml('<blockquote><small><a href="/forum/view_topic/?tid=<?php echo $tid; ?>&amp;pid=<?php echo $_GET["qid"]; ?>"><?php echo htmlspecialchars($user->IdToName($quoted_post->post_creator)); ?> said:<\/a> <?php echo str_replace(array("\r", "\n", "'"), array("", "", "\'"), $clean); ?><\/small></blockquote>');
		});
		<?php
		}
		?>
	</script>
  </body>
</html>
