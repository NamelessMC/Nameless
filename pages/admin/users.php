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
 
// Set page name for sidebar
$adm_page = "users";

// Custom usernames?
$displaynames = $queries->getWhere("settings", array("name", "=", "displaynames"));
$displaynames = $displaynames[0]->value;

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

require('core/includes/password.php'); // Password compat library
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier
require('core/integration/uuid.php');

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
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $admin_language['users'];
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>

  <body>
	<?php
	// "Users" page
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	  
	echo '<br />';

	if(Session::exists('adm-alert')){
		echo Session::flash('adm-alert');
	}
	?>
    <div class="container">
	  <div class="row">
		<div class="col-md-3">
		  <?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <ul class="nav nav-pills">
			<li class="active"><a href="/admin/users"><?php echo $admin_language['users']; ?></a></li>
			<li><a href="/admin/groups"><?php echo $admin_language['groups']; ?></a></li>
		  </ul>
		  
		  <hr>
		  
		  <div class="well well-sm">
			<?php
			if(Session::exists('adm-users')){
				echo Session::flash('adm-users');
			}
			if(!isset($_GET["action"]) && !isset($_GET["user"])){
				if(isset($_GET['p'])){
					if(!is_numeric($_GET['p'])){
						echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
						die();
					} else {
						if($_GET['p'] == 1){ 
							// Avoid bug in pagination class
							echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
							die();
						}
						$p = $_GET['p'];
					}
				} else {
					$p = 1;
				}
				
				$users = $queries->orderAll("users", "USERNAME", "ASC");
				$groups = $queries->getAll("groups", array("id", "<>", 0));
			?>
			<a href="/admin/users/?action=new" class="btn btn-default"><?php echo $admin_language['new_user']; ?></a>
			<br /><br />
			<table class="table table-striped table-bordered table-hover dataTables-users" >
			  <thead>
				<tr>
				  <th><?php echo $user_language['username']; ?></th>
				  <th><?php echo $admin_language['group']; ?></th>
				  <th><?php echo $user_language['email']; ?></th>
				  <th><?php echo $admin_language['created']; ?></th>
				  <th><?php echo $admin_language['edit']; ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php
				foreach($users as $individual){
					$user_group = "";
					foreach($groups as $group){
						if($group->id === $individual->group_id){
							$user_group = $group->name;
							break;
						}
					}
				?>
				<tr>
				  <td><?php echo htmlspecialchars($individual->username); ?></td>
				  <td><?php echo htmlspecialchars($user_group); ?></td>
				  <td><?php echo htmlspecialchars($individual->email); ?></td>
				  <td><?php echo date('d M Y', $individual->joined); ?></td>
				  <td><a href="/admin/users/?user=<?php echo $individual->id; ?>" class="btn btn-primary btn-sm"><?php echo $admin_language['edit']; ?></a></td>
				</tr>
				<?php
				}
				?>
			  </tbody>
			</table>
			<?php 
			} else if(isset($_GET["action"]) && $_GET['action'] !== 'validate'){
				if($_GET["action"] === "new"){
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							$validate = new Validate();
							
							$to_validation = array(
								'password' => array(
									'required' => true,
									'min' => 6,
									'max' => 30
								),
								'password_again' => array(
									'required' => true,
									'matches' => 'password'
								),
								'email' => array(
									'required' => true,
									'min' => 4,
									'max' => 50
								),
								'group' => array(
									'required' => true
								)
							);
							
							if($uuid_linking == '1'){
								if($displaynames == "true"){
									$to_validation['mcname'] = array(
										'required' => true,
										'min' => 3,
										'max' => 20
									);
									$to_validation['username'] = array(
										'required' => true,
										'min' => 3,
										'max' => 20,
										'unique' => 'users'
									);
									$mcname = htmlspecialchars(Input::get('mcname'));
								} else {
									$to_validation['username'] = array(
										'required' => true,
										'min' => 3,
										'max' => 20,
										'unique' => 'users'
									);
									$mcname = htmlspecialchars(Input::get('username'));
								}
								
								// Get UUID
								$profile = ProfileUtils::getProfile($mcname);

								if(!empty($profile)){
									$result = $profile->getProfileAsArray();
									if(isset($result['uuid']) && !empty($result['uuid'])){
										$uuid = $result['uuid'];
									} else $uuid = 'Unknown';
									
								} else $uuid = 'Unknown';
								
							} else {
								if($displaynames == "true"){
									$to_validation['mcname'] = array(
										'required' => true,
										'min' => 3,
										'max' => 20
									);
									$to_validation['username'] = array(
										'required' => true,
										'min' => 3,
										'max' => 20,
										'unique' => 'users'
									);
									$mcname = htmlspecialchars(Input::get('mcname'));
								} else {
									$to_validation['username'] = array(
										'required' => true,
										'min' => 3,
										'max' => 20,
										'unique' => 'users'
									);
									$mcname = htmlspecialchars(Input::get('username'));
								}
							}
							
							$validation = $validate->check($_POST, $to_validation);
							
							if($validation->passed()){
								$user = new User();
								
								$password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));
								
								// Get current unix time
								$date = new DateTime();
								$date = $date->getTimestamp();
								
								try {
									$user->create(array(
										'username' => htmlspecialchars(Input::get('username')),
										'mcname' => $mcname,
										'uuid' => $uuid,
										'password' => $password,
										'pass_method' => 'default',
										'joined' => $date,
										'group_id' => Input::get('group'),
										'email' => htmlspecialchars(Input::get('email')),
										'active' => 1,
										'lastip' => 'none'
									));
									echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
									die();
								} catch(Exception $e){
									die($e->getMessage());
								}
							}
						}
					}
					if(isset($validation)){
						if(!$validation->passed()){
					?>
					<div class="alert alert-danger">
						<?php
						foreach($validation->errors() as $error) {
							if(strpos($error, 'is required') !== false){
								// x is required
								switch($error){
									case (strpos($error, 'username') !== false):
										echo $user_language['username_required'] . '<br />';
									break;
									case (strpos($error, 'email') !== false):
										echo $user_language['email_required'] . '<br />';
									break;
									case (strpos($error, 'password') !== false):
										echo $user_language['password_required'] . '<br />';
									break;
									case (strpos($error, 'mcname') !== false):
										echo $user_language['mcname_required'] . '<br />';
									break;
									case (strpos($error, 'group') !== false):
										echo $admin_language['select_user_group'] . '<br />';
									break;
								}
								
							} else if(strpos($error, 'minimum') !== false){
								// x must be a minimum of y characters long
								switch($error){
									case (strpos($error, 'username') !== false):
										echo $user_language['username_minimum_3'] . '<br />';
									break;
									case (strpos($error, 'mcname') !== false):
										echo $user_language['mcname_minimum_3'] . '<br />';
									break;
									case (strpos($error, 'password') !== false):
										echo $user_language['password_minimum_6'] . '<br />';
									break;
								}
								
							} else if(strpos($error, 'maximum') !== false){
								// x must be a maximum of y characters long
								switch($error){
									case (strpos($error, 'username') !== false):
										echo $user_language['username_maximum_20'] . '<br />';
									break;
									case (strpos($error, 'mcname') !== false):
										echo $user_language['mcname_maximum_20'] . '<br />';
									break;
									case (strpos($error, 'password') !== false):
										echo $user_language['password_maximum_30'] . '<br />';
									break;
								}
								
							} else if(strpos($error, 'must match') !== false){
								// password must match password again
								echo $user_language['passwords_dont_match'] . '<br />';
								
							} else if(strpos($error, 'already exists') !== false){
								// already exists
								echo $user_language['username_mcname_email_exists'] . '<br />';
							} else if(strpos($error, 'not a valid Minecraft account') !== false){
								// Invalid Minecraft username
								echo $user_language['invalid_mcname'] . '<br />';
								
							} else if(strpos($error, 'Mojang communication error') !== false){
								// Mojang server error
								echo $user_language['mcname_lookup_error'] . '<br />';
								
							}
						}
						?>
					</div>
					<?php 
						}
					}
					?>
					<form action="" method="post">
						<h2><?php echo $user_language['create_an_account']; ?></h2>
						<div class="form-group">
							<input class="form-control" type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" placeholder="<?php if($displaynames == "false"){ echo $user_language['minecraft_username']; } else { echo $user_language['username']; } ?>" autocomplete="off">
						</div>
						<?php
						if($displaynames == "true"){
						?>
						<div class="form-group">
							<input class="form-control" type="text" name="mcname" id="mcname" value="<?php echo escape(Input::get('mcname')); ?>" placeholder="<?php echo $user_language['minecraft_username']; ?>" autocomplete="off">
						</div>
						<?php
						}
						?>
						<div class="form-group">
							<input class="form-control" type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" placeholder="<?php echo $user_language['email']; ?>">
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="password" id="password" placeholder="<?php echo $user_language['password']; ?>">
						</div>
						<input class="form-control" type="password" name="password_again" id="password_again" placeholder="<?php echo $user_language['confirm_password']; ?>">	
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>"><br />
						<strong><?php echo $admin_language['group']; ?></strong>
						<select name="group" id="group" size="5" class="form-control">
						  <?php
							$groups = $queries->orderAll('groups', 'name', 'ASC'); 
							$n = 0;
							while ($n < count($groups)){
								$result = (array)$groups[$n];
								echo '<option value="' . $result["id"] . '">' . $result["name"] . '</option>';
								$n++;
							}
						  ?>
						</select>
						<br />
						<input class="btn btn-success" type="submit" value="<?php echo $general_language['submit']; ?>">	
					</form>
					<?php 
				// Delete a user
				} else if($_GET["action"] == 'delete'){
					// Check for a valid UID
					if(!isset($_GET["uid"]) || !is_numeric($_GET["uid"])){
						// Invalid, redirect
						echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
						die();
					} else {
						// Can't delete first user
						if($_GET['uid'] == 1){
							Session::flash('adm-users', '<div class="alert alert-info alert-danger">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['cant_delete_root_user'] . '</div>');
							echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
							die();
						}
						
						// Valid, has the admin confirmed deletion?
						if(isset($_GET["confirm"])){
							// Delete the user
							$queries->delete('users', array('id', '=', $_GET["uid"]));
							
							// Delete the user's posts
							$queries->delete('posts', array('post_creator', '=', $_GET["uid"]));
							
							// Delete the user's topics
							$queries->delete('topics', array('topic_creator', '=', $_GET["uid"]));
							
							// Delete user's friends
							$queries->delete('friends', array('user_id', '=', $_GET["uid"]));
							$queries->delete('friends', array('friend_id', '=', $_GET["uid"]));
							
							Session::flash('adm-users', '<div class="alert alert-info alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['user_deleted'] . '</div>');
							echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
							die();
						} else {
							// Confirm
							$individual = $queries->getWhere("users", array("id", "=", $_GET["uid"]));
							if(count($individual)){
							?>
				<p><?php echo str_replace('{x}', htmlspecialchars($individual[0]->username), $admin_language['confirm_user_deletion']); ?></p>
				<div class="btn-group" role="group" aria-label="...">
				  <a href="/admin/users/?action=delete&uid=<?php echo $_GET["uid"]; ?>&confirm=true/" class="btn btn-danger"><?php echo $general_language['confirm']; ?></a>
				  <a href="/admin/users/?user=<?php echo $individual[0]->id; ?>" class="btn btn-default"><?php echo $general_language['cancel']; ?></a>
				</div>
							<?php
							} else {
								// No user exists with that ID
								echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
								die();
							}
						}
					}
				}
			} else if(isset($_GET["user"])){
				if(isset($_GET['action']) && $_GET['action'] == 'validate'){
					$individual = $queries->getWhere("users", array("id", "=", $_GET["user"]));
					if($individual[0]->active == 0){
						// activate user
						$queries->update('users', $_GET['user'], array(
							'active' => 1
						));
						echo '<script data-cfasync="false">window.location.replace(\'/admin/users/?user=' . $_GET['user'] . '\')</script>';
						die();
					} else {
						// already active
						echo '<script data-cfasync="false">window.location.replace(\'/admin/users/?user=' . $_GET['user'] . '\')</script>';
						die();
					}
				} else {
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							if(Input::get('action') === "update"){
								
								$signature = Input::get('signature');
								$_POST['signature'] = strip_tags(Input::get('signature'));
								
								$validate = new Validate();
								
								$to_validation = array(
									'email' => array(
										'required' => true,
										'min' => 4,
										'max' => 50
									),
									'group' => array(
										'required' => true
									),
									'UUID' => array(
										'max' => 32
									),
									'title' => array(
										'max' => 64
									),
									'signature' => array(
										'max' => 900
									),
									'ip' => array(
										'max' => 256
									)
								);
								
								if($uuid_linking == '1'){
									if($displaynames == "true"){
										$to_validation['MCUsername'] = array(
											'required' => true,
											'min' => 3,
											'max' => 20
										);
										$to_validation['username'] = array(
											'required' => true,
											'min' => 3,
											'max' => 20
										);
										$mcname = htmlspecialchars(Input::get('MCUsername'));
									} else {
										$to_validation['username'] = array(
											'required' => true,
											'min' => 3,
											'max' => 20
										);
										$mcname = htmlspecialchars(Input::get('username'));
									}
								} else {
									if($displaynames == "true"){
										$to_validation['MCUsername'] = array(
											'required' => true,
											'min' => 3,
											'max' => 20
										);
										$to_validation['username'] = array(
											'required' => true,
											'min' => 3,
											'max' => 20
										);
										$mcname = htmlspecialchars(Input::get('MCUsername'));
									} else {
										$to_validation['username'] = array(
											'required' => true,
											'min' => 3,
											'max' => 20
										);
										$mcname = htmlspecialchars(Input::get('username'));
									}
								}
								
								$validation = $validate->check($_POST, $to_validation);
								
								if($validation->passed()){
								  if(isset($_POST['group2']) && $_POST['group2'] > 0)
								    $group2 = $_POST['group2'];
								  else
								    $group2 = null;

									try {
										$queries->update('users', $_GET["user"], array(
											'username' => htmlspecialchars(Input::get('username')),
											'email' => htmlspecialchars(Input::get('email')),
											'group_id' => Input::get('group'),
											'group2_id' => $group2,
											'mcname' => $mcname,
											'uuid' => htmlspecialchars(Input::get('UUID')),
											'user_title' => Input::get('title'),
											'signature' => htmlspecialchars($signature),
											'lastip' => Input::get('ip')
										));
										echo '<script data-cfasync="false">window.location.replace("/admin/users/?user=' . $_GET['user'] . '");</script>';
										die();
									} catch(Exception $e) {
										die($e->getMessage());
									}
									
								} else {
									echo '<div class="alert alert-danger">';
									foreach($validation->errors() as $error) {
										if(strpos($error, 'is required') !== false){
											// x is required
											switch($error){
												case (strpos($error, 'username') !== false):
													echo $user_language['username_required'] . '<br />';
												break;
												case (strpos($error, 'email') !== false):
													echo $user_language['email_required'] . '<br />';
												break;
												case (strpos($error, 'password') !== false):
													echo $user_language['password_required'] . '<br />';
												break;
												case (strpos($error, 'MCUsername') !== false):
													echo $user_language['mcname_required'] . '<br />';
												break;
												case (strpos($error, 'group') !== false):
													echo $admin_language['select_user_group'] . '<br />';
												break;
											}
											
										} else if(strpos($error, 'minimum') !== false){
											// x must be a minimum of y characters long
											switch($error){
												case (strpos($error, 'username') !== false):
													echo $user_language['username_minimum_3'] . '<br />';
												break;
												case (strpos($error, 'MCUsername') !== false):
													echo $user_language['mcname_minimum_3'] . '<br />';
												break;
												case (strpos($error, 'password') !== false):
													echo $user_language['password_minimum_6'] . '<br />';
												break;
											}
											
										} else if(strpos($error, 'maximum') !== false){
											// x must be a maximum of y characters long
											switch($error){
												case (strpos($error, 'username') !== false):
													echo $user_language['username_maximum_20'] . '<br />';
												break;
												case (strpos($error, 'MCUsername') !== false):
													echo $user_language['mcname_maximum_20'] . '<br />';
												break;
												case (strpos($error, 'password') !== false):
													echo $user_language['password_maximum_30'] . '<br />';
												break;
												case (strpos($error, 'UUID') !== false):
													echo $user_language['uuid_max_32'] . '<br />';
												break;
											}
											
										} else if(strpos($error, 'must match') !== false){
											// password must match password again
											echo $user_language['passwords_dont_match'] . '<br />';
											
										} else if(strpos($error, 'already exists') !== false){
											// already exists
											echo $user_language['username_mcname_email_exists'] . '<br />';
										} else if(strpos($error, 'not a valid Minecraft account') !== false){
											// Invalid Minecraft username
											echo $user_language['invalid_mcname'] . '<br />';
											
										} else if(strpos($error, 'Mojang communication error') !== false){
											// Mojang server error
											echo $user_language['mcname_lookup_error'] . '<br />';
											
										}
									}
									echo '</div>';
								}
							} else if(Input::get('action') == "delete"){
								try {
									$queries->delete('users', array('id', '=' , $data[0]->id));
									
								} catch(Exception $e) {
									die($e->getMessage());
								}
								echo '<script data-cfasync="false">window.location.replace("/admin/users/");</script>';
								die();
							} else if(Input::get('action') == "avatar_disable"){
								try {
									$queries->update('users', $_GET["user"], array(
										"has_avatar" => "0"
									));
								} catch(Exception $e) {
									die($e->getMessage());
								}
							} else if(Input::get('action') == "avatar_enable"){ 
								try {
									$queries->update('users', $_GET["user"], array(
										"has_avatar" => "1"
									));
								} catch(Exception $e) {
									die($e->getMessage());
								}
							}
						}
					}
					if(!is_numeric($_GET["user"])){
						$individual = $queries->getWhere("users", array("username", "=", $_GET["user"]));
					} else {
						$individual = $queries->getWhere("users", array("id", "=", $_GET["user"]));
					}
					if(count($individual)){
						$token = Token::generate();
						
						// Initialise HTML Purifier
						$config = HTMLPurifier_Config::createDefault();
						$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
						$config->set('URI.DisableExternalResources', false);
						$config->set('URI.DisableResources', false);
						$config->set('HTML.Allowed', 'u,p,b,a,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
						$config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
						$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
						$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
						$purifier = new HTMLPurifier($config);
						
						$signature = $purifier->purify(htmlspecialchars_decode($individual[0]->signature));
						
						echo '<h2 style="display: inline;">' . htmlspecialchars($individual[0]->username) . '</h2>';
						?>
						<span class="pull-right">
							<?php if($individual[0]->active == 0){ ?>
							<a href="/admin/users/?user=<?php echo $individual[0]->id; ?>&amp;action=validate" class="btn btn-primary"><?php echo $admin_language['validate_user']; ?></a>
							<?php } ?>
							<div class="btn-group">
							  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<?php echo $admin_language['actions']; ?> <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <?php
								// Is UUID linking enabled?
								$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
								$uuid_linking = $uuid_linking[0]->value;
								if($uuid_linking == '1'){
								?>
								<li><a href="/admin/update_uuids/?uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['update_uuid']; ?></a></li>
								<li><a href="/admin/update_mcnames/?uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['update_mc_name']; ?></a></li>
								<?php
								}
								?>
								<li><a href="/admin/reset_password/?uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['reset_password']; ?></a></li>
								<li><a href="/mod/punishments/?uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['punish_user']; ?></a></li>
								<li><a href="/admin/users/?action=delete&uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['delete_user']; ?></a></li>
							  </ul>
							</div>
						</span>
						<br /><br />
						<form role="form" action="" method="post">
						  <div class="form-group">
							<label for="InputUsername"><?php echo $user_language['username']; ?></label>
							<input type="text" name="username" class="form-control" id="InputUsername" placeholder="<?php echo $user_language['username']; ?>" value="<?php echo htmlspecialchars($individual[0]->username); ?>">
						  </div>
						  <div class="form-group">
							<label for="InputEmail"><?php echo $user_language['email_address']; ?></label>
							<input type="email" name="email" class="form-control" id="InputEmail" placeholder="<?php echo $user_language['email_address']; ?>" value="<?php echo htmlspecialchars($individual[0]->email); ?>">
						  </div>
						  <?php
						  if($displaynames === "true"){
						  ?>
						  <div class="form-group">
							<label for="InputMCUsername"><?php echo $user_language['minecraft_username']; ?></label>
							<input type="text" name="MCUsername" class="form-control" name="MCUsername" id="InputMCUsername" placeholder="<?php echo $user_language['minecraft_username']; ?>" value="<?php echo htmlspecialchars($individual[0]->mcname); ?>">
						  </div>
						  <?php
						  } else {
						  ?>
						  <input type="hidden" name="MCUsername" value="<?php echo htmlspecialchars($individual[0]->username); ?>">
						  <?php
						  }
						  if($uuid_linking == '1'){
						  ?>
						  <div class="form-group">
							<label for="InputUUID"><?php echo $admin_language['minecraft_uuid']; ?></label>
							<input type="text" name="UUID" class="form-control" id="InputUUID" placeholder="<?php echo $admin_language['minecraft_uuid']; ?>" value="<?php echo htmlspecialchars($individual[0]->uuid); ?>">
						  </div>
						  <?php
						  }
						  ?>
						  <div class="form-group">
							<label for="InputTitle"><?php echo $user_language['user_title']; ?></label>
							<input type="text" class="form-control" name="title" id="InputTitle"  value="<?php echo htmlspecialchars($individual[0]->user_title);?>"></input>
						  </div>
						  <div class="form-group">
							<label for="InputSignature"><?php echo $user_language['signature']; ?></label>
							<textarea class="signature" rows="10" name="signature" id="InputSignature"><?php echo $signature; ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="InputIP"><?php echo $admin_language['ip_address']; ?></label>
							<input class="form-control" name="ip" id="InputIP" type="text" placeholder="<?php echo htmlspecialchars($individual[0]->lastip); ?>" readonly>
						  </div>
						  <?php 
						  $groups = $queries->orderAll('groups', 'name', 'ASC');
						  ?>
						  <div class="form-group">
							 <label for="InputGroup"><?php echo $admin_language['group']; ?></label>
							 <select class="form-control" id="InputGroup" name="group"<?php if($_GET['user'] == 1){ ?> disabled<?php } ?>>
							<?php 
							foreach($groups as $group){ 
							?>
							  <option value="<?php echo $group->id; ?>" <?php if($group->id === $individual[0]->group_id){ echo 'selected="selected"'; } ?>><?php echo $group->name; ?></option>
							<?php 
							} 
							?>
							</select> 
						  </div>
						  <?php if($_GET['user'] == 1){ ?>
						  <input type="hidden" name="group" value="2">
						  <div class="alert alert-warning">
						    <?php echo $admin_language['cant_modify_root_user']; ?>
						  </div>
						  <?php } ?>

						  <div class="form-group">
						    <label for="InputGroup2"><?php echo $admin_language['group2']; ?></label>
						    <select class="form-control" id="InputGroup2" name="group2">
                  <option value="0"><?php echo $general_language['none']; ?></option>
						    <?php
						    foreach($groups as $group){
						    ?>
						      <option value="<?php echo $group->id; ?>" <?php if($group->id === $individual[0]->group2_id){ echo 'selected="selected"'; } ?>><?php echo $group->name; ?></option>
						    <?php
						    }
						    ?>
						    </select>
						  </div>
						  <input type="hidden" name="token" value="<?php echo $token; ?>">
						  <input type="hidden" name="action" value="update">
						  <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-default">
						</form>
						<br />
						<?php
						// Is avatar uploading enabled?
						$avatar_enabled = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
						$avatar_enabled = $avatar_enabled[0]->value;

						if($avatar_enabled === "1"){
							// Does the user have an avatar enabled?
							$avatar_enabled = $queries->getWhere('users', array('id', '=', $_GET['user']));
							$avatar_enabled = $avatar_enabled[0]->has_avatar;

							if($avatar_enabled === "1"){ // Yes
							?>
							<strong><?php echo $admin_language['other_actions']; ?></strong><br />
							<form role="form" action="" method="post">
							  <input type="hidden" name="token" value="<?php echo $token; ?>">
							  <input type="hidden" name="action" value="avatar_disable">
							  <input type="submit" value="<?php echo $admin_language['disable_avatar']; ?>" class="btn btn-danger">
							</form>
							<?php 
							
							// Doesn't have an avatar enabled, but does one exist? If so, let the admin choose to enable it
							} else if (count(glob(__DIR__ . '/../../avatars/' . $_GET["user"] . '.*'))) { 
							?>
							<strong><?php echo $admin_language['other_actions']; ?></strong><br />
							<form role="form" action="" method="post">
							  <input type="hidden" name="token" value="<?php echo $token; ?>">
							  <input type="hidden" name="action" value="avatar_enable">
							  <input type="submit" value="<?php echo $admin_language['enable_avatar']; ?>" class="btn btn-success">
							</form>
							<?php
							}
						}
					}
				}
			}
			?>
		  </div>
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

	<script src="/core/assets/js/tables/jquery.dataTables.min.js"></script>
	<script src="/core/assets/js/tables/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript">
        $(document).ready(function() {
            $('.dataTables-users').dataTable({
                responsive: true,
				language: {
					"lengthMenu": "<?php echo $table_language['display_records_per_page']; ?>",
					"zeroRecords": "<?php echo $table_language['nothing_found']; ?>",
					"info": "<?php echo $table_language['page_x_of_y']; ?>",
					"infoEmpty": "<?php echo $table_language['no_records']; ?>",
					"infoFiltered": "<?php echo $table_language['filtered']; ?>",
					"search": "<?php echo $general_language['search']; ?> "
				}
            });
		});
	</script>
	
	<script src="/core/assets/js/ckeditor.js"></script>
	<script type="text/javascript">
		CKEDITOR.replace( 'signature', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"styles","groups":["styles"]},
				{"name":"colors","groups":["colors"]},
				{"name":"links","groups":["links"]},
				{"name":"insert","groups":["insert"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
		} );
		CKEDITOR.timestamp = '2';
		CKEDITOR.config.disableNativeSpellChecker = false;
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	</script>
  </body>
</html>
