<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  Converter from XenForo
 *  
 *  Converts:
 *     - Bans
 *     - Categories/Forums
 *     - Groups
 *     - Posts and Topics
 *     - Reports
 *     - Users
 *     - Reputation
 */
 
if(!isset($queries)){
	$queries = new Queries();
}
 
/*
 *  First, check the database connection specified in the form submission
 */
 
$mysqli = new mysqli(Input::get("db_address"), Input::get("db_username"), Input::get("db_password"), Input::get("db_name"));

if($mysqli->connect_errno) {
	header('Location: /install/?step=convert&convert=yes&from=xenforo&error=true');
	die();
}

/*
 *  Get the table prefix
 */

$prefix = 'xf_';


/*
 *  Users
 */

/*
 *  Query the database
 */
 
$xf_users = $mysqli->query("SELECT * FROM {$prefix}user");

/*
 *  Loop through the users
 */
while($row = $xf_users->fetch_assoc()){
	if($row['username'] == $user->data()->username){
		$queries->update('users', $user->data()->id, array(
			"id" => $row["user_id"]
		));
		$queries->update('users_session', 1, array(
			"user_id" => $row["user_id"]
		));
	} else {
		// Get the user's group info
		$group = $row["user_group_id"];
		
		if($group == 1){
			// unconfirmed, needs to be member
			$group_id = 1;
		} else if($group == 2){
			// member
			$group_id = 1;
		} else if($group == 3){
			// admin
			$group_id = 2;
		} else if($group == 4){
			// moderator
			$group_id = 3;
		} else {
			// member
			$group_id = 1;
		}
		
		$group = null;
		
		$queries->create("users", array(
			"username" => htmlspecialchars($row["username"]),
			"password" => '', // Blank for now, we'll fill it in soon
			"pass_method" => "default",
			"mcname" => htmlspecialchars($row["username"]),
			"uuid" => "",
			"joined" => $row["register_date"],
			"group_id" => $group_id,
			"email" => $row["email"],
			"isbanned" => $row["is_banned"],
			"lastip" => "",
			"active" => 1,
			"signature" => "", // We'll fill this in soon
			"reset_code" => "",
			"reputation" => $row["like_count"]
		));
	}
}

$xf_users = null;
 
/*
 *  Passwords
 */

$password = $mysqli->query("SELECT * FROM {$prefix}user_authenticate");

require('core/includes/password.php');

while($row = $password->fetch_assoc()){
	$user_id = $row['user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	$data = unserialize($row['data']);
	
	$new_password = $data['hash'];
	
	$queries->update('users', $user_id, array(
		'password' => $new_password
	));
	
}
$password = null;

/*
 *  Signatures
 */

$signatures = $mysqli->query("SELECT * FROM {$prefix}user_profile");
	
while($row = $signatures->fetch_assoc()){
	$user_id = $row['user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	$queries->update('users', $user_id, array(
		'signature' => htmlspecialchars($row['signature'])
	));
}

$signatures = null;
	
/* 
 *  Groups
 */
 
$xf_groups = $mysqli->query("SELECT * FROM {$prefix}user_group");

/*
 *  Loop through the groups
 */
while($row = $xf_groups->fetch_assoc()) {
	if($row["user_group_id"] == 1 || $row["user_group_id"] == 2 || $row["user_group_id"] == 3 || $row["user_group_id"] == 4){
		continue;
	}
	
	$queries->create("groups", array(
		"id" => $row["user_group_id"],
		"name" => htmlspecialchars($row["title"]),
		'group_html' => htmlspecialchars($row["title"]),
		'group_html_lg' => htmlspecialchars($row["title"])
	));
}

$xf_groups = null;

/*
 *  Nodes
 */

$xf_nodes = $mysqli->query("SELECT * FROM {$prefix}node");

while($row = $xf_nodes->fetch_assoc()){
	$queries->create("forums", array(
		"id" => $row["node_id"],
		"forum_title" => htmlspecialchars($row["title"]),
		"forum_description" => htmlspecialchars($row["description"]),
		"forum_order" => $row["display_order"],
		"parent" => $row["parent_node_id"]
	));
}

