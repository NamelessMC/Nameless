<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Stick/unstick a topic
 */

require_once('modules/Forum/classes/Forum.php');
$forum = new Forum();

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/forum'));
	die();
}

// Ensure a topic is set via URL parameters
if(isset($_GET["tid"])){
	if(is_numeric($_GET["tid"])){
		$topic_id = $_GET["tid"];
	} else {
		Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
		die();
	}
} else {
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

// Check topic exists and get forum ID
$topic = $queries->getWhere('topics', array('id', '=', $topic_id));

if(!count($topic)){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

$forum_id = $topic[0]->forum_id;

if($forum->canModerateForum($user->data()->group_id, $forum_id, $user->data()->secondary_groups)){
	// Get current status
	if($topic[0]->sticky == 0){
		$sticky = 1;
		$status = $forum_language->get('forum', 'topic_stuck');
	} else {
		$sticky = 0;
		$status = $forum_language->get('forum', 'topic_unstuck');
	}

	$queries->update("topics", $topic_id, array(
		"sticky" => $sticky
	));

	Session::flash('success_post', $status);
} 

Redirect::to(URL::build('/forum/topic/' . $topic_id));
die();