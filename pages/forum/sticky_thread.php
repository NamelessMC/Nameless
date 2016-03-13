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

$forum = new Forum();

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

// Ensure a topic is set via URL parameters
if(isset($_GET["tid"])){
	if(is_numeric($_GET["tid"])){
		$topic_id = $_GET["tid"];
	} else {
		Redirect::to('/forum/error/?error=not_exist');
		die();
	}
} else {
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

if($user->canViewMCP($user->data()->id)){
	// Does the thread exist?
	$topic = $queries->getWhere("topics", array("id", "=", $topic_id));
	if(count($topic)){
		// Is it sticky already?
		if($topic[0]->sticky == 0){
			$sticky = 1;
			$status = $forum_language['now_sticky'];
		} else {
			$sticky = 0;
			$status = $forum_language['no_longer_sticky'];
		}

		$queries->update("topics", $topic_id, array(
			"sticky" => $sticky
		));

		Session::flash('success_post', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button><center>' . $forum_language['thread_is_'] . $status . '</center></div>');
	}
} 

Redirect::to("/forum/view_topic/?tid=" . $topic_id);
die();