$xf_nodes = null;

/*
 *  Topics
 */

$xf_topics = $mysqli->query("SELECT * FROM {$prefix}thread");

while($row = $xf_topics->fetch_assoc()){
	// get new user ID of author
	$user_id = $row['user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	// get new user ID of last poster
	$last_user_id = $row['last_post_user_id'];
	
	$last_user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$last_user_id}");
	$last_user_id = $last_user_id->fetch_assoc();
	
	$last_user_id = $last_user_id['username'];
	
	$last_user_id = $queries->getWhere('users', array('username', '=', $last_user_id));
	$last_user_id = $last_user_id[0]->id;
	
	// is the topic locked?
	if($row['discussion_open'] == 0){
		$locked = 1;
	} else {
		$locked = 0;
	}
	
	$queries->create('topics', array(
		'id' => $row['thread_id'],
		'forum_id' => $row['node_id'],
		'topic_title' => htmlspecialchars($row['title']),
		'topic_creator' => $user_id,
		'topic_last_user' => $last_user_id,
		'topic_date' => $row['post_date'],
		'topic_reply_date' => $row['last_post_date'],
		'topic_views' => $row['view_count'],
		'locked' => $locked,
		'sticky' => $row['sticky']
	));
}

$xf_topics = null;

/*
 *  Posts
 */
 
$xf_posts = $mysqli->query("SELECT * FROM {$prefix}post");

while($row = $xf_posts->fetch_assoc()){
	// get user ID of poster
	$user_id = $row['user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	// get forum ID of post
	$thread_id = $row['thread_id'];
	
	$forum_id = $mysqli->query("SELECT * FROM {$prefix}thread WHERE thread_id = {$thread_id}");
	$forum_id = $forum_id->fetch_assoc();
	
	$forum_id = $forum_id['node_id'];
	
	$queries->create('posts', array(
		'id' => $row['post_id'],
		'forum_id' => $forum_id,
		'topic_id' => $row['thread_id'],
		'post_creator' => $user_id,
		'post_content' => htmlspecialchars($row['message']),
		'post_date' => date('Y-m-d H:i:s', $row['post_date'])
	));
}

$xf_posts = null;

/* 
 *  Forums
 */

$xf_forums = $mysqli->query("SELECT * FROM {$prefix}forum");

while($row = $xf_forums->fetch_assoc()){
	// get node ID of forum
	$node_id = $row['node_id'];
	
	// get new ID of last user posted
	$user_id = $row['last_post_user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	// get last topic ID posted in
	$post_id = $row['last_post_id'];
	$topic_id = $mysqli->query("SELECT * FROM {$prefix}post WHERE post_id = {$post_id}");
	$topic_id = $topic_id->fetch_assoc();
	
	$topic_id = $topic_id['thread_id'];
	
	$queries->update("forums", $node_id, array(
		'last_post_date' => date('Y-m-d H:i:s', $row['last_post_date']),
		'last_user_posted' => $user_id,
		'last_topic_posted' => $topic_id
	));
}

$xf_forums = null;

/*
 *  Bans
 */ 

$xf_bans = $mysqli->query("SELECT * FROM {$prefix}user_ban");

while($row = $xf_bans->fetch_assoc()){
	// get ID of banned user
	$banned_user_id = $row['ban_user_id'];
	
	$banned_user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$banned_user_id}");
	$banned_user_id = $banned_user_id->fetch_assoc();
	
	$banned_user_id = $banned_user_id['username'];
	
	$banned_user_id = $queries->getWhere('users', array('username', '=', $banned_user_id));
	$banned_user_id = $banned_user_id[0]->id;
	
	// get ID of staff
	$user_id = $row['ban_user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	$queries->create('infractions', array(
		'type' => 1,
		'punished' => $banned_user_id,
		'staff' => $user_id,
		'reason' => htmlspecialchars($row['user_reason']),
		'infraction_date' => date('Y-m-d H:i:s', $row['ban_date'])
	));
}

