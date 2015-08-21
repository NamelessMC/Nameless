<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

$forum = new Forum();

if($user->data()->group_id == 2 || $user->data()->group_id == 3){
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