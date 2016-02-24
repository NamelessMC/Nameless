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
		echo $admin_language['forum_in_maintenance'] . '. <a href="/">' . $navbar_language['home'] . '</a>';
		die();
	}
}
 
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

$forum = new Forum();

if($user->canViewMCP($user->data()->id)){
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			// Is it the OP?
			if(Input::get('number') == 0){
				try {
					$queries->delete('topics', array('id', '=' , (Input::get('tid'))));
				} catch(Exception $e) {
					die($e->getMessage());
				}
				$redirect = "/forum"; // Create a redirect string
			} else {
				$redirect = "/forum/view_topic/?tid=" . Input::get('tid');
			}
			try {
				$queries->delete('posts', array('id', '=' , (Input::get('pid'))));

				// Update latest posts in categories
				$forum->updateForumLatestPosts();
				$forum->updateTopicLatestPosts();

				Redirect::to($redirect);
				die();
			} catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			Redirect::to('/forum/view_topic/?tid=' . Input::get('tid'));
			die();
		}
	} else {
		echo 'No post selected';
	}
} else {
	Redirect::to('/forum');
	die();
}

?>