<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Ensure user is logged in, and is admin
if($user->isLoggedIn()){
	if($user->canViewACP($user->data()->id)){
		if($user->isAdmLoggedIn()){
			// Can view
		} else {
			Redirect::to('/admin');
			die();
		}
	} else {
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}
 
$adm_page = "forums";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	<script>var groups = [];</script>
	<?php
	// Generate header and navbar content
	// Page title
	$title = $admin_language['forums'];
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	textarea {
		resize: none;
	}
	</style>

  </head>

  <body>
	<?php
	// Forums page
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	if(Session::exists('adm-alert')){
		echo Session::flash('adm-alert');
	}
	?>
	<div class="container">
	  <br />
	  <div class="row">
		<div class="col-md-3">
			<?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <ul class="nav nav-pills">
			<li<?php if(!isset($_GET['view'])){ ?> class="active"<?php } ?>><a href="/admin/forums"><?php echo $admin_language['forums']; ?></a></li>
			<li<?php if(isset($_GET['view']) && $_GET['view'] == 'labels'){ ?> class="active"<?php } ?>><a href="/admin/forums/?view=labels"><?php echo $admin_language['labels']; ?></a></li>
		  </ul>
		  <hr>
		  <?php 
		  if(!isset($_GET['view'])){
			if(Session::exists('adm-forums')){
				echo Session::flash('adm-forums');
			}
			if(!isset($_GET["action"]) && !isset($_GET["forum"])){
				if(Input::exists()) {
					if(Token::check(Input::get('token'))) {
						try {
							$forum_layout_id = $queries->getWhere("settings", array("name", "=", "forum_layout"));
							$forum_layout_id = $forum_layout_id[0]->id;
							$queries->update("settings", $forum_layout_id, array(
								'value' => Input::get('layout')
							));
							echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
							die();
						} catch(Exception $e){
							die($e->getMessage());
						}					
					} else {
						// Invalid token
						Session::flash('adm-forums', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
						echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
						die();
					}
				}
			?>
			<a href="/admin/forums/?action=new" class="btn btn-default"><?php echo $admin_language['new_forum']; ?></a>
			<br /><br />
			<?php 
			$forums = $queries->orderAll("forums", "forum_order", "ASC");
			$forum_layout = $queries->getWhere("settings", array("name", "=", "forum_layout"));
			$forum_layout = $forum_layout[0]->value;
			
			// Form token
			$token = Token::generate();
			
			// HTMLPurifier
			require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);
			?>

			<div class="panel panel-default">
				<div class="panel-heading"><?php echo $admin_language['forums']; ?></div>
				<div class="panel-body">
					<?php 
					$number = count($forums);
					$i = 1;
					foreach($forums as $forum){
					?>
					<div class="row">
						<div class="col-md-10">
							<?php echo '<a href="/admin/forums/?forum=' . $forum->id . '">' . $purifier->purify(htmlspecialchars_decode($forum->forum_title)) . '</a><br />' . $purifier->purify(htmlspecialchars_decode($forum->forum_description)); ?>
						</div>
						<div class="col-md-2">
							<span class="pull-right">
								<?php if($i !== 1){ ?><a href="/admin/forums/?action=order&amp;dir=up&amp;fid=<?php echo $forum->id;?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-arrow-up"></span></a><?php } ?>
								<?php if($i !== $number){ ?><a href="/admin/forums/?action=order&amp;dir=down&amp;fid=<?php echo $forum->id;?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-arrow-down"></span></a><?php } ?>
								<a href="/admin/forums/?action=delete&amp;fid=<?php echo $forum->id;?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
							</span>
						</div>
					</div>
					<?php 
					$i++;
					if((sizeof($forums)+1) != $i) { echo '<hr>'; } }
					?>

				</div>
			</div>
			
			<form action="" method="post">
				<div class="form-group">
				  <label for="InputLayout"><?php echo $admin_language['forum_layout']; ?></label>
				  <select class="form-control" id="InputLayout" name="layout">
					<option value="0" <?php if($forum_layout == 0){ echo ' selected="selected"'; } ?>><?php echo $admin_language['table_view']; ?></option>
					<option value="1" <?php if($forum_layout == 1){ echo ' selected="selected"'; } ?>><?php echo $admin_language['latest_discussions_view']; ?></option>
				  </select>
				</div>
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>" />
			</form>
			
			<?php 
			} else if(isset($_GET["action"])){
				if($_GET["action"] === "new"){
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							$validate = new Validate();
							$validation = $validate->check($_POST, array(
								'forumname' => array(
									'required' => true,
									'min' => 2,
									'max' => 150
								),
								'forumdesc' => array(
									'required' => true,
									'min' => 2,
									'max' => 255
								)
							));
							
							if($validation->passed()){
								$last_forum_order = $queries->orderAll('forums', 'forum_order', 'DESC');
								$last_forum_order = $last_forum_order[0]->forum_order;
								try {
									$queries->create("forums", array(
										'forum_title' => htmlspecialchars(Input::get('forumname')),
										'forum_description' => htmlspecialchars(Input::get('forumdesc')),
										'forum_type' => Input::get('forum_type'),
										'forum_order' => $last_forum_order + 1
									));
									$forum_id = $queries->getLastId();
									echo '<script data-cfasync="false">window.location.replace("/admin/forums/?forum=' . $forum_id . '");</script>';
									die();
								} catch(Exception $e){
									die($e->getMessage());
								}
							}
						} else {
							echo $admin_language['invalid_token'] . ' - <a href="/admin/forums">' . $general_language['back'] . '</a>';
							die();
						}
					}
					if(isset($validation)){
						if(!$validation->passed()){
					?>
					<div class="alert alert-danger">
					  <?php
					  foreach($validation->errors() as $error) {
						  if(strpos($error, 'is required') !== false){
							switch($error){
								case (strpos($error, 'forumname') !== false):
									echo $admin_language['input_forum_title'] . '<br />';
								break;
								case (strpos($error, 'forumdesc') !== false):
									echo $admin_language['input_forum_description'] . '<br />';
								break;
							}
						  } else if(strpos($error, 'minimum') !== false){
							switch($error){
								case (strpos($error, 'forumname') !== false):
									echo $admin_language['forum_name_minimum'] . '<br />';
								break;
								case (strpos($error, 'forumdesc') !== false):
									echo $admin_language['forum_description_minimum'] . '<br />';
								break;
							}
						  } else if(strpos($error, 'maximum') !== false){
							switch($error){
								case (strpos($error, 'forumname') !== false):
									echo $admin_language['forum_name_maximum'] . '<br />';
								break;
								case (strpos($error, 'forumdesc') !== false):
									echo $admin_language['forum_description_maximum'] . '<br />';
								break;
							}
						  }
					  }
					  ?>
					</div>
					<?php 
						}
					}
					?>
					<form action="" method="post">
						<h2><?php echo $admin_language['create_forum']; ?></h2>
						<div class="form-group">
							<input class="form-control" type="text" name="forumname" id="forumname" value="<?php echo escape(Input::get('forumname')); ?>" placeholder="<?php echo $admin_language['forum_name']; ?>" autocomplete="off">
						</div>
						<div class="form-group">
							<textarea name="forumdesc" placeholder="<?php echo $admin_language['forum_description']; ?>" class="form-control" rows="3"></textarea>
						</div>
						<div class="form-group">
							<input type="radio" name="forum_type" value="forum" checked> <span class="label label-default"><?php echo $admin_language['forum_type_forum']; ?></span>
							<input type="radio" name="forum_type" value="category"> <span class="label label-default"><?php echo $admin_language['forum_type_category']; ?></span>
						</div>
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						<input class="btn btn-success" type="submit" value="<?php echo $general_language['submit']; ?>">	
					</form>
					<?php 
				} else if($_GET["action"] === "order"){
					if(!isset($_GET["dir"]) || !isset($_GET["fid"]) || !is_numeric($_GET["fid"])){
						echo $admin_language['invalid_action'] . ' - <a href="/admin/forums">' . $general_language['back'] . '</a>';
						die();
					}
					if($_GET["dir"] === "up" || $_GET["dir"] === "down"){
						$dir = $_GET["dir"];
					} else {
						echo $admin_language['invalid_action'] . ' - <a href="/admin/forums">' . $general_language['back'] . '</a>';
						die();
					}
					
					$forum_id = $queries->getWhere('forums', array("id", "=", $_GET["fid"]));
					$forum_id = $forum_id[0]->id;
					
					$forum_order = $queries->getWhere('forums', array("id", "=", $_GET["fid"]));
					$forum_order = $forum_order[0]->forum_order;
					
					$previous_forums = $queries->orderAll("forums", "forum_order", "ASC");
					
					if($dir == "up"){
						$n = 0;
						foreach($previous_forums as $previous_forum){
							if($previous_forum->id == $_GET["fid"]){
								$previous_fid = $previous_forums[$n - 1]->id;
								$previous_f_order = $previous_forums[$n - 1]->forum_order;
								break;
							}
							$n++;
						}

						try {
							$queries->update("forums", $forum_id, array(
								'forum_order' => $previous_f_order
							));	
							$queries->update("forums", $previous_fid, array(
								'forum_order' => $previous_f_order + 1
							));	
						} catch(Exception $e){
							die($e->getMessage());
						}
						echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
						die();

					} else if($dir == "down"){
						$n = 0;
						foreach($previous_forums as $previous_forum){
							if($previous_forum->id == $_GET["fid"]){
								$previous_fid = $previous_forums[$n + 1]->id;
								$previous_f_order = $previous_forums[$n + 1]->forum_order;
								break;
							}
							$n++;
						}
						try {
							$queries->update("forums", $forum_id, array(
								'forum_order' => $previous_f_order
							));	
							$queries->update("forums", $previous_fid, array(
								'forum_order' => $previous_f_order - 1
							));	
						} catch(Exception $e){
							die($e->getMessage());
						}
						echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
						die();
						
					}
					
				} else if($_GET["action"] === "delete"){
					if(!isset($_GET["fid"]) || !is_numeric($_GET["fid"])){
						echo $admin_language['invalid_action'] . ' - <a href="/admin/forums">' . $general_language['back'] . '</a>';
						die();
					}
					
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							if(Input::get('confirm') === 'true'){
								$forum_perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET["fid"])); // Get permissions to be deleted
								if(Input::get('move_forum') === 'none'){
									$posts = $queries->getWhere('posts', array('forum_id', '=', $_GET["fid"]));
									$topics = $queries->getWhere('topics', array('forum_id' , '=', $_GET["fid"]));
									try {
										foreach($posts as $post){
											$queries->delete('posts', array('id', '=' , $post->id));
										}
										foreach($topics as $topic){
											$queries->delete('topics', array('id', '=' , $topic->id));
										}
										$queries->delete('forums', array('id', '=' , $_GET["fid"]));
										// Forum perm deletion
										foreach($forum_perms as $perm){
											$queries->delete('forums_permissions', array('id', '=', $perm->id));
										}
										
										echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
										die();
									} catch(Exception $e) {
										die($e->getMessage());
									}
								} else {
									$new_forum = Input::get('move_forum');
									$posts = $queries->getWhere('posts', array('forum_id', '=', $_GET["fid"]));
									$topics = $queries->getWhere('topics', array('forum_id' , '=', $_GET["fid"]));
									try {
										foreach($posts as $post){
											$queries->update('posts', $post->id, array(
												'forum_id' => $new_forum
											));
										}
										foreach($topics as $topic){
											$queries->update('topics', $topic->id, array(
												'forum_id' => $new_forum
											));
										}
										$queries->delete('forums', array('id', '=' , $_GET["fid"]));
										// Forum perm deletion
										foreach($forum_perms as $perm){
											$queries->delete('forums_permissions', array('id', '=', $perm->id));
										}
										echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
										die();
									} catch(Exception $e) {
										die($e->getMessage());
									}
								}
							}
						} else {
							echo $admin_language['invalid_token'] . ' - <a href="/admin/forums">' . $general_language['back'] . '</a>';
							die();
						}
					}
					?>
					<h2><?php echo $admin_language['delete_forum']; ?></h2>
					<form role="form" action="" method="post">
					    <strong><?php echo $admin_language['move_topics_and_posts_to']; ?></strong>
						<select class="form-control" name="move_forum">
						  <option value="none" selected><?php echo $admin_language['delete_topics_and_posts']; ?></option>
						  <?php 
							$forums = $queries->orderAll("forums", "forum_order", "ASC");
							foreach($forums as $forum){
								if($forum->id !== $_GET["fid"]){
									echo '<option value="' . $forum->id . '">' . htmlspecialchars($forum->forum_title) . '</option>';
								}
							}
						  ?>
						</select>
					  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					  <input type="hidden" name="confirm" value="true">
					  <br />
					  <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-danger">
					</form>
					<?php 
				}
			} else if(isset($_GET["forum"])){
				$available_forums = $queries->getWhere("forums", array("id", "<>", 0)); // Get a list of all forums which can be chosen as a parent
				$groups = $queries->getWhere('groups', array('id', '<>', '0')); // Get a list of all groups
				
				if(Input::exists()) {
					if(Token::check(Input::get('token'))) {
						if(Input::get('action') === "update"){
							$validate = new Validate();
							$validation = $validate->check($_POST, array(
								'title' => array(
									'required' => true,
									'min' => 2,
									'max' => 150
								),
								'description' => array(
									'required' => true,
									'min' => 2,
									'max' => 255
								)
							));
							
							if($validation->passed()){
								try {
									// Update the forum
									$queries->update('forums', $_GET["forum"], array(
										'forum_title' => htmlspecialchars(Input::get('title')),
										'forum_description' => htmlspecialchars(Input::get('description')),
										'news' => Input::get('display'),
										'parent' => Input::get('parent_forum')
									));
									
								} catch(Exception $e) {
									die($e->getMessage());
								}
								
								// Guest forum permissions
								$view = Input::get('perm-view-0');
								$create = Input::get('perm-topic-0');
								$post = Input::get('perm-post-0');
								
								$forum_perm_exists = 0;
								
								$forum_perm_query = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET["forum"]));
								if(count($forum_perm_query)){ 
									foreach($forum_perm_query as $query){
										if($query->group_id == 0){
											$forum_perm_exists = 1;
											$update_id = $query->id;
											break;
										}
									}
								}
								
								try {
									if($forum_perm_exists != 0){ // Permission already exists, update
									
										// Update the forum
										$queries->update('forums_permissions', $update_id, array(
											'view' => $view,
											'create_topic' => $create,
											'create_post' => $post
										));
									} else { // Permission doesn't exist, create
										$queries->create('forums_permissions', array(
											'group_id' => 0,
											'forum_id' => $_GET["forum"],
											'view' => $view,
											'create_topic' => $create,
											'create_post' => $post
										));
									}
									
								} catch(Exception $e) {
									die($e->getMessage());
								}
								
								// Group forum permissions
								foreach($groups as $group){ 
									$view = Input::get('perm-view-' . $group->id);
									$create = Input::get('perm-topic-' . $group->id);
									$post = Input::get('perm-post-' . $group->id);
									
									$forum_perm_exists = 0;

									if(count($forum_perm_query)){ 
										foreach($forum_perm_query as $query){
											if($query->group_id == $group->id){
												$forum_perm_exists = 1;
												$update_id = $query->id;
												break;
											}
										}
									}
									
									try {
										if($forum_perm_exists != 0){ // Permission already exists, update
										
											// Update the forum
											$queries->update('forums_permissions', $update_id, array(
												'view' => $view,
												'create_topic' => $create,
												'create_post' => $post
											));
										} else { // Permission doesn't exist, create
											$queries->create('forums_permissions', array(
												'group_id' => $group->id,
												'forum_id' => $_GET["forum"],
												'view' => $view,
												'create_topic' => $create,
												'create_post' => $post
											));
										}
										
									} catch(Exception $e) {
										die($e->getMessage());
									}
								}
								
								echo '<script data-cfasync="false">window.location.replace("/admin/forums");</script>';
								die();
								
							} else {
								echo '<div class="alert alert-danger">';
								foreach($validation->errors() as $error) {
								  if(strpos($error, 'is required') !== false){
									switch($error){
										case (strpos($error, 'title') !== false):
											echo $admin_language['input_forum_title'] . '<br />';
										break;
										case (strpos($error, 'description') !== false):
											echo $admin_language['input_forum_description'] . '<br />';
										break;
									}
								  } else if(strpos($error, 'minimum') !== false){
									switch($error){
										case (strpos($error, 'title') !== false):
											echo $admin_language['forum_name_minimum'] . '<br />';
										break;
										case (strpos($error, 'description') !== false):
											echo $admin_language['forum_description_minimum'] . '<br />';
										break;
									}
								  } else if(strpos($error, 'maximum') !== false){
									switch($error){
										case (strpos($error, 'title') !== false):
											echo $admin_language['forum_name_maximum'] . '<br />';
										break;
										case (strpos($error, 'description') !== false):
											echo $admin_language['forum_description_maximum'] . '<br />';
										break;
									}
								  }
								}
								echo '</div>';
							}
						}
					} else {
						echo $admin_language['invalid_token'] . ' - <a href="/admin/forums">' . $general_language['back'] . '</a>';
						die();
					}
				}
				
				// Form token
				$token = Token::generate();
				
				// HTMLPurifier
				require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
				$config = HTMLPurifier_Config::createDefault();
				$purifier = new HTMLPurifier($config);
				
				if(!is_numeric($_GET["forum"])){
					die();
				} else {
					$forum = $queries->getWhere("forums", array("id", "=", $_GET["forum"]));
				}
				if(count($forum)){
					echo '<h2 style="display: inline;">' . htmlspecialchars($forum[0]->forum_title) . '</h2>';
					?>
					<br /><br />
					<form role="form" action="" method="post">
					  <div class="form-group">
						<label for="InputTitle"><?php echo $admin_language['forum_name']; ?></label>
						<input type="text" name="title" class="form-control" id="InputTitle" placeholder="<?php echo $admin_language['forum_name']; ?>" value="<?php echo $purifier->purify(htmlspecialchars_decode($forum[0]->forum_title)); ?>">
					  </div>
					  <div class="form-group">
					    <label for="InputDescription"><?php echo $admin_language['forum_description']; ?></label>
						<textarea name="description" id="InputDescription" placeholder="<?php echo $admin_language['forum_description']; ?>" class="form-control" rows="3"><?php echo $purifier->purify(htmlspecialchars_decode($forum[0]->forum_description)); ?></textarea>
				      </div>
						<div class="form-group" <?php if($forum[0]->forum_type == 'category') { echo'style="display:none;"'; } ?>>
							<label for="InputParentForum"><?php echo $admin_language['parent_forum']; ?></label>
							<select class="form-control" id="InputParentForum" name="parent_forum">
								<option value="0" <?php if($forum[0]->parent == 0){ echo ' selected="selected"'; } ?>><?php echo $admin_language['has_no_parent']; ?></option>
								<?php
								foreach($available_forums as $available_forum){
									if($available_forum->id !== $forum[0]->id){
										?>
										<option value="<?php echo $available_forum->id; ?>" <?php if($available_forum->id == $forum[0]->parent){ ?> selected="selected"<?php } ?>><?php echo $purifier->purify(htmlspecialchars_decode($available_forum->forum_title)); ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
					  <div class="form-group">
						<strong><?php echo $admin_language['forum_permissions']; ?></strong><br />
						<?php
						// Get all forum permissions
						$group_perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET["forum"]));
						?>

						<?php
						foreach($group_perms as $group_perm){
							if($group_perm->group_id == 0){
								$view = $group_perm->view;
								break;
							}
						}
						?>

					    <script>groups.push("0");</script>
						<input type="hidden" name="perm-topic-0" value="0" />
						<input type="hidden" name="perm-post-0" value="0" />
						
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Group</th>
									<th><?php echo $admin_language['can_view_forum']; ?></th>
									<th><?php echo $admin_language['can_create_topic']; ?></th>
									<th><?php echo $admin_language['can_post_reply']; ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Guest</td>
									<td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>

								<?php
								foreach($groups as $group){
									// Get the existing group permissions
									$view = 0;
									$topic = 0;
									$post = 0;
									
									foreach($group_perms as $group_perm){
										if($group_perm->group_id == $group->id){
											$view = $group_perm->view;
											$topic = $group_perm->create_topic;
											$post = $group_perm->create_post;
											break;
										}
									}
								?>
								<tr>
									<td><?php echo htmlspecialchars($group->name); ?></td>
									<td><input type="hidden" name="perm-view-<?php echo $group->id; ?>" value="0" /> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
									<td><input type="hidden" name="perm-topic-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-topic-<?php echo $group->id; ?>" id="Input-topic-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($topic) && $topic == 1){ echo ' checked'; } ?>></td>
									<td><input type="hidden" name="perm-post-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-post-<?php echo $group->id; ?>" id="Input-post-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($post) && $post == 1){ echo ' checked'; } ?>></td>
								</tr>
								<script>groups.push("<?php echo $group->id; ?>");</script>
								<?php
								}
								?>
							</tbody>	
						</table>
					  </div>
					  <input type="hidden" name="display" value="0" />
					  <label for="InputDisplay"><?php echo $admin_language['display_threads_as_news']; ?></label>
					  <input name="display" id="InputDisplay" value="1" type="checkbox"<?php if($forum[0]->news == 1){ echo ' checked'; } ?>>
					  <br /><br />
					  <input type="hidden" name="token" value="<?php echo $token; ?>">
					  <input type="hidden" name="action" value="update">
					  <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-default">
					</form>
					<?php 
				}
			}
		  } else {
			  if($_GET['view'] == 'labels'){
				if(!isset($_GET['action'])){
				  // Topic labels
				  $topic_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
				  ?>
				  <a href="/admin/forums/?view=labels&amp;action=create" class="btn btn-default"><?php echo $admin_language['new_label']; ?></a><br /><br />
				  <?php
				  if(Session::exists('forum_labels')){
					echo Session::flash('forum_labels');
				  }
				  if(count($topic_labels)){
					?>
				  <div class="panel panel-default">
				    <div class="panel-heading"><?php echo $admin_language['labels']; ?></div>
					<div class="panel-body">
					<?php
					// Display list of all labels
					foreach($topic_labels as $topic_label){
					?>
					<h4 style="display:inline;"><span class="label label-<?php echo htmlspecialchars($topic_label->label); ?>"><?php echo htmlspecialchars($topic_label->name); ?></span></h4>
					<span class="pull-right">
					  <a href="/admin/forums/?view=labels&amp;action=edit&amp;lid=<?php echo $topic_label->id; ?>" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
					  <a onclick="return confirm('<?php echo $admin_language['confirm_label_deletion']; ?>');" href="/admin/forums/?view=labels&amp;action=delete&amp;lid=<?php echo $topic_label->id; ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
					</span>
					<br /><br />
					<?php
					// Display list of forums the label is enabled in
					$enabled_forums = explode(',', $topic_label->fids);
					$forums_string = '';
					foreach($enabled_forums as $item){
						$forum_name = $queries->getWhere('forums', array('id', '=', $item));
						$forums_string .= htmlspecialchars($forum_name[0]->forum_title) . ', ';
					}
					echo rtrim($forums_string, ', ');
					?>
					<hr>
					<?php
					}
				  ?>
					</div>
				  </div>
				  <?php
				  } else {
					// No labels defined yet
					echo '<div class="alert alert-warning">' . $admin_language['no_labels_defined'] . '</div>'; 
				  }
				} else if(isset($_GET['action']) && $_GET['action'] == 'create'){
					// Deal with input
					if(Input::exists()){
						// Check token
						if(Token::check(Input::get('token'))){
							// Valid token
							// Validate input
							$validate = new Validate();
							
							$validation = $validate->check($_POST, array(
								'label_name' => array(
									'required' => true,
									'min' => 1,
									'max' => 32
								),
								'label_type' => array(
									'required' => true
								)
							));
							
							if($validation->passed()){
								// Create string containing selected forum IDs
								$forum_string = '';
								foreach(Input::get('label_forums') as $item){
									// Turn array of inputted forums into string of forums
									$forum_string .= $item . ',';
								}

								$forum_string = rtrim($forum_string, ',');
								
								try {
									$queries->create('forums_topic_labels', array(
										'fids' => $forum_string,
										'name' => htmlspecialchars(Input::get('label_name')),
										'label' => htmlspecialchars(Input::get('label_type'))
									));
									
									Session::flash('forum_labels', '<div class="alert alert-info">' . $admin_language['label_creation_success'] . '</div>');
									echo '<script data-cfasync="false">window.location.replace("/admin/forums/?view=labels");</script>';
									die();
								} catch(Exception $e){
									die($e->getMessage());
								}
								
							} else {
								// Validation errors
								Session::flash('new_label_error', '<div class="alert alert-danger">' . $admin_language['label_creation_error'] . '</div>');
							}
							
						} else {
							// Invalid token
							Session::flash('new_label_error', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
						}
					}
					?>
				  <div class="well well-sm">
					<h2> <?php echo $admin_language['new_label']; ?></h2>
					<?php
					if(Session::exists('new_label_error')){
						echo Session::flash('new_label_error');
					}
					?>
					<form action="" method="post">
					  <div class="form-group">
					    <label for="label_name"><?php echo $admin_language['label_name']; ?></label>
					    <input type="text" name="label_name" placeholder="<?php echo $admin_language['label_name']; ?>" id="label_name" class="form-control">
					  </div>
					  <div class="form-group">
					    <label for="label_type"><?php echo $admin_language['label_type']; ?></label><br />
						<div class="row">
						  <div class="col-md-2">
						    <input type="radio" name="label_type" id="label_type" value="default"> <span class="label label-default"><?php echo $admin_language['label_default']; ?></span><br />
					      </div>
						  <div class="col-md-2">
						    <input type="radio" name="label_type" id="label_type" value="primary"> <span class="label label-primary"><?php echo $admin_language['label_primary']; ?></span><br />
					      </div>
						  <div class="col-md-2">
						    <input type="radio" name="label_type" id="label_type" value="success"> <span class="label label-success"><?php echo $admin_language['label_success']; ?></span><br />
					      </div>
						  <div class="col-md-2">
						    <input type="radio" name="label_type" id="label_type" value="info"> <span class="label label-info"><?php echo $admin_language['label_info']; ?></span><br />
					      </div>
						  <div class="col-md-2">
						    <input type="radio" name="label_type" id="label_type" value="warning"> <span class="label label-warning"><?php echo $admin_language['label_warning']; ?></span><br />
					      </div>
						  <div class="col-md-2">
						    <input type="radio" name="label_type" id="label_type" value="danger"> <span class="label label-danger"><?php echo $admin_language['label_danger']; ?></span><br />
					      </div>
						</div>
					  </div>
					  <div class="form-group">
					    <label for="label_forums"><?php echo $admin_language['label_forums']; ?></label>
						<select name="label_forums[]" id="label_forums" size="5" class="form-control" multiple>
						  <?php 
						  $forum_list = $queries->getWhere('forums', array('parent', '<>', 0)); 
						  foreach($forum_list as $item){
						  ?>
						  <option value="<?php echo $item->id; ?>"><?php echo htmlspecialchars($item->forum_title); ?></option>
						  <?php
						  }
						  ?>
						</select>
					  </div>
					  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
					</form>
					<?php
				} else if(isset($_GET['action']) && $_GET['action'] == 'edit'){
					// Editing a label
					if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
						// Check the label ID is valid
						echo '<script data-cfasync="false">window.location.replace("/admin/forums/?view=labels");</script>';
						die();
					}
					
					// Does the label exist?
					$label = $queries->getWhere('forums_topic_labels', array('id', '=', $_GET['lid']));
					if(!count($label)){
						// No, it doesn't exist
						echo '<script data-cfasync="false">window.location.replace("/admin/forums/?view=labels");</script>';
						die();
					} else {
						$label = $label[0];
					}
					
					// Deal with input
					if(Input::exists()){
						// Check token
						if(Token::check(Input::get('token'))){
							// Valid token
							// Validate input
							$validate = new Validate();
							
							$validation = $validate->check($_POST, array(
								'label_name' => array(
									'required' => true,
									'min' => 1,
									'max' => 32
								),
								'label_type' => array(
									'required' => true
								)
							));
							
							if($validation->passed()){
								// Create string containing selected forum IDs
								$forum_string = '';
								foreach(Input::get('label_forums') as $item){
									// Turn array of inputted forums into string of forums
									$forum_string .= $item . ',';
								}

								$forum_string = rtrim($forum_string, ',');
								
								try {
									$queries->update('forums_topic_labels', $label->id, array(
										'fids' => $forum_string,
										'name' => htmlspecialchars(Input::get('label_name')),
										'label' => htmlspecialchars(Input::get('label_type'))
									));
									
									Session::flash('forum_labels', '<div class="alert alert-info">' . $admin_language['label_edit_success'] . '</div>');
									echo '<script data-cfasync="false">window.location.replace("/admin/forums/?view=labels");</script>';
									die();
								} catch(Exception $e){
									die($e->getMessage());
								}
								
							} else {
								// Validation errors
								Session::flash('editing_label_error', '<div class="alert alert-danger">' . $admin_language['label_creation_error'] . '</div>');
							}
							
						} else {
							// Invalid token
							Session::flash('editing_label_error', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
						}
					}
					?>
					<div class="well well-sm">
					  <h2><?php echo $admin_language['editing_label']; ?></h2>
					<?php
					if(Session::exists('editing_label_error')){
						echo Session::flash('editing_label_error');
					}
					?>
					  <form action="" method="post">
					    <div class="form-group">
					      <label for="label_name"><?php echo $admin_language['label_name']; ?></label>
					      <input type="text" name="label_name" placeholder="<?php echo $admin_language['label_name']; ?>" id="label_name" value="<?php echo htmlspecialchars($label->name); ?>" class="form-control">
					    </div>
					    <div class="form-group">
					      <label for="label_type"><?php echo $admin_language['label_type']; ?></label><br />
						  <div class="row">
						    <div class="col-md-2">
						      <input type="radio" name="label_type" id="label_type" value="default"<?php if($label->label == 'default'){ ?> checked<?php } ?>> <span class="label label-default"><?php echo $admin_language['label_default']; ?></span><br />
					        </div>
						    <div class="col-md-2">
						      <input type="radio" name="label_type" id="label_type" value="primary"<?php if($label->label == 'primary'){ ?> checked<?php } ?>> <span class="label label-primary"><?php echo $admin_language['label_primary']; ?></span><br />
					        </div>
						    <div class="col-md-2">
						      <input type="radio" name="label_type" id="label_type" value="success"<?php if($label->label == 'success'){ ?> checked<?php } ?>> <span class="label label-success"><?php echo $admin_language['label_success']; ?></span><br />
					        </div>
						    <div class="col-md-2">
						      <input type="radio" name="label_type" id="label_type" value="info"<?php if($label->label == 'info'){ ?> checked<?php } ?>> <span class="label label-info"><?php echo $admin_language['label_info']; ?></span><br />
					        </div>
						    <div class="col-md-2">
						      <input type="radio" name="label_type" id="label_type" value="warning"<?php if($label->label == 'warning'){ ?> checked<?php } ?>> <span class="label label-warning"><?php echo $admin_language['label_warning']; ?></span><br />
					        </div>
						    <div class="col-md-2">
						      <input type="radio" name="label_type" id="label_type" value="danger"<?php if($label->label == 'danger'){ ?> checked<?php } ?>> <span class="label label-danger"><?php echo $admin_language['label_danger']; ?></span><br />
					        </div>
						  </div>
					    </div>
					    <div class="form-group">
					      <label for="label_forums"><?php echo $admin_language['label_forums']; ?></label>
						  <select name="label_forums[]" id="label_forums" size="5" class="form-control" multiple>
						    <?php 
							// Get a list of forums in which the label is enabled
							$enabled_forums = explode(',', $label->fids);
							
							// Get a list of all forums
						    $forum_list = $queries->getWhere('forums', array('parent', '<>', 0)); 
						    foreach($forum_list as $item){
						    ?>
						    <option value="<?php echo $item->id; ?>"<?php if(in_array($item->id, $enabled_forums)){ ?> selected<?php } ?>><?php echo htmlspecialchars($item->forum_title); ?></option>
						    <?php
						    }
						    ?>
						  </select>
					    </div>
					    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					    <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
					  </form>
					</div>
					<?php
				} else if(isset($_GET['action']) && $_GET['action'] == 'delete'){
					// Label deletion
					if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
						// Check the label ID is valid
						echo '<script data-cfasync="false">window.location.replace("/admin/forums/?view=labels");</script>';
						die();
					}
					try {
						// Delete the label
						$queries->delete('forums_topic_labels', array('id', '=', $_GET['lid']));
						echo '<script data-cfasync="false">window.location.replace("/admin/forums/?view=labels");</script>';
						die();
					} catch(Exception $e){
						die($e->getMessage());
					}
				}
			  }
		  }
		  ?>
		</div>
      </div>
    </div>
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts
	require('core/includes/template/scripts.php'); 
	?>
  </body>
  <script type="text/javascript">
  	function colourUpdate(that) {
    	var x = that.parentElement;
    	if(that.checked) {
    		x.className = "success";
    	} else {
    		x.className = "danger";
    	}
	}
	function toggle(group) {
		if(document.getElementById('Input-view-' + group).checked) {
			document.getElementById('Input-view-' + group).checked = false;
		} else {
			document.getElementById('Input-view-' + group).checked = true;
		}
		if(document.getElementById('Input-topic-' + group).checked) {
			document.getElementById('Input-topic-' + group).checked = false;
		} else {
			document.getElementById('Input-topic-' + group).checked = true;
		}
		if(document.getElementById('Input-post-' + group).checked) {
			document.getElementById('Input-post-' + group).checked = false;
		} else {
			document.getElementById('Input-post-' + group).checked = true;
		}

		colourUpdate(document.getElementById('Input-view-' + group));
		colourUpdate(document.getElementById('Input-topic-' + group));
		colourUpdate(document.getElementById('Input-post-' + group));
	}
	for(var g in groups) {
		colourUpdate(document.getElementById('Input-view-' + groups[g]));
		if(groups[g] != "0") {
			colourUpdate(document.getElementById('Input-topic-' + groups[g]));
			colourUpdate(document.getElementById('Input-post-' + groups[g]));
		}
	}
  </script>
</html>
