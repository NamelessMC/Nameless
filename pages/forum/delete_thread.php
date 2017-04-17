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
 
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

$forum = new Forum();

if(!isset($_GET["tid"]) || !is_numeric($_GET["tid"])){
	Redirect::to('/forum');
	die();
} else {
	$topic_id = $_GET["tid"];
}

if($user->canViewMCP($user->data()->id)){
	try {
		// Mark posts as deleted
		$posts = $queries->getWhere('posts', array('topic_id', '=', $topic_id));
		foreach($posts as $post){
			$queries->update('posts', $post->id, array(
				'deleted' => 1
			));
		}
		
		$queries->delete('topics', array('id', '=', $topic_id));

		// Update latest posts in categories
		$forum->updateForumLatestPosts();

		Redirect::to('/forum');
		die();
		
	} catch(Exception $e) {
		die($e->getMessage());
	}
} else {
	Redirect::to('/forum');
	die();
}

?>