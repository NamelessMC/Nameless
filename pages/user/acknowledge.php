<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if($user->isLoggedIn()){
	if(!isset($_GET["iid"]) || !is_numeric($_GET["iid"])){
		Redirect::to('/');
		die();
	}
	$infraction = $queries->getWhere("infractions", array("id", "=", $_GET["iid"]));
	if(!count($infraction)){
		Redirect::to('/');
		die();
	} else {
		$infraction = $infraction[0];
		if($infraction->acknowledged == 1 || $infraction->punished !== $user->data()->id){
			Redirect::to('/');
			die();
		} else {
			$queries->update('infractions', $_GET["iid"], array(
				"acknowledged" => 1
			));
			Redirect::to('/');
			die();
		}
	}
} else {
	Redirect::to('/');
	die();
}

?>