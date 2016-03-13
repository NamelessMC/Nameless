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
 
if($user->isLoggedIn()){
	if(!isset($_GET["tid"]) || !is_numeric($_GET["tid"])){
		Redirect::to('/forum/error/?error=not_exist');
		die();
	} else {
		$topic_id = $_GET["tid"];
	}

	if($user->canViewMCP($user->data()->id)){
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