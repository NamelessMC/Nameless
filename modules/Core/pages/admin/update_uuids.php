<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Update Minecraft UUID script
 */

require(ROOT_PATH . '/core/integration/uuid.php');

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$custom_usernames = $custom_usernames[0]->value;

if(isset($_GET["uid"])){
	$individual = $queries->getWhere('users', array('id', '=', $_GET['uid']));
	
	if(count($individual)){
		$profile = ProfileUtils::getProfile($individual[0]->username);
		
		if(!empty($profile)){
			$result = $profile->getProfileAsArray();
			$queries->update('users', $individual[0]->id, array(
				'uuid' => $result['uuid']
			));
		} else {
			Session::flash('adm-users', '<div class="alert alert-danger">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $language->get('admin', 'unable_to_update_uuid') . '</div>');
			Redirect::to(URL::build('/admin/users/', 'user=' . $individual[0]->id));
			die();
		}
	}

	Session::flash('adm-users', '<div class="alert alert-info">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $language->get('admin', 'task_successful') . '</div>');
	Redirect::to(URL::build('/admin/users/', 'user=' . $individual[0]->id));
	die();
} else {
	// todo: sync all mcnames for cron job
}

return true;
?>