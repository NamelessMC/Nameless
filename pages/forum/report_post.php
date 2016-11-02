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

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

$forum = new Forum();

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

// Ensure a post and topic is set via URL parameters
if(isset($_GET["pid"]) && isset($_GET["tid"])){
	if(is_numeric($_GET["pid"]) && is_numeric($_GET["tid"])){
		$post_id = $_GET["pid"];
		$topic_id = $_GET["tid"];
	} else {
		Redirect::to('/forum/error/?error=not_exist');
		die();
	}
} else {
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

// Deal with inputted data
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'reason' => array(
				'required' => true,
				'min' => 2,
				'max' => 255
			)
		));
		if($validation->passed()){
			try {
				$queries->create("reports", array(
					'type' => 0,
					'reporter_id' => $user->data()->id,
					'reported_id' => Input::get('reported_user'),
					'status' => 0,
					'date_reported' => date('Y-m-d H:i:s'),
					'date_updated' => date('Y-m-d H:i:s'),
					'report_reason' => htmlspecialchars(Input::get('reason')),
					'updated_by' => $user->data()->id,
					'reported_post' => Input::get('post_id'),
					'reported_post_topic' => Input::get('topic_id')
				));
				
				$report_id = $queries->getLastId();
				
				// Alert for moderators
				$mod_groups = $queries->getWhere('groups', array('mod_cp', '=', 1));
				foreach($mod_groups as $mod_group){
					$mod_users = $queries->getWhere('users', array('group_id', '=', $mod_group->id));
					foreach($mod_users as $individual){
						$queries->create('alerts', array(
							'user_id' => $individual->id,
							'type' => $user_language['report'],
							'url' => '/mod/reports/?rid=' . $report_id,
							'content' => str_replace(array('{x}', '{y}'), array(htmlspecialchars($user->data()->username), htmlspecialchars($user->idToName(Input::get('reported_user')))), $mod_language['new_report_submitted_alert']),
							'created' => date('U')
						));
					}
				}
				
				Session::flash('success_post', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $forum_language['report_submitted'] . '</div>');
				Redirect::to('/forum/view_topic/?tid=' . Input::get('topic_id'));
				die();

			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			foreach($validation->errors() as $error) {
				$error_string .= ucfirst($error) . '<br />';
			}
			Session::flash('failure_post', '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $error_string . '</div>');
			Redirect::to('/forum/report_post/?pid=' . Input::get('post_id') . '&tid=' . Input::get('topic_id'));
			die();
		}
	} else {
		// Invalid token - TODO: improve this
	}
}

$token = Token::generate();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> Forum - <?php echo htmlspecialchars($forum_query->forum_title); ?>">
    <meta name="author" content="<?php echo $sitename; ?>">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'] . ' - ' . $forum_language['report_post'];
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	textarea {
		resize: none;
	}
	</style>
	
  </head>

  <body>
	<?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	
	// Generate content for template
	$smarty->assign('REPORT_POST', $forum_language['report_post']);
	
	$reported_post = $forum->getIndividualPost($post_id); // Get an array containing information about the post
	
	// Avatar
	$avatar = '<img class="img-rounded" style="width:100px; height:100px;" src="' . $user->getAvatar($reported_post[0][0], "../", 100) . '" />';
	
	// Initialise HTML Purifier
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	$config->set('URI.DisableExternalResources', false);
	$config->set('URI.DisableResources', false);
	$config->set('HTML.Allowed', 'u,p,b,a,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
	$config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
	$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
	$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	$config->set('HTML.SafeIframe', true);
	$config->set('Core.EscapeInvalidTags', true);
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
	$purifier = new HTMLPurifier($config);

	$clean = $purifier->purify(htmlspecialchars_decode($reported_post[1][0]));
	
	$content = 	'<div class="row">' . PHP_EOL .
				'  <div class="col-md-3">' . PHP_EOL . 
				'    <center>' . $avatar . '<br /><br />' . PHP_EOL . 
				'    <strong><a href="/profile/' . htmlspecialchars($user->IdToMCName($reported_post[0][0])) . '">' . htmlspecialchars($user->IdToName($reported_post[0][0])) . '</a></strong>' . PHP_EOL . 
				'    </center>' . PHP_EOL . 
				'  </div>' . PHP_EOL . 
				'  <div class="col-md-9">' . PHP_EOL . 
				'    <a href="/profile/' . htmlspecialchars($user->IdToMCName($reported_post[0][0])) . '">' . htmlspecialchars($user->IdToName($reported_post[0][0])) . '</a> &raquo; ' . date("d M Y, H:i", strtotime($reported_post[2][0])) . '<hr>' .PHP_EOL . 
				'    ' . $clean . PHP_EOL .
				'  </div>' . PHP_EOL . 
				'</div>';
	
	$smarty->assign('CONTENT', $content);
	
	$form_content = '<form action="" method="post">' . PHP_EOL . 
					'  <textarea name="reason" class="form-control" rows="3"></textarea>' . PHP_EOL . 
					'  <br />' . PHP_EOL . 
					'  <input type="hidden" name="token" value="' .  $token . '">' . PHP_EOL . 
				    '  <input type="hidden" name="post_id" value="' . $_GET["pid"] . '">' . PHP_EOL . 
					'  <input type="hidden" name="reported_user" value="' . $reported_post[0][0] . '">' . PHP_EOL . 
					'  <input type="hidden" name="topic_id" value="' . $_GET["tid"] . '">' . PHP_EOL . 
					'  <button type="submit" class="btn btn-primary">' . $general_language['submit'] . '</button> <a href="/forum/view_topic/?tid=' . $_GET['tid'] . '" class="btn btn-danger" onclick="return confirm(\'' . $forum_language['confirm_cancellation'] . '\');">' . $general_language['cancel'] . '</a>' . PHP_EOL . 
					'</form>';
	
	$smarty->assign('FORM_CONTENT', $form_content);
	
	if(Session::exists('failure_post')){
		$smarty->assign('SESSION', Session::flash('failure_post'));
	} else {
		$smarty->assign('SESSION', '');
	}
	
	$smarty->assign('VIEW_POST_CONTENT', $forum_language['view_post_content']);
	$smarty->assign('REPORT_REASON', $forum_language['report_reason']);
	
	$smarty->display('styles/templates/' . $template . '/forum_report_post.tpl');

	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>

  </body>
</html>
