<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Lock/unlock a topic
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

require('modules/Forum/classes/Forum.php');
$forum = new Forum();
 
if($user->isLoggedIn()){
	if(!isset($_GET["tid"]) || !is_numeric($_GET["tid"])){
		Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
		die();
	} else {
		$topic_id = $_GET["tid"];
	}

	// Check topic exists and get forum ID
	$topic = $queries->getWhere('topics', array('id', '=', $topic_id));
	
	if(!count($topic)){
		Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
		die();
	}
	
	$forum_id = $topic[0]->forum_id;
	
	if($forum->canModerateForum($user->data()->group_id, $forum_id)){
		$locked_status = $topic[0]->locked;
		
		if($locked_status == 1){
			$locked_status = 0;
		} else {
			$locked_status = 1;
		}
		
		try {
			$queries->update('topics', $topic_id, array(
				'locked' => $locked_status
			));
			
			Redirect::to(URL::build('/forum/view_topic/', 'tid=' . $topic_id));
			die();
			
		} catch(Exception $e) {
			die($e->getMessage());
		}
		
	} else {
		Redirect::to(URL::build("/forum"));
		die();
	}
} else {
	Redirect::to(URL::build("/forum"));
	die();
}