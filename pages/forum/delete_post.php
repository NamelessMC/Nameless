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
 
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

$forum = new Forum();

if($user->canViewMCP($user->data()->id)){
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			if(isset($_POST['tid'])){
				// Is it the OP?
				if(Input::get('number') == 0){
					try {
						$queries->delete('topics', array('id', '=' , (Input::get('tid'))));
						$opening_post = 1;
					} catch(Exception $e) {
						die($e->getMessage());
					}
					$redirect = "/forum"; // Create a redirect string
				} else {
					$redirect = "/forum/view_topic/?tid=" . Input::get('tid');
				}
			} else $redirect = '/forum/search/?p=1&s=' . htmlspecialchars($_POST['search_string']);
			
			try {
				$queries->update('posts', Input::get('pid'), array(
					'deleted' => 1
				));
				
				if(isset($opening_post)){
					$posts = $queries->getWhere('posts', array('topic_id', '=', $_POST['tid']));
					
					if(count($posts)){
						foreach($posts as $post){
							$queries->update('posts', $post->id, array(
								'deleted' => 1
							));
						}
					}
				}

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