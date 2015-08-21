<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if($user->isLoggedIn()){
	if(!isset($_GET["tid"]) || !is_numeric($_GET["tid"])){
		Redirect::to('/forum/error/?error=not_exist');
		die();
	} else {
		$topic_id = $_GET["tid"];
	}

	if($user->data()->group_id == 2 || $user->data()->group_id == 3){
		$locked_status = $queries->getWhere('topics', array('id', '=', $topic_id));
		$locked_status = $locked_status[0]->locked;
		if($locked_status == 1){
			$locked_status = 0;
		} else {
			$locked_status = 1;
		}
		try {
			$queries->update('topics', $topic_id, array(
				'locked' => $locked_status
			));
			Redirect::to("/forum/view_topic/?tid=" . $topic_id);
			die();
		} catch(Exception $e) {
			die($e->getMessage());
		}
	} else {
		Redirect::to("/forum");
		die();
	}
} else {
	Redirect::to("/forum");
	die();
}
?>