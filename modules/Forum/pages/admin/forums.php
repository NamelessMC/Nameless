<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum module - admin forum page
 */

// Can the user view the AdminCP?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else {
		// Check the user has re-authenticated
		if(!$user->isAdmLoggedIn()){
			// They haven't, do so now
			Redirect::to(URL::build('/admin/auth'));
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
 
$page = 'admin';
$admin_page = 'forums';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<?php 
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php'); 
	?>
  
	<!-- Custom style -->
	<style>
	textarea {
		resize: none;
	}
	</style>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
  
  </head>

  <body>
    <div class="container">	
	  <?php require('modules/Core/pages/admin/navbar.php'); ?>
	  <div class="row">
		<div class="col-md-3">
		  <?php require('modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <ul class="nav nav-pills">
				<li class="nav-item">
				  <a class="nav-link<?php if(!isset($_GET['view'])) echo ' active'; ?>" href="<?php echo URL::build('/admin/forums'); ?>"><?php echo $forum_language->get('forum', 'forums'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($_GET['view']) && $_GET['view'] == 'labels') echo ' active'; ?>" href="<?php echo URL::build('/admin/forums/', 'view=labels'); ?>"><?php echo $forum_language->get('forum', 'labels'); ?></a>
				</li>
			  </ul>
		      <hr />
			  <h3 style="display:inline;"><?php echo $forum_language->get('forum', 'forums'); ?></h3>
			  <?php
			  if(!isset($_GET['view'])){
				if(!isset($_GET['action']) && !isset($_GET['forum'])){
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							try {
								// Get reactions value
								if(isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
								else $enabled = 0;
								
								$forum_reactions_id = $queries->getWhere('settings', array('name', '=', 'forum_reactions'));
								$forum_reactions_id = $forum_reactions_id[0]->id;
								$queries->update('settings', $forum_reactions_id, array(
									'value' => $enabled
								));
								
								Redirect::to(URL::build('/admin/forums'));
								die();
							} catch(Exception $e){
								die($e->getMessage());
							}					
						} else {
							// Invalid token
							Session::flash('adm-forums', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
							Redirect::to(URL::build('/admin/forums'));
							die();
						}
					}
				?>
				<span class="pull-right"><a href="<?php echo URL::build('/admin/forums/', 'action=new'); ?>" class="btn btn-primary"><?php echo $forum_language->get('forum', 'new_forum'); ?></a></span>
				<br /><br />
				<?php 
				if(Session::exists('adm-forums')){
					echo Session::flash('adm-forums');
				}
				$forums = $queries->orderAll('forums', 'forum_order', 'ASC');
				$forum_reactions = $queries->getWhere('settings', array('name', '=', 'forum_reactions'));
				$forum_reactions = $forum_reactions[0]->value;
				
				// Form token
				$token = Token::generate();
				?>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <?php echo $forum_language->get('forum', 'forums'); ?>
				  </div>
				  <div class="panel-body">
					<?php 
					$number = count($forums);
					$i = 1;
					foreach($forums as $forum){
					?>
					<div class="row">
					  <div class="col-md-9">
						<?php echo '<a href="' . URL::build('/admin/forums/', 'forum=' . $forum->id) . '">' . Output::getPurified(htmlspecialchars_decode($forum->forum_title)) . '</a><br />' . Output::getPurified(htmlspecialchars_decode($forum->forum_description)); ?>
					  </div>
					  <div class="col-md-3">
						<span class="pull-right">
						  <?php if($i != 1){ ?><a href="<?php echo URL::build('/admin/forums/', 'action=order&dir=up&fid=' . $forum->id); ?>" class="btn btn-success btn-sm"><i class="fa fa-chevron-up" aria-hidden="true"></i></a><?php } ?>
						  <?php if($i != $number){ ?><a href="<?php echo URL::build('/admin/forums/', 'action=order&dir=down&fid=' . $forum->id);?>" class="btn btn-danger btn-sm"><i class="fa fa-chevron-down" aria-hidden="true"></i></a><?php } ?>
						  <a href="<?php echo URL::build('/admin/forums/', 'action=delete&fid=' . $forum->id);?>" class="btn btn-warning btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</span>
					  </div>
					</div>
					<?php 
						if($i != $number) echo '<hr />';
						$i++;
					}
					?>
				  </div>
				</div>
				
				<form action="" method="post">
					<div class="form-group">
					  <label for="InputEnabled"><?php echo $forum_language->get('forum', 'use_reactions'); ?></label>
					  <input type="checkbox" name="enabled" id="InputEnabled" class="js-switch"<?php if($forum_reactions == 1) echo ' checked'; ?>/>
					</div>
					<input type="hidden" name="token" value="<?php echo $token; ?>">
					<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>" />
				</form>
				<?php 
				} else if(isset($_GET['action'])){
					if($_GET['action'] == 'new'){
						// Forum creation wizard
						echo '<hr /><h4>' . $forum_language->get('forum', 'creating_forum') . '</h4>';
						
						if(!isset($_GET['step'])){
							// First step
							if(Input::exists()){
								if(Token::check(Input::get('token'))){
									// Validate input
									$validate = new Validate();
									$validation = $validate->check($_POST, array(
										'forumname' => array(
											'required' => true,
											'min' => 2,
											'max' => 150
										),
										'forumdesc' => array(
											'max' => 255
										)
									));
									
									if($validation->passed()){
										// Create the forum
										try {
											$description = Input::get('forumdesc');
											
											$last_forum_order = $queries->orderAll('forums', 'forum_order', 'DESC');
											if(count($last_forum_order)) $last_forum_order = $last_forum_order[0]->forum_order;
											else $last_forum_order = 0;
											
											$queries->create('forums', array(
												'forum_title' => htmlspecialchars(Input::get('forumname')),
												'forum_description' => htmlspecialchars($description),
												'forum_order' => $last_forum_order + 1,
												'forum_type' => Input::get('forum_type')
											));
											
											$forum_id = $queries->getLastId();
											
											Redirect::to(URL::build('/admin/forums/', 'action=new&step=2&forum=' . $forum_id));
											die();
											
										} catch(Exception $e){
											$error = '<div class="alert alert-danger">Unable to create forum: ' . $e->getMessage() . '</div>';
										}
									} else {
										$error = '<div class="alert alert-danger">';
										foreach($validation->errors() as $item) {
											  if(strpos($item, 'is required') !== false){
												switch($item){
													case (strpos($item, 'forumname') !== false):
														$error .= $forum_language->get('forum', 'input_forum_title') . '<br />';
													break;
												}
											  } else if(strpos($item, 'minimum') !== false){
												switch($item){
													case (strpos($item, 'forumname') !== false):
														$error .= $forum_language->get('forum', 'forum_name_minimum') . '<br />';
													break;
												}
											  } else if(strpos($item, 'maximum') !== false){
												switch($item){
													case (strpos($item, 'forumname') !== false):
														$error .= $admin_language->get('forum', 'forum_name_maximum') . '<br />';
													break;
													case (strpos($item, 'forumdesc') !== false):
														$error .= $admin_language->get('forum', 'forum_description_maximum') . '<br />';
													break;
												}
											  }
										}
										$error .= '</div>';
									}
								} else {
									// Invalid token
									$error = '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>';
								}
							}
							if(isset($error)) echo $error;
							?>
							<form action="" method="post">
							  <div class="form-group">
								<div class="form-group">
								  <label for="InputType"><?php echo $forum_language->get('forum', 'forum_type'); ?></label>
								  <select class="form-control" id="InputType" name="forum_type">
									<option value="forum"><?php echo $forum_language->get('forum', 'forum_type_forum'); ?></option>
									<option value="category"><?php echo $forum_language->get('forum', 'forum_type_category'); ?></option>
								  </select>
								</div>
							  </div>
							  <div class="form-group">
								<input class="form-control" type="text" name="forumname" id="forumname" value="<?php echo Output::getClean(Input::get('forumname')); ?>" placeholder="<?php echo $forum_language->get('forum', 'forum_name'); ?>" autocomplete="off">
							  </div>
							  <div class="form-group">
								<textarea name="forumdesc" placeholder="<?php echo $forum_language->get('forum', 'forum_description'); ?>" class="form-control" rows="3"><?php echo Output::getClean(Input::get('forumdesc')); ?></textarea>
							  </div>
							  <div class="form-group">
							    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
								<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
							  </div>
							</form>
						<?php
						} else {
							// Parent category, for type forum only
							if(!isset($_GET['forum']) || !is_numeric($_GET['forum'])){
								Redirect::to(URL::build('/admin/forums'));
								die();
							}
							
							// Get forum from database
							$forum = $queries->getWhere('forums', array('id', '=', $_GET['forum']));
							if(!count($forum)){
								Redirect::to(URL::build('/admin/forums'));
								die();
							} else $forum = $forum[0];
							
							switch($_GET['step']){
								case 2:
									// Forums only
									if($forum->forum_type == 'category'){
										Redirect::to(URL::build('/admin/forums/', 'action=new&step=3&forum=' . $forum->id));
										die();
									}
									
									// Deal with input
									if(Input::exists()){
										if(Token::check(Input::get('token'))){
											try {
												$queries->update('forums', $forum->id, array(
													'parent' => Input::get('parent'),
													'news' => Input::get('news_forum')
												));
												
												Redirect::to(URL::build('/admin/forums/', 'action=new&step=3&forum=' . $forum->id));
												die();
												
											} catch(Exception $e){
												$error = '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
											}
										} else {
											$error = '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>';
										}
									}
									
									// Get a list of forums
									$forums = $queries->getWhere('forums', array('id', '<>', $forum->id));
									?>
									<form action="" method="post">
									  <?php if(isset($error)) echo $error; ?>
									  <div class="form-group">
									    <label for="InputParent"><?php echo $forum_language->get('forum', 'select_a_parent_forum'); ?></label>
									    <select class="form-control" id="InputParent" name="parent">
										  <?php foreach($forums as $item){ ?>
										  <option value="<?php echo $item->id; ?>"><?php echo Output::getPurified(htmlspecialchars_decode($item->forum_title)); ?></option>
										  <?php } ?>
									    </select>
									  </div>
									  <div class="form-group">
									    <label for="InputNews"><?php echo $forum_language->get('forum', 'display_topics_as_news'); ?></label>
										<input type="hidden" name="news_forum" id="InputNews" value="0">
										<input name="news_forum" id="InputNews" type="checkbox" class="js-switch" value="1" />
									  </div>
									  <div class="form-group">
									    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
										<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
									  </div>
									</form>
									<?php
								break;
								case 3:
									// Permissions
									// Obtain list of groups and permissions
									$groups = $queries->getWhere('groups', array('id', '<>', 0));
									$group_perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $forum->id));
									
									// Deal with input
									if(Input::exists()){
										if(Token::check(Input::get('token'))){
											// Guest forum permissions
											$view = Input::get('perm-view-0');
											$create = 0;
											$post = 0;
											$view_others = 0;
											$moderate = 0;
											
											if(!($view)) $view = 0;
											
											$forum_perm_exists = 0;
											
											$forum_perm_query = $queries->getWhere('forums_permissions', array('forum_id', '=', $forum->id));
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
														'create_post' => $post,
														'view_other_topics' => $view_others,
														'moderate' => $moderate
													));
												} else { // Permission doesn't exist, create
													$queries->create('forums_permissions', array(
														'group_id' => 0,
														'forum_id' => $forum->id,
														'view' => $view,
														'create_topic' => $create,
														'create_post' => $post,
														'view_other_topics' => $view_others,
														'moderate' => $moderate
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
												$view_others = Input::get('perm-view_others-' . $group->id);
												$moderate = Input::get('perm-moderate-' . $group->id);
												
												if(!($view)) $view = 0;
												if(!($create)) $create = 0;
												if(!($post)) $post = 0;
												if(!($view_others)) $view_others = 0;
												if(!($moderate)) $moderate = 0;
												
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
															'create_post' => $post,
															'view_other_topics' => $view_others,
															'moderate' => $moderate
														));
													} else { // Permission doesn't exist, create
														$queries->create('forums_permissions', array(
															'group_id' => $group->id,
															'forum_id' => $forum->id,
															'view' => $view,
															'create_topic' => $create,
															'create_post' => $post,
															'view_other_topics' => $view_others,
															'moderate' => $moderate
														));
													}
													
												} catch(Exception $e) {
													die($e->getMessage());
												}
											}
											
											Session::flash('adm-forums', '<div class="alert alert-success">' . $forum_language->get('forum', 'forum_created_successfully') . '</div>');
											Redirect::to(URL::build('/admin/forums'));
											die();
										} else {
											$error = '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>';
										}
									}
									?>
							<script>
							var groups = [];
							groups.push("0");
							</script>
							
							<input type="hidden" name="perm-topic-0" value="0" />
							<input type="hidden" name="perm-post-0" value="0" />
							<input type="hidden" name="perm-view_others-0" value="0" />
							<input type="hidden" name="perm-moderate-0" value="0" />
							
							<form action="" method="post">
							  <strong><?php echo $forum_language->get('forum', 'forum_permissions'); ?></strong>
							  <?php if(isset($error)) echo $error; ?>
							  <table class="table table-striped">
								<thead>
								  <tr>
									<th><?php echo $forum_language->get('forum', 'group'); ?></th>
									<th><?php echo $forum_language->get('forum', 'can_view_forum'); ?></th>
									<th><?php echo $forum_language->get('forum', 'can_create_topic'); ?></th>
									<th><?php echo $forum_language->get('forum', 'can_post_reply'); ?></th>
									<th><?php echo $forum_language->get('forum', 'can_view_other_topics'); ?></th>
									<th><?php echo $forum_language->get('forum', 'can_moderate_forum'); ?></th>
								  </tr>
								</thead>
								<tbody>
								  <tr>
									<td><?php echo $language->get('user', 'guests'); ?></td>
									<td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
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
												$view_others = $group_perm->view_other_topics;
												$moderate = $group_perm->moderate;
												break;
											}
										}
								  ?>
								  <tr>
									<td onclick="toggleAll(this);"><?php echo htmlspecialchars($group->name); ?></td>
									<td><input type="hidden" name="perm-view-<?php echo $group->id; ?>" value="0" /> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
									<td><input type="hidden" name="perm-topic-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-topic-<?php echo $group->id; ?>" id="Input-topic-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($topic) && $topic == 1){ echo ' checked'; } ?>></td>
									<td><input type="hidden" name="perm-post-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-post-<?php echo $group->id; ?>" id="Input-post-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($post) && $post == 1){ echo ' checked'; } ?>></td>
									<td><input type="hidden" name="perm-view_others-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-view_others-<?php echo $group->id; ?>" id="Input-view_others-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view_others) && $view_others == 1){ echo ' checked'; } ?>></td>
									<td><input type="hidden" name="perm-moderate-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-moderate-<?php echo $group->id; ?>" id="Input-moderate-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($moderate) && $moderate == 1){ echo ' checked'; } ?>></td>
								  </tr>
								  <script>groups.push("<?php echo $group->id; ?>");</script>
								  <?php
									}
								  ?>
								</tbody>	
							  </table>
							  <div class="form-group">
							    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
								<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
							  </div>
							</form>
									<?php
								break;
								default:
									Redirect::to(URL::build('/admin/forums'));
									die();
								break;
							}
						}
					} else if($_GET['action'] == 'order'){
						if(!isset($_GET['dir']) || !isset($_GET['fid']) || !is_numeric($_GET['fid'])){
							echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/admin/forums') . '">' . $language->get('general', 'back') . '</a>';
							die();
						}
						if($_GET['dir'] == 'up' || $_GET['dir'] == 'down'){
							$dir = $_GET['dir'];
						} else {
							echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/admin/forums') . '">' . $language->get('general', 'back') . '</a>';
							die();
						}
						
						$forum_id = $queries->getWhere('forums', array('id', '=', $_GET['fid']));
						$forum_id = $forum_id[0]->id;
						
						$forum_order = $queries->getWhere('forums', array('id', '=', $_GET['fid']));
						$forum_order = $forum_order[0]->forum_order;
						
						$previous_forums = $queries->orderAll('forums', 'forum_order', 'ASC');
						
						if($dir == 'up'){
							$n = 0;
							foreach($previous_forums as $previous_forum){
								if($previous_forum->id == $_GET['fid']){
									$previous_fid = $previous_forums[$n - 1]->id;
									$previous_f_order = $previous_forums[$n - 1]->forum_order;
									break;
								}
								$n++;
							}

							try {
								$queries->update('forums', $forum_id, array(
									'forum_order' => $previous_f_order
								));	
								$queries->update('forums', $previous_fid, array(
									'forum_order' => $previous_f_order + 1
								));	
							} catch(Exception $e){
								die($e->getMessage());
							}

							Redirect::to(URL::build('/admin/forums'));
							die();

						} else if($dir == 'down'){
							$n = 0;
							foreach($previous_forums as $previous_forum){
								if($previous_forum->id == $_GET['fid']){
									$previous_fid = $previous_forums[$n + 1]->id;
									$previous_f_order = $previous_forums[$n + 1]->forum_order;
									break;
								}
								$n++;
							}
							try {
								$queries->update('forums', $forum_id, array(
									'forum_order' => $previous_f_order
								));	
								$queries->update('forums', $previous_fid, array(
									'forum_order' => $previous_f_order - 1
								));	
							} catch(Exception $e){
								die($e->getMessage());
							}
							
							Redirect::to(URL::build('/admin/forums'));
							die();
							
						}
						
					} else if($_GET['action'] == 'delete'){
						if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])){
							echo $forum_language->get('forum', 'invalid_action') . ' - <a href="' . URL::build('/admin/forums') . '">' . $language->get('general', 'back') . '</a>';
							die();
						}
						
						if(Input::exists()) {
							if(Token::check(Input::get('token'))) {
								if(Input::get('confirm') === 'true'){
									$forum_perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET['fid'])); // Get permissions to be deleted
									if(Input::get('move_forum') === 'none'){
										$posts = $queries->getWhere('posts', array('forum_id', '=', $_GET['fid']));
										$topics = $queries->getWhere('topics', array('forum_id' , '=', $_GET['fid']));
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

											Redirect::to(URL::build('/admin/forums'));
											die();
										} catch(Exception $e) {
											die($e->getMessage());
										}
									} else {
										$new_forum = Input::get('move_forum');
										$posts = $queries->getWhere('posts', array('forum_id', '=', $_GET['fid']));
										$topics = $queries->getWhere('topics', array('forum_id' , '=', $_GET['fid']));
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
											
											Redirect::to(URL::build('/admin/forums'));
											die();
										} catch(Exception $e) {
											die($e->getMessage());
										}
									}
								}
							} else {
								echo $language->get('general', 'invalid_token') . ' - <a href="' . URL::build('/admin/forums') . '">' . $language->get('general', 'back') . '</a>';
								die();
							}
						}
						?>
						<br /><br />
						<h4><?php echo $forum_language->get('forum', 'delete_forum'); ?></h4>
						<form role="form" action="" method="post">
							<strong><?php echo $forum_language->get('forum', 'move_topics_and_posts_to'); ?></strong>
							<select class="form-control" name="move_forum">
							  <option value="none" selected><?php echo $forum_language->get('forum', 'delete_topics_and_posts'); ?></option>
							  <?php 
								$forums = $queries->orderAll('forums', 'forum_order', 'ASC');
								foreach($forums as $forum){
									if($forum->id !== $_GET['fid']){
										echo '<option value="' . $forum->id . '">' . Output::getPurified(htmlspecialchars_decode($forum->forum_title)) . '</option>';
									}
								}
							  ?>
							</select>
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="hidden" name="confirm" value="true">
						  <br />
						  <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-danger">
						</form>
						<?php 
					}
				} else if(isset($_GET['forum'])){
					$available_forums = $queries->getWhere('forums', array('id', '<>', 0)); // Get a list of all forums which can be chosen as a parent
					$groups = $queries->getWhere('groups', array('id', '<>', '0')); // Get a list of all groups
					
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							if(Input::get('action') == 'update'){
								$validate = new Validate();
								$validation = $validate->check($_POST, array(
									'title' => array(
										'required' => true,
										'min' => 2,
										'max' => 150
									),
									'description' => array(
										'max' => 255
									)
								));
								
								if($validation->passed()){
									try {
										// Update the forum
										$queries->update('forums', $_GET['forum'], array(
											'forum_title' => Output::getClean(Input::get('title')),
											'forum_description' => Output::getClean(Input::get('description')),
											'news' => Input::get('display'),
											'parent' => Input::get('parent_forum')
										));
										
									} catch(Exception $e) {
										die($e->getMessage());
									}
									
									// Guest forum permissions
									$view = Input::get('perm-view-0');
									$create = 0;
									$post = 0;
									$view_others = 0;
									$moderate = 0;
									
									if(!($view)) $view = 0;
									
									$forum_perm_exists = 0;
									
									$forum_perm_query = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET['forum']));
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
												'create_post' => $post,
												'view_other_topics' => $view_others,
												'moderate' => $moderate
											));
										} else { // Permission doesn't exist, create
											$queries->create('forums_permissions', array(
												'group_id' => 0,
												'forum_id' => $_GET['forum'],
												'view' => $view,
												'create_topic' => $create,
												'create_post' => $post,
												'view_other_topics' => $view_others,
												'moderate' => $moderate
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
										$view_others = Input::get('perm-view_others-' . $group->id);
										$moderate = Input::get('perm-moderate-' . $group->id);
										
										if(!($view)) $view = 0;
										if(!($create)) $create = 0;
										if(!($post)) $post = 0;
										if(!($view_others)) $view_others = 0;
										if(!($moderate)) $moderate = 0;
										
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
													'create_post' => $post,
													'view_other_topics' => $view_others,
													'moderate' => $moderate
												));
											} else { // Permission doesn't exist, create
												$queries->create('forums_permissions', array(
													'group_id' => $group->id,
													'forum_id' => $_GET['forum'],
													'view' => $view,
													'create_topic' => $create,
													'create_post' => $post,
													'view_other_topics' => $view_others,
													'moderate' => $moderate
												));
											}
											
										} catch(Exception $e) {
											die($e->getMessage());
										}
									}
									
									Redirect::to(URL::build('/admin/forums'));
									die();
									
								} else {
									echo '<div class="alert alert-danger">';
									foreach($validation->errors() as $error) {
									  if(strpos($error, 'is required') !== false){
										switch($error){
											case (strpos($error, 'title') !== false):
												echo $forum_language->get('forum', 'input_forum_title') . '<br />';
											break;
										}
									  } else if(strpos($error, 'minimum') !== false){
										switch($error){
											case (strpos($error, 'title') !== false):
												echo $forum_language->get('forum', 'forum_name_minimum') . '<br />';
											break;
										}
									  } else if(strpos($error, 'maximum') !== false){
										switch($error){
											case (strpos($error, 'title') !== false):
												echo $forum_language->get('forum', 'forum_name_maximum') . '<br />';
											break;
											case (strpos($error, 'description') !== false):
												echo $forum_language->get('forum', 'forum_description_maximum') . '<br />';
											break;
										}
									  }
									}
									echo '</div>';
								}
							}
						} else {
							echo $language->get('general', 'invalid_token') . ' - <a href="' . URL::build('/admin/forums') . '">' . $language->get('general', 'back') . '</a>';
							die();
						}
					}
					
					// Form token
					$token = Token::generate();
					
					if(!is_numeric($_GET['forum'])){
						die();
					} else {
						$forum = $queries->getWhere('forums', array('id', '=', $_GET['forum']));
					}
					if(count($forum)){
						echo '<hr /><h4 style="display: inline;">' . Output::getClean($forum[0]->forum_title) . '</h2>';
						?>
						<br /><br />
						<form role="form" action="" method="post">
						  <div class="form-group">
							<label for="InputTitle"><?php echo $forum_language->get('forum', 'forum_name'); ?></label>
							<input type="text" name="title" class="form-control" id="InputTitle" placeholder="<?php echo $forum_language->get('forum', 'forum_name'); ?>" value="<?php echo Output::getPurified(htmlspecialchars_decode($forum[0]->forum_title)); ?>">
						  </div>
						  <div class="form-group">
							<label for="InputDescription"><?php echo $forum_language->get('forum', 'forum_description'); ?></label>
							<textarea name="description" id="InputDescription" placeholder="<?php echo $forum_language->get('forum', 'forum_description'); ?>" class="form-control" rows="3"><?php echo Output::getPurified(htmlspecialchars_decode($forum[0]->forum_description)); ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="InputParentForum"><?php echo $forum_language->get('forum', 'parent_forum'); ?></label>
							<select class="form-control" id="InputParentForum" name="parent_forum">
							  <option value="0" <?php if($forum[0]->parent == 0){ echo ' selected="selected"'; } ?>><?php echo $forum_language->get('forum', 'has_no_parent'); ?></option>
							  <?php
								foreach($available_forums as $available_forum){
								  if($available_forum->id !== $forum[0]->id){
								?>
							  <option value="<?php echo $available_forum->id; ?>" <?php if($available_forum->id == $forum[0]->parent){ ?> selected="selected"<?php } ?>><?php echo Output::getPurified(htmlspecialchars_decode($available_forum->forum_title)); ?></option>
								<?php 
								  }
								}
							  ?>
							</select>
						  </div>
						  <div class="form-group">
						    <input type="hidden" name="display" value="0" />
						    <label for="InputDisplay"><?php echo $forum_language->get('forum', 'display_topics_as_news'); ?></label>
						    <input name="display" id="InputDisplay" value="1" class="js-switch" type="checkbox"<?php if($forum[0]->news == 1){ echo ' checked'; } ?>>
						  </div>
						  <div class="form-group">
							<strong><?php echo $forum_language->get('forum', 'forum_permissions'); ?></strong><br />
							<script>
							var groups = [];
							groups.push("0");
							</script>
							<?php
							// Get all forum permissions
							$group_perms = $queries->getWhere('forums_permissions', array('forum_id', '=', $_GET['forum']));
	
							foreach($group_perms as $group_perm){
								if($group_perm->group_id == 0){
									$view = $group_perm->view;
									break;
								}
							}
							?>
							<table class="table table-striped">
							  <thead>
								<tr>
								  <th><?php echo $forum_language->get('forum', 'group'); ?></th>
								  <th><?php echo $forum_language->get('forum', 'can_view_forum'); ?></th>
								  <th><?php echo $forum_language->get('forum', 'can_create_topic'); ?></th>
								  <th><?php echo $forum_language->get('forum', 'can_post_reply'); ?></th>
								  <th><?php echo $forum_language->get('forum', 'can_view_other_topics'); ?></th>
								  <th><?php echo $forum_language->get('forum', 'can_moderate_forum'); ?></th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
								  <td><?php echo $language->get('user', 'guests'); ?></td>
								  <td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
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
											$view_others = $group_perm->view_other_topics;
											$moderate = $group_perm->moderate;
											break;
										}
									}
								?>
								<tr>
								  <td onclick="toggleAll(this);"><?php echo htmlspecialchars($group->name); ?></td>
								  <td><input type="hidden" name="perm-view-<?php echo $group->id; ?>" value="0" /> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
								  <td><input type="hidden" name="perm-topic-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-topic-<?php echo $group->id; ?>" id="Input-topic-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($topic) && $topic == 1){ echo ' checked'; } ?>></td>
								  <td><input type="hidden" name="perm-post-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-post-<?php echo $group->id; ?>" id="Input-post-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($post) && $post == 1){ echo ' checked'; } ?>></td>
								  <td><input type="hidden" name="perm-view_others-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-view_others-<?php echo $group->id; ?>" id="Input-view_others-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view_others) && $view_others == 1){ echo ' checked'; } ?>></td>
								  <td><input type="hidden" name="perm-moderate-<?php echo $group->id; ?>" value="0" /><input onclick="colourUpdate(this);" name="perm-moderate-<?php echo $group->id; ?>" id="Input-moderate-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($moderate) && $moderate == 1){ echo ' checked'; } ?>></td>
								</tr>
								<script>groups.push("<?php echo $group->id; ?>");</script>
								<?php
								}
								?>
							  </tbody>	
							</table>
						  </div>
						  <div class="form-group">
						    <input type="hidden" name="token" value="<?php echo $token; ?>">
						    <input type="hidden" name="action" value="update">
						    <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-primary">
						  </div>
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
					  <span class="pull-right"><a href="<?php echo URL::build('/admin/forums/', 'view=labels&action=create'); ?>" class="btn btn-primary"><?php echo $language->get('new_label'); ?></a></span>
					  <br /><br />
					  <?php
					  if(Session::exists('forum_labels')){
						echo Session::flash('forum_labels');
					  }
					  if(count($topic_labels)){
						?>
					  <div class="panel panel-default">
						<div class="panel-heading"><?php echo $language->get('labels'); ?></div>
						<div class="panel-body">
						<?php
						// Display list of all labels
						foreach($topic_labels as $topic_label){
						?>
						<h4 style="display:inline;"><span class="label label-<?php echo Output::getClean($topic_label->label); ?>"><?php echo Output::getClean($topic_label->name); ?></span></h4>
						<span class="pull-right">
						  <a href="<?php echo URL::build('/admin/forums/', 'view=labels&amp;action=edit&amp;lid=' . $topic_label->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						  <a onclick="return confirm('<?php echo $language->get('confirm_deletion'); ?>');" href="<?php echo URL::build('/admin/forums/', 'view=labels&action=delete&lid=' . $topic_label->id); ?>" class="btn btn-warning btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</span>
						<br /><br />
						<?php
						// Display list of forums the label is enabled in
						$enabled_forums = explode(',', $topic_label->fids);
						$forums_string = '';
						foreach($enabled_forums as $item){
							$forum_name = $queries->getWhere('forums', array('id', '=', $item));
							if(count($forum_name)) $forums_string .= Output::getClean($forum_name[0]->forum_title) . ', '; else $forums_string .= $language->get('no_forums');
						}
						echo rtrim($forums_string, ', ');
						?>
						<hr />
						<?php
						}
					  ?>
						</div>
					  </div>
					  <?php
					  } else {
						// No labels defined yet
						echo '<div class="alert alert-warning">' . $language->get('no_labels_defined') . '</div>'; 
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
										
										Session::flash('forum_labels', '<div class="alert alert-success">' . $language->get('label_creation_success') . '</div>');
										Redirect::to(URL::build('/admin/forums/', 'view=labels'));
										die();
									} catch(Exception $e){
										die($e->getMessage());
									}
									
								} else {
									// Validation errors
									Session::flash('new_label_error', '<div class="alert alert-danger">' . $language->get('label_creation_error') . '</div>');
								}
								
							} else {
								// Invalid token
								Session::flash('new_label_error', '<div class="alert alert-danger">' . $language->get('invalid_token') . '</div>');
							}
						}
						?>
					    <br /><br />
						<h4><?php echo $language->get('creating_label'); ?></h4>
						<?php
						if(Session::exists('new_label_error')){
							echo Session::flash('new_label_error');
						}
						?>
						<form action="" method="post">
						  <div class="form-group">
							<label for="label_name"><?php echo $language->get('label_name'); ?></label>
							<input type="text" name="label_name" placeholder="<?php echo $language->get('label_name'); ?>" id="label_name" class="form-control">
						  </div>
						  <div class="form-group">
							<label for="label_type"><?php echo $language->get('label_type'); ?></label><br />
							<div class="row">
							  <div class="col-md-2">
								<input type="radio" name="label_type" id="label_type" value="default"> <span class="label label-default"><?php echo $language->get('label'); ?></span><br />
							  </div>
							  <div class="col-md-2">
								<input type="radio" name="label_type" id="label_type" value="primary"> <span class="label label-primary"><?php echo $language->get('label'); ?></span><br />
							  </div>
							  <div class="col-md-2">
								<input type="radio" name="label_type" id="label_type" value="success"> <span class="label label-success"><?php echo $language->get('label'); ?></span><br />
							  </div>
							  <div class="col-md-2">
								<input type="radio" name="label_type" id="label_type" value="info"> <span class="label label-info"><?php echo $language->get('label'); ?></span><br />
							  </div>
							  <div class="col-md-2">
								<input type="radio" name="label_type" id="label_type" value="warning"> <span class="label label-warning"><?php echo $language->get('label'); ?></span><br />
							  </div>
							  <div class="col-md-2">
								<input type="radio" name="label_type" id="label_type" value="danger"> <span class="label label-danger"><?php echo $language->get('label'); ?></span><br />
							  </div>
							</div>
						  </div>
						  <div class="form-group">
							<label for="label_forums"><?php echo $language->get('label_forums'); ?></label>
							<select name="label_forums[]" id="label_forums" size="5" class="form-control" multiple>
							  <?php 
							  $forum_list = $queries->getWhere('forums', array('parent', '<>', 0)); 
							  foreach($forum_list as $item){
							  ?>
							  <option value="<?php echo $item->id; ?>"><?php echo Output::getClean($item->forum_title); ?></option>
							  <?php
							  }
							  ?>
							</select>
						  </div>
						  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('submit'); ?>">
						  <a href="<?php echo URL::build('/admin/forums', 'view=labels'); ?>" class="btn btn-danger" onclick="return confirm('<?php echo $language->get('confirm_cancel'); ?>');"><?php echo $language->get('cancel'); ?></a>
						</form>
						<?php
					} else if(isset($_GET['action']) && $_GET['action'] == 'edit'){
						// Editing a label
						if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
							// Check the label ID is valid
							Redirect::to(URL::build('/admin/forums/', 'view=labels'));
							die();
						}
						
						// Does the label exist?
						$label = $queries->getWhere('forums_topic_labels', array('id', '=', $_GET['lid']));
						if(!count($label)){
							// No, it doesn't exist
							Redirect::to(URL::build('/admin/forums', 'view=labels'));
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
											'name' => Output::getClean(Input::get('label_name')),
											'label' => Output::getClean(Input::get('label_type'))
										));
										
										Session::flash('forum_labels', '<div class="alert alert-info">' . $language->get('label_edit_success') . '</div>');
										Redirect::to(URL::build('/admin/forums', 'view=labels'));
										die();
									} catch(Exception $e){
										die($e->getMessage());
									}
									
								} else {
									// Validation errors
									Session::flash('editing_label_error', '<div class="alert alert-danger">' . $language->get('label_creation_error') . '</div>');
								}
								
							} else {
								// Invalid token
								Session::flash('editing_label_error', '<div class="alert alert-danger">' . $language->get('invalid_token') . '</div>');
							}
						}
						?>
						<br /><br />
						<h4><?php echo $language->get('editing_label'); ?></h4>
						<?php
						if(Session::exists('editing_label_error')){
							echo Session::flash('editing_label_error');
						}
						?>
						  <form action="" method="post">
							<div class="form-group">
							  <label for="label_name"><?php echo $language->get('label_name'); ?></label>
							  <input type="text" name="label_name" placeholder="<?php echo $language->get('label_name'); ?>" id="label_name" value="<?php echo Output::getClean($label->name); ?>" class="form-control">
							</div>
							<div class="form-group">
							  <label for="label_type"><?php echo $language->get('label_type'); ?></label><br />
							  <div class="row">
								<div class="col-md-2">
								  <input type="radio" name="label_type" id="label_type" value="default"<?php if($label->label == 'default'){ ?> checked<?php } ?>> <span class="label label-default"><?php echo $language->get('label'); ?></span><br />
								</div>
								<div class="col-md-2">
								  <input type="radio" name="label_type" id="label_type" value="primary"<?php if($label->label == 'primary'){ ?> checked<?php } ?>> <span class="label label-primary"><?php echo $language->get('label'); ?></span><br />
								</div>
								<div class="col-md-2">
								  <input type="radio" name="label_type" id="label_type" value="success"<?php if($label->label == 'success'){ ?> checked<?php } ?>> <span class="label label-success"><?php echo $language->get('label'); ?></span><br />
								</div>
								<div class="col-md-2">
								  <input type="radio" name="label_type" id="label_type" value="info"<?php if($label->label == 'info'){ ?> checked<?php } ?>> <span class="label label-info"><?php echo $language->get('label'); ?></span><br />
								</div>
								<div class="col-md-2">
								  <input type="radio" name="label_type" id="label_type" value="warning"<?php if($label->label == 'warning'){ ?> checked<?php } ?>> <span class="label label-warning"><?php echo $language->get('label'); ?></span><br />
								</div>
								<div class="col-md-2">
								  <input type="radio" name="label_type" id="label_type" value="danger"<?php if($label->label == 'danger'){ ?> checked<?php } ?>> <span class="label label-danger"><?php echo $language->get('label'); ?></span><br />
								</div>
							  </div>
							</div>
							<div class="form-group">
							  <label for="label_forums"><?php echo $language->get('label_forums'); ?></label>
							  <select name="label_forums[]" id="label_forums" size="5" class="form-control" multiple>
								<?php 
								// Get a list of forums in which the label is enabled
								$enabled_forums = explode(',', $label->fids);
								
								// Get a list of all forums
								$forum_list = $queries->getWhere('forums', array('parent', '<>', 0)); 
								foreach($forum_list as $item){
								?>
								<option value="<?php echo $item->id; ?>"<?php if(in_array($item->id, $enabled_forums)){ ?> selected<?php } ?>><?php echo Output::getClean($item->forum_title); ?></option>
								<?php
								}
								?>
							  </select>
							</div>
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<input type="submit" class="btn btn-primary" value="<?php echo $language->get('submit'); ?>">
							<a class="btn btn-danger" href="<?php echo URL::build('/admin/forums/', 'view=labels'); ?>" onclick="return confirm('<?php echo $language->get('confirm_cancel'); ?>');"><?php echo $language->get('cancel'); ?></a>
						  </form>
						<?php
					} else if(isset($_GET['action']) && $_GET['action'] == 'delete'){
						// Label deletion
						if(!isset($_GET['lid']) || !is_numeric($_GET['lid'])){
							// Check the label ID is valid
							Redirect::to(URL::build('/admin/forums/', 'view=labels'));
							die();
						}
						try {
							// Delete the label
							$queries->delete('forums_topic_labels', array('id', '=', $_GET['lid']));
							Redirect::to(URL::build('/admin/forums/', 'view=labels'));
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
      </div>
    </div>
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
	
    <script type="text/javascript">
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
		var switchery = new Switchery(html);
	});
	
  	function colourUpdate(that) {
    	var x = that.parentElement;
    	if(that.checked) {
    		x.className = "bg-success";
    	} else {
    		x.className = "bg-danger";
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
		if(document.getElementById('Input-view_others-' + group).checked) {
			document.getElementById('Input-view_others-' + group).checked = false;
		} else {
			document.getElementById('Input-view_others-' + group).checked = true;
		}
		if(document.getElementById('Input-moderate-' + group).checked) {
			document.getElementById('Input-moderate-' + group).checked = false;
		} else {
			document.getElementById('Input-moderate-' + group).checked = true;
		}

		colourUpdate(document.getElementById('Input-view-' + group));
		colourUpdate(document.getElementById('Input-topic-' + group));
		colourUpdate(document.getElementById('Input-post-' + group));
		colourUpdate(document.getElementById('Input-view_others-' + group));
		colourUpdate(document.getElementById('Input-moderate-' + group));
	}
	for(var g in groups) {
		colourUpdate(document.getElementById('Input-view-' + groups[g]));
		if(groups[g] != "0") {
			colourUpdate(document.getElementById('Input-topic-' + groups[g]));
			colourUpdate(document.getElementById('Input-post-' + groups[g]));
			colourUpdate(document.getElementById('Input-view_others-' + groups[g]));
			colourUpdate(document.getElementById('Input-moderate-' + groups[g]));
		}
	}
	
	// Toggle all columns in row
	function toggleAll(that){
		var first = (($(that).parents('tr').find(':checkbox').first().is(':checked') == true) ? false : true);
		$(that).parents('tr').find(':checkbox').each(function(){
			$(this).prop('checked', first);
			colourUpdate(this);
		});
	}
    </script>
  </body>
</html>