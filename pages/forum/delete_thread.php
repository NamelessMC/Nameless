<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

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
		//$queries->delete('posts', array('topic_id', '=' , $topic_id));
		// Keep posts for archival reasons
		
		
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