<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

require('core/integration/uuid.php');

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$custom_usernames = $custom_usernames[0]->value;

if(isset($_GET["uid"])){
	$individual = $queries->getWhere("users", array("id", "=", $_GET["uid"]));
	
	if(count($individual)){
		if($custom_usernames == 'true'){
			$profile = ProfileUtils::getProfile($individual[0]->mcname);
		} else {
			$profile = ProfileUtils::getProfile($individual[0]->username);
		}
		
		if(!empty($profile)){
			$result = $profile->getProfileAsArray();
			$queries->update("users", $individual[0]->id, array(
				"uuid" => $result['uuid']
			));
		} else {
			Session::flash('adm-users', '<div class="alert alert-danger">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['unable_to_update_uuid'] . '</div>');
			Redirect::to('/admin/users/?user=' . $individual[0]->id);
			die();
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