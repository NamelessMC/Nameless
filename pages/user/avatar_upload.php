<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// First, check to see if avatar uploading is enabled..

$avatar_enabled = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
$avatar_enabled = $avatar_enabled[0]->value;


if($avatar_enabled === "1"){
	$image = new SimpleImage();

	if(!$user->isLoggedIn()){
		Redirect::to('/');
		die();
	}

	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			
			if(!isset($_FILES['uploaded_avatar']['tmp_name'])){
				// TODO
				echo $user_language['no_file_chosen'] . ' - <a href="/user/settings">' . $general_language['back'] . '</a>';
				die();
			}
			
			if(file_exists("avatars/" . $user->data()->id . ".jpg")){
				unlink("avatars/" . $user->data()->id . ".jpg");
			} else if(file_exists("avatars/" . $user->data()->id . ".png")){
				unlink("avatars/" . $user->data()->id . ".png");
			} else if(file_exists("avatars/" . $user->data()->id . ".gif")){
				unlink("avatars/" . $user->data()->id . ".gif");
			}
			
			$image->load($_FILES['uploaded_avatar']['tmp_name']);
			$image->resize('300', '300');
			$image->save("avatars/" . $user->data()->id);

			$queries->update("users", $user->data()->id, array(
				"has_avatar" => 1
			));
			
			Redirect::to('/user/settings');
			die();
			
		} else {
			// TODO
			echo $admin_language['invalid_token'] . ' - <a href="/user/settings">' . $general_language['back'] . '</a>';
			die();
		}
	} else {
		// TODO
		Redirect::to('/user/settings');
		die();
	}
} else {
	Redirect::to('/user/settings');
	die();	
}

?>
