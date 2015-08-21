<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

// Deal with the reputation change
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'pid' => array(
				'required' => true
			),
			'tid' => array(
				'required' => true
			),
			'type' => array(
				'required' => true
			),
			'uid' => array(
				'required' => true
			)
		));
		if($validation->passed()){
			if(Input::get('type') === 'positive'){
				try {
					$queries->create("reputation", array(
						'user_received' => Input::get('uid'),
						'post_id' => Input::get('pid'),
						'topic_id' => Input::get('tid'),
						'user_given' => $user->data()->id,
						'time_given' => date('Y-m-d H:i:s')
					));
					$queries->increment("users", Input::get('uid'), "reputation");
					Redirect::to("/forum/view_topic/?tid=" . Input::get('tid') . "&pid=" . Input::get('pid'));
					die();
				} catch(Exception $e){
					die($e->getMessage());
				}
			} else if(Input::get('type') === 'negative'){
				$reputation_id = $queries->getWhere("reputation", array("post_id", "=", Input::get('pid')));
				$rep_id = 0;
				
				foreach($reputation_id as $reputation){
					if($reputation->user_given == $user->data()->id){
						$rep_id = $reputation->id;
						break;
					}
				}
				if($rep_id == 0){
					Redirect::to("/forum/view_topic/?tid=" . Input::get('tid') . "&pid=" . Input::get('pid'));
					die();
				}
				try {
					$queries->delete("reputation", array("id", "=", $rep_id));
					$queries->decrement("users", Input::get('uid'), "reputation");
					Redirect::to("/forum/view_topic/?tid=" . Input::get('tid') . "&pid=" . Input::get('pid'));
					die();
				} catch(Exception $e){
					die($e->getMessage());
				}
			}
		}
	} else {
		// Invalid token
		Redirect::to("/forum/view_topic/?tid=" . Input::get('tid') . "&pid=" . Input::get('pid'));
		die();
	}
} else {
	Redirect::to("/forum");
	die();
}
?>