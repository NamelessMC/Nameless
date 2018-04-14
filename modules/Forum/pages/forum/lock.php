<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Lock/unlock a topic
 */

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
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
	
	if($forum->canModerateForum($user->data()->group_id, $forum_id, $user->data()->secondary_groups)){
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
			Log::getInstance()->log(Log::Action('forums/topic/lock'),($locked_status ==1)?$language->get('log', 'info_forums_lock'):$language->get('log', 'info_forums_unlock'));
			
			Redirect::to(URL::build('/forum/topic/' . $topic_id));
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