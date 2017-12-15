<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Update Minecraft username script
 */

require(ROOT_PATH . '/core/integration/uuid.php');

// Custom usernames?
$displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$displaynames = $displaynames[0]->value;

if(isset($_GET['uid'])){
	$individual = $queries->getWhere('users', array('id', '=', $_GET['uid']));
	
	if(count($individual)){
		$uuid = $individual[0];
		$uuid = $uuid->uuid;
		
		$profile = ProfileUtils::getProfile($uuid);
		
		$result = $profile->getUsername();
		
		
		if(!empty($result)){
			$queries->update('users', $individual[0]->id, array(
				'username' => $result
			));
			
			if($displaynames == 'false'){
				$queries->update('users', $individual[0]->id, array(
					'username' => $result,
					'nickname' => $result
				));
			}
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