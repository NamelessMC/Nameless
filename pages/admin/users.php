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
					  
require('core/includes/password.php'); // Password compat library
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $admin_language['admin_cp']; ?> &bull; <?php echo $admin_language['users']; ?></title>
	
	<?php
	// Generate header and navbar content
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
    <div class="container">	
	  <?php
	  // "Users" page
	  // Load navbar
	  $smarty->display('styles/templates/' . $template . '/navbar.tpl');
	  
	  echo '<br />';

	  if(Session::exists('adm-alert')){
		echo Session::flash('adm-alert');
	  }
	  ?>
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
						echo '<script>window.location.replace("/admin/users/");</script>';
						die();
					} else {
						if($_GET['p'] == 1){ 
							// Avoid bug in pagination class
							echo '<script>window.location.replace("/admin/users/");</script>';
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
							
							if($displaynames == "true"){
								$to_validation['mcname'] = array(
									'required' => true,
									'isvalid' => true,
									'min' => 4,
									'max' => 20
								);
								$to_validation['username'] = array(
									'required' => true,
									'min' => 4,
									'max' => 20,
									'unique' => 'users'
								);
								$mcname = htmlspecialchars(Input::get('mcname'));
							} else {
								$to_validation['username'] = array(
									'required' => true,
									'isvalid' => true,
									'min' => 4,
									'max' => 20,
									'unique' => 'users'
								);
								$mcname = htmlspecialchars(Input::get('username'));
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
										'password' => $password,
										'pass_method' => 'default',
										'joined' => $date,
										'group_id' => Input::get('group'),
										'email' => htmlspecialchars(Input::get('email')),
										'active' => 1
									));
									echo '<script>window.location.replace("/admin/users/");</script>';
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
							echo $error, '<br />';
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
						echo '<script>window.location.replace("/admin/users/");</script>';
						die();
					} else {
						// Valid, has the admin confirmed deletion?
						if(isset($_GET["confirm"])){
							// Delete the user
							$queries->delete('users', array('id', '=', $_GET["uid"]));
							Session::flash('adm-users', '<div class="alert alert-info alert-dismissible">  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $admin_language['user_deleted'] . '</div>');
							echo '<script>window.location.replace("/admin/users/");</script>';
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
								echo '<script>window.location.replace("/admin/users/");</script>';
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
						echo '<script>window.location.replace(\'/admin/users/?user=' . $_GET['user'] . '\')</script>';
						die();
					} else {
						// already active
						echo '<script>window.location.replace(\'/admin/users/?user=' . $_GET['user'] . '\')</script>';
						die();
					}
				} else {
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							if(Input::get('action') === "update"){
								$validate = new Validate();
								$validation = $validate->check($_POST, array(
									'email' => array(
										'required' => true,
										'min' => 2,
										'max' => 50
									),
									'group' => array(
										'required' => true
									),
									'username' => array(
										'required' => true,
										'min' => 2,
										'max' => 20
									),
									'MCUsername' => array(
										'isvalid' => true
									),
									'UUID' => array(
										'max' => 32
									),
									'signature' => array(
										'max' => 256
									),
									'ip' => array(
										'max' => 256
									)
								));
								
								if($validation->passed()){
									try {
										$queries->update('users', $_GET["user"], array(
											'username' => htmlspecialchars(Input::get('username')),
											'email' => htmlspecialchars(Input::get('email')),
											'group_id' => Input::get('group'),
											'mcname' => htmlspecialchars(Input::get('MCUsername')),
											'uuid' => htmlspecialchars(Input::get('UUID')),
											'signature' => htmlspecialchars(Input::get('signature')),
											'lastip' => Input::get('ip')
										));
										echo '<script>window.location.replace("/admin/users/?user=' . $_GET['user'] . '");</script>';
										die();
									} catch(Exception $e) {
										die($e->getMessage());
									}
									
								} else {
									echo '<div class="alert alert-danger">';
									foreach($validation->errors() as $error) {
										echo $error, '<br>';
									}
									echo '</div>';
								}
							} else if(Input::get('action') == "delete"){
								try {
									$queries->delete('users', array('id', '=' , $data[0]->id));
									
								} catch(Exception $e) {
									die($e->getMessage());
								}
								echo '<script>window.location.replace("/admin/users/");</script>';
								die();
							} else if(Input::get('action') == "avatar_disable"){
								try {
									$queries->update('users', $_GET["user"], array(
										"has_avatar" => "0"
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
						$config->set('HTML.AllowedAttributes', 'href, src, height, width, alt, class, *.style');
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
								<li><a href="/admin/update_uuids/?uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['update_uuid']; ?></a></li>
								<li><a href="/admin/update_mcnames/?uid=<?php echo $individual[0]->id; ?>"><?php echo $admin_language['update_mc_name']; ?></a></li>
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
						  ?>
						  <div class="form-group">
							<label for="InputUUID"><?php echo $admin_language['minecraft_uuid']; ?></label>
							<input type="text" name="UUID" class="form-control" id="InputUUID" placeholder="<?php echo $admin_language['minecraft_uuid']; ?>" value="<?php echo htmlspecialchars($individual[0]->uuid); ?>">
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
							 <select class="form-control" id="InputGroup" name="group">
							<?php 
							foreach($groups as $group){ 
							?>
							  <option value="<?php echo $group->id; ?>" <?php if($group->id === $individual[0]->group_id){ echo 'selected="selected"'; } ?>><?php echo $group->name; ?></option>
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

						if($avatar_enabled === "true"){
						?>
						<strong><?php echo $admin_language['other_actions']; ?></strong><br />
						<form role="form" action="" method="post">
						  <input type="hidden" name="token" value="<?php echo $token; ?>">
						  <input type="hidden" name="action" value="avatar_disable">
						  <input type="submit" value="<?php echo $admin_language['disable_avatar']; ?>" class="btn btn-danger">
						</form>
						<?php 
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
	</script>
  </body>
</html>