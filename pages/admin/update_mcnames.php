<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

require('core/integration/uuid.php');

// Custom usernames?
$displaynames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$displaynames = $displaynames[0]->value;

if(isset($_GET["uid"])){
	$individual = $queries->getWhere("users", array("id", "=", $_GET["uid"]));
	
	if(count($individual)){
		$uuid = $individual[0];
		$uuid = $uuid->uuid;
		
		$profile = ProfileUtils::getProfile($uuid);
		
		$result = $profile->getUsername();
		
		
		if(!empty($result)){
			$queries->update("users", $individual[0]->id, array(
				"mcname" => $result
			));
			
			if($displaynames == "false"){
				$queries->update("users", $individual[0]->id, array(
					"username" => $result
				));
			}
		}
	}

	Session::flash('adm-users', '<div class="alert alert-info">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['task_successful'] . '</div>');
	Redirect::to('/admin/users/?user=' . $individual[0]->id);
	die();
} else {
	// todo: sync all mcnames for cron job
}

return true;
?>