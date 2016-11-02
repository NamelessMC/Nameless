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


if(!isset($_GET["tid"]) || !is_numeric($_GET["tid"])){
	Redirect::to('/forum/error/?error=not_exist');
	die();
} else {
	$topic_id = $_GET["tid"];
	$forum_id = $queries->getWhere('topics', array('id', '=', $topic_id));
	$forum_id = $forum_id[0]->forum_id;
}

if($user->canViewMCP($user->data()->id)){ // TODO: Change to permission based if statement
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'move' => array(
					'required' => true
				)
			));
			$thread_to_move = $queries->getWhere('topics', array('id', '=', $topic_id));
			$thread_to_move = $thread_to_move[0];
			
			if(!count($thread_to_move)){
				Redirect::to('/forum');
				die();
			}
			
			$posts_to_move = $queries->getWhere('posts', array('topic_id', '=', $topic_id));
			if($validation->passed()){
				try {
					$queries->update('topics', $thread_to_move->id, array(
						'forum_id' => Input::get('move')
					));
					foreach($posts_to_move as $post_to_move){
						$queries->update('posts', $post_to_move->id, array(
							'forum_id' => Input::get('move')
						));
					}

					// Update latest posts in categories
					$forum->updateForumLatestPosts();
					$forum->updateTopicLatestPosts();

					Redirect::to('/forum/view_topic/?tid=' . $topic_id);
					die();
				} catch(Exception $e){
					die($e->getMessage());
				}
			} else {
				echo 'Error processing that action. <a href="/forum">Forum index</a>';
				die();
			}
		}
	}
} else {
	Redirect::to("/forum");
	die();
}

$token = Token::generate();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> Forum - Move Thread">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'] . ' - ' . $forum_language['move_thread'];
	
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
	?>
	<br />
    <div class="container">
	  <h2><?php echo $forum_language['move_thread']; ?></h2>
	  <?php 
		$forums = $queries->getWhere("forums", array("parent", "<>", 0));
	  ?>
	  <form action="" method="post">
		<div class="form-group">
		  <label for="InputMove"><?php echo $forum_language['move_to']; ?></label>
		  <select class="form-control" id="InputMove" name="move">
		  <?php 
		  foreach($forums as $forum){
			  if($forum->forum_type != 'category') {
				  ?>
				  <option value="<?php echo $forum->id; ?>"><?php echo str_replace("&amp;", "&", htmlspecialchars($forum->forum_title)); ?></option>
				  <?php
			  }
		  } 
		  ?>
		  </select> 
		</div>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-default">
	  </form>
	</div>
	<?php 
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	  
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
  </body>
</html>
