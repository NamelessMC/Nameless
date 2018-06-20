<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Admin Users page
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
		} else if(!$user->hasPermission('admincp.users')){
            // Can't view this page
            if($user->hasPermission('admincp.groups'))
              Redirect::to(URL::build('/admin/groups'));
            else
              require(ROOT_PATH . '/404.php');
            die();
        }
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
 
$page = 'admin';
$admin_page = 'users_and_groups';

require(ROOT_PATH . '/core/integration/uuid.php'); // For UUID stuff
require(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML

// Is UUID linking enabled?
$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
$uuid_linking = $uuid_linking[0]->value;

$mc_integration = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
$mc_integration = $mc_integration[0]->value;

require(ROOT_PATH . '/core/includes/password.php'); // Password compat library

?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
  <head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php 
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php'); 
	?>
  
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.sprites.css"/>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/css/emojionearea.min.css"/>
  
  </head>

  <body>
    <?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
    <div class="container">
	  <div class="row">
		<div class="col-md-3">
		  <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <ul class="nav nav-pills">
				<li class="nav-item">
				  <a class="nav-link active"><?php echo $language->get('admin', 'users'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link" href="<?php echo URL::build('/admin/groups'); ?>"><?php echo $language->get('admin', 'groups'); ?></a>
				</li>
			  </ul>
		      <hr />
			  <h3 style="display:inline"><?php echo $language->get('admin', 'users'); ?></h3>
			  <?php
				if(Session::exists('adm-users')){
					echo Session::flash('adm-users');
				}
				
				if(!isset($_GET["action"]) && !isset($_GET["user"])){
					if(isset($_GET['p'])){
						if(!is_numeric($_GET['p'])){
							Redirect::to(URL::build('/admin/users'));
							die();
						} else {
							if($_GET['p'] == 1){ 
								// Avoid bug in pagination class
								Redirect::to(URL::build('/admin/users'));
								die();
							}
							$p = $_GET['p'];
						}
					} else {
						$p = 1;
					}
					
					$users = $queries->orderAll('users', 'USERNAME', 'ASC');
					$groups = $queries->getWhere('groups', array('id', '<>', 0));
				?>
				<span class="pull-right"><a href="<?php echo URL::build('/admin/users/', 'action=new'); ?>" class="btn btn-primary"><?php echo $language->get('admin', 'new_user'); ?></a></span>
				<br /><br />
				
				<div class="table-responsive">
				  <table class="table table-bordered dataTables-users">
					<colgroup>
					   <col span="1" style="width: 25%;">
					   <col span="1" style="width: 15%;">
					   <col span="1" style="width: 20%">
					   <col span="1" style="width: 25%">
					   <col span="1" style="width: 15%">
					</colgroup>
					<thead>
					  <tr>
					    <th><?php echo $language->get('user', 'username'); ?></th>
					    <th><?php echo $language->get('admin', 'group'); ?></th>
					    <th><?php echo $language->get('user', 'email'); ?></th>
					    <th><?php echo $language->get('admin', 'registered'); ?></th>
					    <th><?php echo $language->get('general', 'edit'); ?></th>
					  </tr>
					</thead>
					<tbody>
						<?php
						foreach($users as $individual){
							$user_group = '';
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
						  <td><a href="<?php echo URL::build('/admin/users/', 'user=' . $individual->id); ?>" class="btn btn-primary btn-sm"><?php echo $language->get('general', 'edit'); ?></a></td>
						</tr>
						<?php
						}
						?>
					</tbody>
				  </table>
				</div>
				<?php 
				} else if(isset($_GET['action']) && $_GET['action'] !== 'validate'){
					if($_GET['action'] === 'new'){
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
									),
                                    'nickname' => array(
                                        'required' => true,
                                        'min' => 4,
                                        'max' => 20,
                                        'unique' => 'users'
                                    ),
                                    'username' => array(
                                        'required' => true,
                                        'min' => 4,
                                        'max' => 20,
                                        'unique' => 'users'
                                    )
								);
								
								$validation = $validate->check($_POST, $to_validation);
								
								if($validation->passed()){
									$user = new User();
									
									$password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 13));
									
									// Get current unix time
									$date = new DateTime();
									$date = $date->getTimestamp();

									// Try to get UUID
									if($uuid_linking == '1'){
									    if(!isset($mcname_result)){
									        $profile = ProfileUtils::getProfile(str_replace(' ', '%20', Input::get('username')));
									        $mcname_result = $profile->getProfileAsArray();
									    }
									    if(isset($mcname_result["uuid"]) && !empty($mcname_result['uuid'])){
									        $uuid = $mcname_result['uuid'];

									    } else {
									        $uuid = '';
									    }
									} else {
									    $uuid = '';
									}
									
									try {
										$user->create(array(
											'username' => htmlspecialchars(Input::get('username')),
											'nickname' => htmlspecialchars(Input::get('nickname')),
											'uuid' => htmlspecialchars($uuid),
											'password' => $password,
											'pass_method' => 'default',
											'joined' => $date,
											'group_id' => Input::get('group'),
											'email' => htmlspecialchars(Input::get('email')),
											'active' => 1
										));

										Log::getInstance()->log(Log::Action('admin/user/create'), htmlspecialchars(Input::get('username')));

										Session::flash('adm-users', '<div class="alert alert-success">' . $language->get('admin', 'user_created') . '</div>');
										
										Redirect::to(URL::build('/admin/users'));
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
										case (strpos($error, 'nickname') !== false):
											echo $language->get('user', 'username_required') . '<br />';
										break;
										case (strpos($error, 'email') !== false):
											echo $language->get('user', 'email_required') . '<br />';
										break;
										case (strpos($error, 'password') !== false):
											echo $language->get('user', 'password_required') . '<br />';
										break;
										case (strpos($error, 'username') !== false):
											echo $language->get('user', 'mcname_required') . '<br />';
										break;
										case (strpos($error, 'group') !== false):
											echo $language->get('admin', 'select_user_group') . '<br />';
										break;
									}
									
								} else if(strpos($error, 'minimum') !== false){
									// x must be a minimum of y characters long
									switch($error){
										case (strpos($error, 'nickname') !== false):
											echo $language->get('user', 'username_minimum_3') . '<br />';
										break;
										case (strpos($error, 'username') !== false):
											echo $language->get('user', 'mcname_minimum_3') . '<br />';
										break;
										case (strpos($error, 'password') !== false):
											echo $language->get('user', 'password_minimum_6') . '<br />';
										break;
										case (strpos($error, 'email') !== false):
											echo $language->get('user', 'invalid_email') . '<br />';
										break;
									}
									
								} else if(strpos($error, 'maximum') !== false){
									// x must be a maximum of y characters long
									switch($error){
										case (strpos($error, 'nickname') !== false):
											echo $language->get('user', 'username_maximum_20') . '<br />';
										break;
										case (strpos($error, 'username') !== false):
											echo $language->get('user', 'mcname_maximum_20') . '<br />';
										break;
										case (strpos($error, 'password') !== false):
											echo $language->get('user', 'password_maximum_30') . '<br />';
										break;
									}
									
								} else if(strpos($error, 'must match') !== false){
									// password must match password again
									echo $language->get('user', 'passwords_dont_match') . '<br />';
									
								} else if(strpos($error, 'already exists') !== false){
									// already exists
									echo $language->get('user', 'username_mcname_email_exists') . '<br />';
									
								} else if(strpos($error, 'not a valid Minecraft account') !== false){
									// Invalid Minecraft username
									echo $language->get('user', 'invalid_mcname') . '<br />';
									
								} else if(strpos($error, 'Mojang communication error') !== false){
									// Mojang server error
									echo $language->get('user', 'mcname_lookup_error') . '<br />';
									
								}
							}
							?>
						</div>
						<?php 
							}
						}
						?>
						<span class="pull-right"><a href="<?php echo URL::build('/admin/users'); ?>" class="btn btn-danger" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a></span>
						<form action="" method="post">
							<br />
							<h4><?php echo $language->get('admin', 'creating_new_user'); ?></h4>
							<div class="form-group">
							    <input class="form-control" type="text" name="nickname" id="nickname" value="<?php echo Output::getClean(Input::get('nickname')); ?>" placeholder="<?php echo $language->get('user', 'nickname'); ?>" autocomplete="off">
							</div>
							<div class="form-group">
								<input class="form-control" type="text" name="username" id="username" value="<?php echo Output::getClean(Input::get('username')); ?>" placeholder="<?php if($mc_integration == '1') echo $language->get('user', 'minecraft_username'); else echo $language->get('user', 'username'); ?>" autocomplete="off">
							</div>
							<div class="form-group">
								<input class="form-control" type="text" name="email" id="email" value="<?php echo Output::getClean(Input::get('email')); ?>" placeholder="<?php echo $language->get('user', 'email'); ?>">
							</div>
							<div class="form-group">
								<input class="form-control" type="password" name="password" id="password" placeholder="<?php echo $language->get('user', 'password'); ?>">
							</div>
							<input class="form-control" type="password" name="password_again" id="password_again" placeholder="<?php echo $language->get('user', 'confirm_password'); ?>">	
							<input type="hidden" name="token" value="<?php echo Token::get(); ?>"><br />
							<strong><?php echo $language->get('admin', 'group'); ?></strong>
							<select name="group" id="group" size="5" class="form-control">
							  <?php
								$groups = $queries->orderAll('groups', 'name', 'ASC'); 
								$n = 0;
								while ($n < count($groups)){
									$result = (array)$groups[$n];
									echo '<option value="' . $result['id'] . '">' . Output::getClean($result['name']) . '</option>';
									$n++;
								}
							  ?>
							</select>
							<br />
							<input class="btn btn-success" type="submit" value="<?php echo $language->get('general', 'submit'); ?>">	
						</form>
						<?php 
					// Delete a user
					} else if($_GET['action'] == 'delete'){
						// Check for a valid user ID
						if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
							// Invalid, redirect
							Redirect::to(URL::build('/admin/users'));
							die();
						} else {
							// Can't delete first user
							if($_GET['uid'] == 1){
								Session::flash('adm-users', '<div class="alert alert-danger alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $language->get('admin', 'cant_delete_root_user') . '</div>');
								Redirect::to(URL::build('/admin/users'));
								die();
							}
							
							// Valid, has the admin confirmed deletion?
							if(isset($_GET['confirm'])){
								// Delete the user
								$queries->delete('users', array('id', '=', $_GET['uid']));
								
								// Delete the user's posts
								$queries->delete('posts', array('post_creator', '=', $_GET['uid']));
								
								// Delete the user's topics
								$queries->delete('topics', array('topic_creator', '=', $_GET['uid']));

								// IP logs
                                $queries->delete('users_ips', array('user_id', '=', $_GET['uid']));

                                // Logs
                                $queries->delete('logs', array('user_id', '=', $_GET['uid']));

                                // Alerts
                                $queries->delete('alerts', array('user_id', '=', $_GET['uid']));

                                // Blocked users
                                $queries->delete('blocked_users', array('user_id', '=', $_GET['uid']));
                                $queries->delete('blocked_users', array('user_blocked_id', '=', $_GET['uid']));

                                // Email errors
                                $queries->delete('email_errors', array('user_id', '=', $_GET['uid']));

                                // Forum reactions
                                $queries->delete('forums_reactions', array('user_received', '=', $_GET['uid']));
                                $queries->delete('forums_reactions', array('user_given', '=', $_GET['uid']));

                                // Friends
                                $queries->delete('friends', array('user_id', '=', $_GET['uid']));
                                $queries->delete('friends', array('friend_id', '=', $_GET['uid']));

                                // Infractions
                                $queries->delete('infractions', array('punished', '=', $_GET['uid']));
                                $queries->delete('infractions', array('staff', '=', $_GET['uid']));

                                // Private messages
                                $queries->delete('private_messages', array('author_id', '=', $_GET['uid']));
                                $queries->delete('private_messages_replies', array('author_id', '=', $_GET['uid']));
                                $queries->delete('private_messages_users', array('user_id', '=', $_GET['uid']));

                                // Reports
                                $queries->delete('reports', array('reporter_id', '=', $_GET['uid']));
                                $queries->delete('reports_comments', array('commenter_id', '=', $_GET['uid']));

                                // User sessions
                                $queries->delete('users_admin_session', array('user_id', '=', $_GET['uid']));
                                $queries->delete('users_session', array('user_id', '=', $_GET['uid']));

                                // Profile fields
                                $queries->delete('users_profile_fields', array('user_id', '=', $_GET['uid']));

                                // Username history
                                $queries->delete('users_username_history', array('user_id', '=', $_GET['uid']));

                                // Profile wall posts
                                $queries->delete('user_profile_wall_posts', array('user_id', '=', $_GET['uid']));
                                $queries->delete('user_profile_wall_posts', array('author_id', '=', $_GET['uid']));
                                $queries->delete('user_profile_wall_posts_reactions', array('user_id', '=', $_GET['uid']));
                                $queries->delete('user_profile_wall_posts_replies', array('author_id', '=', $_GET['uid']));

								//TODO: Username
								Log::getInstance()->log(Log::Action('admin/user/delete'));

								Session::flash('adm-users', '<div class="alert alert-success alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $language->get('admin', 'user_deleted') . '</div>');
								Redirect::to(URL::build('/admin/users'));
								die();
							} else {
								// Confirm
								$individual = $queries->getWhere('users', array('id', '=', $_GET['uid']));
								if(count($individual)){
								?>
					<p><?php echo str_replace('{x}', Output::getClean($individual[0]->username), $language->get('admin', 'confirm_user_deletion')); ?></p>
					<div class="btn-group" role="group">
					  <a href="<?php echo URL::build('/admin/users/', 'action=delete&confirm=true&uid=' . $_GET["uid"]); ?>" class="btn btn-danger"><?php echo $language->get('general', 'confirm'); ?></a>
					  <a href="<?php echo URL::build('/admin/users/', 'user=' . $individual[0]->id); ?>" class="btn btn-secondary"><?php echo $language->get('general', 'cancel'); ?></a>
					</div>
								<?php
								} else {
									// No user exists with that ID
									Redirect::to(URL::build('/admin/users'));
									die();
								}
							}
						}
					}
				} else if(isset($_GET["user"])){
					if(isset($_GET['action']) && $_GET['action'] == 'validate'){
						$individual = $queries->getWhere('users', array('id', '=', $_GET['user']));
						if($individual[0]->active == 0){
							// Activate user
							$queries->update('users', $_GET['user'], array(
								'active' => 1
							));
							Redirect::to(URL::build('/admin/users/', 'user=' . $_GET['user']));
							die();
						} else {
							// Already active
							Redirect::to(URL::build('/admin/users/', 'user=' . $_GET['user']));
							die();
						}
					} else {
                        if(!is_numeric($_GET['user'])){
                            $individual = $queries->getWhere('users', array('username', '=', $_GET['user']));
                        } else {
                            $individual = $queries->getWhere('users', array('id', '=', $_GET['user']));
                        }

                        if(!count($individual)){
                            Redirect::to(URL::build('/admin/users'));
                            die();
                        }

                        $displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
                        $displaynames = $displaynames[0]->value;

						if(Input::exists()) {
							if(Token::check(Input::get('token'))) {
								if(Input::get('action') === 'update'){
									// Update a user's settings
									$signature = Input::get('signature');
									$_POST['signature'] = strip_tags(Input::get('signature'));
									
									$validate = new Validate();
									
									$to_validation = array(
										'email' => array(
											'required' => true,
											'min' => 4,
											'max' => 50
										),
										'UUID' => array(
											'max' => 32
										),
										'signature' => array(
											'max' => 900
										),
										'ip' => array(
											'max' => 256
										),
										'title' => array(
											'max' => 64
										),
										'MCUsername' => array(
										    'required' => true,
										    'min' => 3,
										    'max' => 20
										),
                                        'username' => array(
                                            'required' => true,
                                            'min' => 3,
                                            'max' => 20
                                        )
									);

									if($_GET['user'] != 1 && ($_GET['user'] != $user->data()->id || $user->hasPermission('admincp.groups.self'))){
										$to_validation['group'] = array(
											'required' => true
										);
										$group = Input::get('group');
									} else {
										$group = $individual[0]->group_id;
									}
									
									$validation = $validate->check($_POST, $to_validation);
									
									if($validation->passed()){
										try {
											// Signature from Markdown -> HTML if needed
											$cache->setCache('post_formatting');
											$formatting = $cache->retrieve('formatting');
											
											if($formatting == 'markdown'){
												$signature = Michelf\Markdown::defaultTransform($signature);
												$signature = Output::getClean($signature);
											} else {
												$signature = Output::getClean($signature);
											}

											$private_profile_active = $queries->getWhere('settings', array('name', '=', 'private_profile'));
											$private_profile_active = $private_profile_active[0]->value == 1;
											
											if($private_profile_active){
                                                $queries->update('users', $_GET["user"], array(
                                                    'private_profile' => Input::get('privateProfile')
                                                ));
                                            }

											// Get secondary groups
                                            if(isset($_POST['secondary_groups']) && count($_POST['secondary_groups'])){
											    $secondary_groups = json_encode($_POST['secondary_groups']);
                                            } else {
                                                $secondary_groups = '';
                                            }
											
											$queries->update('users', $_GET["user"], array(
												'nickname' => htmlspecialchars(Input::get('username')),
												'email' => htmlspecialchars(Input::get('email')),
												'group_id' => $group,
												'username' => Output::getClean(Input::get('MCUsername')),
												'user_title' => Output::getClean(Input::get('title')),
												'uuid' => htmlspecialchars(Input::get('UUID')),
												'signature' => $signature,
                                                'secondary_groups' => $secondary_groups
											));
											Log::getInstance()->log(Log::Action('admin/user/update'), Output::getClean(Input::get('MCUsername')));
											Redirect::to(URL::build('/admin/users/', 'user=' . $_GET['user']));
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
														echo $language->get('user', 'username_required') . '<br />';
													break;
													case (strpos($error, 'email') !== false):
														echo $language->get('user', 'email_required') . '<br />';
													break;
													case (strpos($error, 'password') !== false):
														echo $language->get('user', 'password_required') . '<br />';
													break;
													case (strpos($error, 'MCUsername') !== false):
														echo $language->get('user', 'mcname_required') . '<br />';
													break;
													case (strpos($error, 'group') !== false):
														echo $language->get('admin', 'select_user_group') . '<br />';
													break;
												}
												
											} else if(strpos($error, 'minimum') !== false){
												// x must be a minimum of y characters long
												switch($error){
													case (strpos($error, 'username') !== false):
														echo $language->get('user', 'username_minimum_3') . '<br />';
													break;
													case (strpos($error, 'MCUsername') !== false):
														echo $language->get('user', 'mcname_minimum_3') . '<br />';
													break;
													case (strpos($error, 'password') !== false):
														echo $language->get('user', 'password_minimum_6') . '<br />';
													break;
												}
												
											} else if(strpos($error, 'maximum') !== false){
												// x must be a maximum of y characters long
												switch($error){
													case (strpos($error, 'username') !== false):
														echo $language->get('user', 'username_maximum_20') . '<br />';
													break;
													case (strpos($error, 'MCUsername') !== false):
														echo $language->get('user', 'mcname_maximum_20') . '<br />';
													break;
													case (strpos($error, 'password') !== false):
														echo $language->get('user', 'password_maximum_30') . '<br />';
													break;
													case (strpos($error, 'UUID') !== false):
														echo $language->get('admin', 'uuid_max_32') . '<br />';
													break;
													case (strpos($error, 'title') !== false):
														echo $language->get('admin', 'title_max_64') . '<br />';
													break;
												}
												
											} else if(strpos($error, 'must match') !== false){
												// password must match password again
												echo $language->get('user', 'passwords_dont_match') . '<br />';
												
											} else if(strpos($error, 'already exists') !== false){
												// already exists
												echo $language->get('user', 'username_mcname_email_exists') . '<br />';
												
											} else if(strpos($error, 'not a valid Minecraft account') !== false){
												// Invalid Minecraft username
												echo $language->get('user', 'invalid_mcname') . '<br />';
												
											} else if(strpos($error, 'Mojang communication error') !== false){
												// Mojang server error
												echo $language->get('user', 'mcname_lookup_error') . '<br />';
												
											}
										}
										echo '</div>';
									}
								} else if(Input::get('action') == 'delete'){
									try {
										$queries->delete('users', array('id', '=' , $data[0]->id));
										
									} catch(Exception $e) {
										die($e->getMessage());
									}

									Redirect::to(URL::build('/admin/users'));
									die();
								} else if(Input::get('action') == 'avatar_disable'){
									try {
										$queries->update('users', $_GET['user'], array(
											'has_avatar' => 0
										));
									} catch(Exception $e) {
										die($e->getMessage());
									}
								} else if(Input::get('action') == 'avatar_enable'){ 
									try {
										$queries->update('users', $_GET['user'], array(
											'has_avatar' => 1
										));
									} catch(Exception $e) {
										die($e->getMessage());
									}
								}

								// Re-query individual
                                if(!is_numeric($_GET['user'])){
                                    $individual = $queries->getWhere('users', array('username', '=', $_GET['user']));
                                } else {
                                    $individual = $queries->getWhere('users', array('id', '=', $_GET['user']));
                                }
							}
						}
						if(count($individual)){
							$token = Token::get();
							
							echo '<br /><br /><h4 style="display: inline;">' . Output::getClean($individual[0]->username) . '</h4>';
							?>
							<span class="pull-right">
								<?php if($individual[0]->active == 0){ ?>
								<a href="<?php echo URL::build('/admin/users', 'user=' . $individual[0]->id . '&action=validate'); ?>" class="btn btn-secondary"><?php echo $language->get('admin', 'validate_user'); ?></a>
								<?php } ?>
								<div class="btn-group">
								  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
									<?php echo $language->get('general', 'actions'); ?> <span class="caret"></span>
								  </button>
								  <div class="dropdown-menu" role="menu">
									<?php
									// Is UUID linking enabled?
									$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
									$uuid_linking = $uuid_linking[0]->value;
									if($uuid_linking == '1'){
									?>
									<li><a class="dropdown-item" href="<?php echo URL::build('/admin/update_uuids/', 'uid=' . $individual[0]->id); ?>"><?php echo $language->get('admin', 'update_uuid'); ?></a></li>
									<li><a class="dropdown-item" href="<?php echo URL::build('/admin/update_mcnames/', 'uid=' . $individual[0]->id); ?>"><?php echo $language->get('admin', 'update_mc_name'); ?></a></li>
									<?php
									}
									?>
									<!--<li><a class="dropdown-item" href="<?php echo URL::build('/admin/reset_password/', 'uid=' . $individual[0]->id); ?>"><?php echo $language->get('admin', 'reset_password'); ?></a></li>-->
									<li><a class="dropdown-item" href="<?php echo URL::build('/mod/punishments/', 'user=' . $individual[0]->id); ?>"><?php echo $language->get('admin', 'punish_user'); ?></a></li>
									<li><a class="dropdown-item" href="<?php echo URL::build('/admin/users/', 'action=delete&uid=' . $individual[0]->id); ?>"><?php echo $language->get('admin', 'delete_user'); ?></a></li>
								  </div>
								</div>
							</span>
							<br /><br />
							<form role="form" action="" method="post">
							  <div class="form-group">
								<label for="InputUsername"><?php echo $language->get('user', 'username'); ?></label>
								<input type="text" name="username" class="form-control" id="InputUsername" placeholder="<?php echo $language->get('user', 'username'); ?>" value="<?php echo Output::getClean($individual[0]->nickname); ?>">
							  </div>
							  <div class="form-group">
								<label for="InputEmail"><?php echo $language->get('user', 'email_address'); ?></label>
								<input type="email" name="email" class="form-control" id="InputEmail" placeholder="<?php echo $language->get('user', 'email_address'); ?>" value="<?php echo Output::getClean($individual[0]->email); ?>">
							  </div>
							  <?php
							  if($displaynames === "true"){
							  ?>
							  <div class="form-group">
								<label for="InputMCUsername"><?php echo $language->get('user', 'minecraft_username'); ?></label>
								<input type="text" name="MCUsername" class="form-control" name="MCUsername" id="InputMCUsername" placeholder="<?php echo $language->get('user', 'minecraft_username'); ?>" value="<?php echo Output::getClean($individual[0]->username); ?>">
							  </div>
							  <?php
							  } else {
							  ?>
							  <input type="hidden" name="MCUsername" value="<?php echo Output::getClean($individual[0]->username); ?>">
							  <?php
							  }
							  if($uuid_linking == '1'){
							  ?>
							  <div class="form-group">
								<label for="InputUUID"><?php echo $language->get('admin', 'minecraft_uuid'); ?></label>
								<input type="text" name="UUID" class="form-control" id="InputUUID" placeholder="<?php echo $language->get('admin', 'minecraft_uuid'); ?>" value="<?php echo Output::getClean($individual[0]->uuid); ?>">
							  </div>
							  <?php
							  }
							  ?>
							  <div class="form-group">
								<label for="InputTitle"><?php echo $language->get('admin', 'title'); ?></label>
								<input type="text" name="title" class="form-control" id="InputTitle" placeholder="<?php echo $language->get('admin', 'title'); ?>" value="<?php echo Output::getClean($individual[0]->user_title); ?>">
							  </div>
							  <?php
                              // Get private profile settings
                              $private_profile = $queries->getWhere('settings', array('name', '=', 'private_profile'));
                              $private_profile = $private_profile[0];

                              if($private_profile->value == 1){
                              ?>
                              <div class="form-group">
                                <label for="inputPrivateProfile"><?php echo $language->get('user', 'private_profile'); ?></label>
                                <?php
                                $private_profile = $individual[0]->private_profile;
                                ?>
                                <select name="privateProfile" class="form-control" id="inputPrivateProfile">
                                  <option value="1"<?php if ($private_profile == 1) { ?> selected<?php } ?>><?php echo $language->get('admin', 'enabled'); ?></option>
                                  <option value="0"<?php if ($private_profile == 0) { ?> selected<?php } ?>><?php echo $language->get('admin', 'disabled'); ?></option>
                                </select>
                                </div>
                              <?php }?>
							  <div class="form-group">
								<label for="InputSignature"><?php echo $language->get('user', 'signature'); ?></label>
								<?php
								// HTML -> Markdown if necessary
								$cache->setCache('post_formatting');
								$formatting = $cache->retrieve('formatting');
								
								if($formatting == 'markdown'){
									require(ROOT_PATH . '/core/includes/markdown/tomarkdown/autoload.php');
									$converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => true));

									$signature = $converter->convert(htmlspecialchars_decode($individual[0]->signature));
									$signature = Output::getPurified($signature);
								} else {
									$signature = Output::getPurified(htmlspecialchars_decode($individual[0]->signature));
								}
								?>
								<textarea style="width:100%" rows="10" name="signature" id="InputSignature"><?php echo $signature; ?></textarea>
							  </div>
							  <div class="form-group">
								<label for="InputIP"><?php echo $language->get('admin', 'ip_address'); ?></label>
								<input class="form-control" name="ip" id="InputIP" type="text" placeholder="<?php echo Output::getClean($individual[0]->lastip); ?>" readonly>
							  </div>
							  <?php 
							  $groups = $queries->orderAll('groups', 'name', 'ASC');
							  ?>
							  <div class="form-group">
								 <label for="InputGroup"><?php echo $language->get('admin', 'group'); ?></label>
								 <select class="form-control" id="InputGroup" name="group"<?php if($_GET['user'] == 1 || ($_GET['user'] == $user->data()->id && !$user->hasPermission('admincp.groups.self'))){ ?> disabled<?php } ?>>
								<?php 
								foreach($groups as $group){ 
								?>
								  <option value="<?php echo $group->id; ?>" <?php if($group->id === $individual[0]->group_id){ echo 'selected="selected"'; } ?>><?php echo Output::getClean($group->name); ?></option>
								<?php 
								} 
								?>
								</select> 
							  </div>
							  <?php if($_GET['user'] == 1){ ?>
							  <div class="alert alert-warning">
								<?php echo $language->get('admin', 'cant_modify_root_user'); ?>
							  </div>
							  <?php } ?>
							  <div class="form-group">
							    <label for="inputSecondaryGroups"><?php echo $language->get('admin', 'secondary_groups'); ?></label>
							    <div class="alert alert-info"><?php echo $language->get('admin', 'secondary_groups_info'); ?></div>
							    <select class="form-control" name="secondary_groups[]" id="inputSecondaryGroups" multiple>
                                    <?php
                                    $secondary_groups = json_decode($individual[0]->secondary_groups, true);
                                    if(is_null($secondary_groups)) $secondary_groups = array();

                                    foreach($groups as $group){
                                        if($individual[0]->group_id == $group->id)
                                            continue;

                                        echo '<option value="' . $group->id . '"';
                                        if(in_array($group->id, $secondary_groups))
                                            echo ' selected="selected"';
                                        echo '>' . Output::getClean($group->name) . '</option>';
                                    }
                                    ?>
							    </select>
							  </div>
							  <input type="hidden" name="token" value="<?php echo $token; ?>">
							  <input type="hidden" name="action" value="update">
							  <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-primary">
							</form>
							<br />
							<?php
							// Is avatar uploading enabled?
							$avatar_enabled = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
							$avatar_enabled = $avatar_enabled[0]->value;

							if($avatar_enabled == "1"){
								// Does the user have an avatar enabled?
								$avatar_enabled = $queries->getWhere('users', array('id', '=', $_GET['user']));
								$avatar_enabled = $avatar_enabled[0]->has_avatar;

								if($avatar_enabled == "1"){ // Yes
								?>
								<strong><?php echo $language->get('admin', 'other_actions'); ?></strong><br />
								<form role="form" action="" method="post">
								  <input type="hidden" name="token" value="<?php echo $token; ?>">
								  <input type="hidden" name="action" value="avatar_disable">
								  <input type="submit" value="<?php echo $language->get('admin', 'disable_avatar'); ?>" class="btn btn-danger">
								</form>
								<?php 
								
								// Doesn't have an avatar enabled, but does one exist? If so, let the admin choose to enable it
								} else if (count(glob(__DIR__ . '/../../avatars/' . $_GET["user"] . '.*'))) { 
								?>
								<strong><?php echo $language->get('admin', 'other_actions'); ?></strong><br />
								<form role="form" action="" method="post">
								  <input type="hidden" name="token" value="<?php echo $token; ?>">
								  <input type="hidden" name="action" value="avatar_enable">
								  <input type="submit" value="<?php echo $language->get('admin', 'enable_avatar'); ?>" class="btn btn-success">
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
    </div>
	
	<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>
    <?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>

	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dataTables/jquery.dataTables.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

	<script type="text/javascript">
        $(document).ready(function() {
            $('.dataTables-users').dataTable({
                responsive: true,
				language: {
					"lengthMenu": "<?php echo $language->get('table', 'display_records_per_page'); ?>",
					"zeroRecords": "<?php echo $language->get('table', 'nothing_found'); ?>",
					"info": "<?php echo $language->get('table', 'page_x_of_y'); ?>",
					"infoEmpty": "<?php echo $language->get('table', 'no_records'); ?>",
					"infoFiltered": "<?php echo $language->get('table', 'filtered'); ?>",
					"search": "<?php echo $language->get('general', 'search'); ?> ",
					"paginate": {
					    "next": "<?php echo $language->get('general', 'next'); ?>",
					    "previous": "<?php echo $language->get('general', 'previous'); ?>"
					}
				}
            });
		});
	</script>
	
	<?php
	// Get post formatting type (HTML or Markdown)
	$cache->setCache('post_formatting');
	$formatting = $cache->retrieve('formatting');
	
	if($formatting == 'markdown'){
		// Markdown
		?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/js/emojionearea.min.js"></script>
	
	<script type="text/javascript">
	  $(document).ready(function() {
	    var el = $("#InputSignature").emojioneArea({
			pickerPosition: "bottom"
		});
	  });
	</script>
	<?php
	} else {
	?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
        <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/emojione/dialogs/emojione.json"></script>
        <script type="text/javascript">
            <?php echo Input::createEditor('InputSignature'); ?>
        </script>
	<?php } ?>
  </body>
</html>