$xf_bans = null;

/*
 *  Reports
 */ 
 
$xf_reports = $mysqli->query("SELECT * FROM {$prefix}report");

while($row = $xf_reports->fetch_assoc()){
	if($row['report_state'] == 'open'){
		$status = 0; // open
	} else {
		$status = 1; // closed
	}
	
	// get ID of last user
	$last_user_id = $row['last_modified_user_id'];
	
	$last_user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$last_user_id}");
	$last_user_id = $last_user_id->fetch_assoc();
	
	$last_user_id = $last_user_id['username'];
	
	$last_user_id = $queries->getWhere('users', array('username', '=', $last_user_id));
	$last_user_id = $last_user_id[0]->id;
	
	// get ID of reported user
	$user_id = $row['content_user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	// get topic ID of post
	$topic_id = $queries->getWhere('posts', array('id', '=', $row['content_id']));
	$topic_id = $topic_id[0]->topic_id;
	
	$queries->create('reports', array(
		'id' => $row['report_id'],
		'type' => 0,
		'reporter_id' => $last_user_id,
		'reported_id' => $user_id,
		'status' => $status,
		'date_reported' => date('Y-m-d H:i:s', $row['first_report_date']),
		'date_updated' => date('Y-m-d H:i:s', $row['last_modified_date']),
		'report_reason' => 'See comments',
		'updated_by' => $last_user_id,
		'reported_post' => $row['content_id'],
		'reported_post_topic' => $topic_id
	));
	
}

$xf_reports = null;

/*
 *  Reports comments
 */

$xf_reports = $mysqli->query("SELECT * FROM {$prefix}report_comment");

while($row = $xf_reports->fetch_assoc()){
	// get user ID 
	$user_id = $row['user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	$queries->create('reports_comments', array(
		'id' => $row['report_comment_id'],
		'report_id' => $row['report_id'],
		'comment_date' => date('Y-m-d H:i:s', $row['comment_date']),
		'commenter_id' => $user_id,
		'comment_content' => substr(htmlspecialchars($row['message']), 0, 255)
	));
}

$xf_reports = null;
 
/* 
 *  Reputation
 */
 
$xf_likes = $mysqli->query("SELECT * FROM {$prefix}liked_content");

while($row = $xf_likes->fetch_assoc()){
	// get new user ID who liked
	$user_id = $row['like_user_id'];
	
	$user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$user_id}");
	$user_id = $user_id->fetch_assoc();
	
	$user_id = $user_id['username'];
	
	$user_id = $queries->getWhere('users', array('username', '=', $user_id));
	$user_id = $user_id[0]->id;
	
	// get new user ID whose content was liked
	$liked_user_id = $row['content_user_id'];
	
	$liked_user_id = $mysqli->query("SELECT * FROM {$prefix}user WHERE user_id = {$liked_user_id}");
	$liked_user_id = $liked_user_id->fetch_assoc();
	
	$liked_user_id = $liked_user_id['username'];
	
	$liked_user_id = $queries->getWhere('users', array('username', '=', $liked_user_id));
	$liked_user_id = $liked_user_id[0]->id;
	
	// get topic ID
	$post_id = $row['content_id'];
	$topic_id = $mysqli->query("SELECT * FROM {$prefix}post WHERE post_id = {$post_id}");
	if(count($topic_id)){
		$topic_id = $topic_id->fetch_assoc();
		$topic_id = $topic_id['thread_id'];
	} else {
		$topic_id = 0;
	}
	
	$queries->create('reputation', array(
		'id' => $row['like_id'],
		'user_received' => $liked_user_id,
		'post_id' => $row['content_id'],
		'topic_id' => $topic_id,
		'user_given' => $user_id,
		'time_given' => date('Y-m-d H:i:s', $row['like_date'])
	));
}

$xf_likes = null;