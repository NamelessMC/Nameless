<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Admin Groups page
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
 
// Set page name for sidebar
$page = 'admin';
$admin_page = 'users_and_groups';
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<meta name="robots" content="noindex">

	<?php 
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php'); 
	?>
  
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    
  </head>

  <body>
  <?php
  require('modules/Core/pages/admin/navbar.php');
  ?>
    <div class="container">
	  <div class="row">
		<div class="col-md-3">
		  <?php require('modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <ul class="nav nav-pills">
				<li class="nav-item">
				  <a class="nav-link" href="<?php echo URL::build('/admin/users'); ?>"><?php echo $language->get('admin', 'users'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link active"><?php echo $language->get('admin', 'groups'); ?></a>
				</li>
			  </ul>
		      <hr />
			  <h3 style="display:inline"><?php echo $language->get('admin', 'groups'); ?></h3>
			  
			  <?php
				if(Session::exists('adm-groups')){
					echo Session::flash('adm-groups');
				}

				if(!isset($_GET["action"]) && !isset($_GET["group"])){
			  ?>
			  <span class="pull-right">
			    <a href="<?php echo URL::build('/admin/groups/', 'action=new'); ?>" class="btn btn-primary"><?php echo $language->get('admin', 'new_group'); ?></a>
			  </span>
			  <br /><br />
			  <?php 
			    $groups = $queries->getAll("groups", array("id", "<>", 0));
			  ?>
			  <table class="table table-bordered">
				<thead>
				  <tr>
					<th><?php echo $language->get('admin', 'group_id'); ?></th>
					<th><?php echo $language->get('admin', 'name'); ?></th>
					<th><?php echo $language->get('admin', 'users'); ?></th>
				  </tr>
				</thead>
				<tbody>
			  <?php 
				foreach($groups as $group){
			  ?>
				  <tr>
					<td><?php echo $group->id; ?></td>
					<td><a href="<?php echo URL::build('/admin/groups/', 'group=' . $group->id); ?>"><?php echo Output::getClean($group->name); ?></a></td>
					<td><?php echo count($queries->getWhere('users', array('group_id', '=', $group->id))); ?></td>
				  </tr>
			  <?php 
				}
			  ?>
			  </tbody>
			</table>
			  <?php
				} else if(isset($_GET["action"])){
					if($_GET["action"] === "new"){
						if(Input::exists()) {
							if(Token::check(Input::get('token'))) {
								$validate = new Validate();
								$validation = $validate->check($_POST, array(
									'groupname' => array(
										'required' => true,
										'min' => 2,
										'max' => 20
									),
									'html' => array(
										'max' => 1024
									),
									'html_lg' => array(
										'max' => 1024
									)
								));
								
								if($validation->passed()){
									try {
										
										if(isset($_POST['html']) && !empty($_POST['html'])) $group_html = Input::get('html');
										else $group_html = Output::getClean(Input::get('groupname'));

										if(isset($_POST['html_lg']) && !empty($_POST['html_lg'])) $group_html_lg = Input::get('html_lg');
										else $group_html_lg = Output::getClean(Input::get('groupname'));
										
										$queries->create('groups', array(
											'name' => Output::getClean(Input::get('groupname')),
											'group_html' => $group_html,
											'group_html_lg' => $group_html_lg
										));

										Redirect::to(URL::build('/admin/groups/', 'group=' . $queries->getLastID()));
										die();
									
									} catch(Exception $e){
										die($e->getMessage());
									}
								}
							}						
						}
						
						// Generate token for form
						$token = Token::get();
						
						if(isset($validation)){
							if(!$validation->passed()){
						?>
						<div class="alert alert-danger">
							<?php
							foreach($validation->errors() as $error){
								if(strpos($error, 'is required') !== false){
									echo $language->get('admin', 'group_name_required');
								} else if(strpos($error, 'minimum') !== false){
									echo $language->get('admin', 'group_name_minimum');
								} else if(strpos($error, 'maximum') !== false){
									if(strpos($error, 'groupname') !== false){
										echo $language->get('admin', 'group_name_maximum');
									} else {
										echo $language->get('admin', 'group_html_maximum');
									}
								}
							}
							?>
						</div>
						<?php 
							}
						}
						?>
						<span class="pull-right"><a href="<?php echo URL::build('/admin/groups'); ?>" class="btn btn-danger" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a></span>
						<form action="" method="post">
						  <br />
						  
						  <h4><?php echo $language->get('admin', 'creating_group'); ?></h4>
						  
						  <div class="form-group">
							<input class="form-control" type="text" name="groupname" id="groupname" value="<?php echo Output::getClean(Input::get('groupname')); ?>" placeholder="<?php echo $language->get('admin', 'name'); ?>" autocomplete="off">
						  </div>
						  
						  <div class="form-group">
							<input class="form-control" type="text" name="html" id="html" value="<?php echo Input::get('html'); ?>" placeholder="<?php echo $language->get('admin', 'group_html'); ?>">
						  </div>
						  
						  <div class="form-group">
							<input class="form-control" type="text" name="html_lg" id="html_lg" value="<?php echo Input::get('html_lg'); ?>" placeholder="<?php echo $language->get('admin', 'group_html_lg'); ?>">
						  </div>
						  
						  <input type="hidden" name="token" value="<?php echo $token; ?>">
						  <input class="btn btn-success" type="submit" value="<?php echo $language->get('general', 'submit'); ?>">
						</form>
						
						<br />
						
						<?php 
					}
				} else if(isset($_GET["group"])){
					// Verify group is numeric ID
					if(!is_numeric($_GET['group'])){
						Redirect::to(URL::build('/admin/groups'));
						die();
					}
					// Deal with input
					if(Input::exists()){
						// Check token
						if(Token::check(Input::get('token'))){
							// Get action
							if(Input::get('action') == 'update'){
								$validate = new Validate();
								$validation = $validate->check($_POST, array(
									'groupname' => array(
										'required' => true,
										'min' => 2,
										'max' => 20
									),
									'html' => array(
										'max' => 1024
									),
									'html_lg' => array(
										'max' => 1024
									)
								));
								
								if($validation->passed()){
									try {
										$queries->update('groups', $_GET['group'], array(
											'name' => Input::get('groupname'),
											'group_html' => Input::get('html'),
											'group_html_lg' => Input::get('html_lg'),
											'group_username_css' => Input::get('username_style'),
											'mod_cp' => Input::get('modcp'),
											'admin_cp' => Input::get('admincp'),
											'staff' => Input::get('staff')
										));
										
										Redirect::to(URL::build('/admin/groups/', 'group=' . Output::getClean($_GET['group'])));
										die();
									} catch(Exception $e) {
										die($e->getMessage());
									}
									
								} else {
									$error_string = '<div class="alert alert-danger">';
									foreach($validation->errors() as $error) {
										if(strpos($error, 'is required') !== false){
											$error_string .= $language->get('admin', 'group_name_required');
										} else if(strpos($error, 'minimum') !== false){
											$error_string .= $language->get('admin', 'group_name_minimum');
										} else if(strpos($error, 'maximum') !== false){
											switch($error){
												case (strpos($error, 'groupname') !== false):
													$error_string .= $language->get('admin', 'group_name_maximum') . '<br />';
												break;
												case (strpos($error, 'html') !== false):
													$error_string .= $language->get('admin', 'html_maximum') . '<br />';
												break;
											}
										}
									}
									$error_string .= '</div>';
								}
							} else if(Input::get('action') == 'delete'){
								try {
									// Ensure group is not default/admin
									$group = $queries->getWhere('groups', array('id', '=', Input::get('id')));
									
									if(count($group)){
										if($group[0]->id == 1 || $group[0]->admin_cp == 1){
											// Can't delete default group/admin group
										} else
											$queries->delete('groups', array('id', '=' , Input::get('id')));
									}
									
									Redirect::to(URL::build('/admin/groups'));
									die();
								} catch(Exception $e) {
									die($e->getMessage());
								}				
							}
						}
					}
					
					// Generate token for form
					$token = Token::get();
					
					if(!is_numeric($_GET["group"])){
						$group = $queries->getWhere("groups", array("name", "=", $_GET["group"]));
					} else {
						$group = $queries->getWhere("groups", array("id", "=", $_GET["group"]));
					}
					if(count($group)){
					    echo '<span class="pull-right"><a href="' . URL::build('/admin/groups') . '" class="btn btn-danger">'  . $language->get('general', 'back') . '</a></span>';
						echo '<br /><br /><h4>' . Output::getClean($group[0]->name) . '</h4>';
						if(isset($error_string)) echo $error_string;
						?>
					<form role="form" action="" method="post">
					  <div class="form-group">
						<label for="InputGroupname"><?php echo $language->get('admin', 'name'); ?></label>
						<input type="text" name="groupname" class="form-control" id="InputGroupname" placeholder="<?php echo $language->get('admin', 'name'); ?>" value="<?php echo Output::getClean($group[0]->name); ?>">
					  </div>
					  <div class="form-group">
						<label for="InputHTML"><?php echo $language->get('admin', 'group_html'); ?></label>
						<input type="text" name="html" class="form-control" id="InputHTML" placeholder="<?php echo $language->get('admin', 'group_html'); ?>" value="<?php echo Output::getClean($group[0]->group_html); ?>">
					  </div>
					  <div class="form-group">
						<label for="InputHTML_Lg"><?php echo $language->get('admin', 'group_html_lg'); ?></label>
						<input type="text" name="html_lg" class="form-control" id="InputHTML_Lg" placeholder="<?php echo $language->get('admin', 'group_html_lg'); ?>" value="<?php echo Output::getClean($group[0]->group_html_lg); ?>">
					  </div>
					  <div class="form-group groupColour">
						<label for="InputColour"><?php echo $language->get('admin', 'group_username_colour'); ?></label>
						<div class="input-group">
						  <input type="text" name="username_style" class="form-control" id="InputColour" value="<?php echo Output::getClean($group[0]->group_username_css); ?>">
						  <span class="input-group-addon"><i></i></span>
						</div>
					  </div>
					  <div class="form-group">
						<label for="InputStaff"><?php echo $language->get('admin', 'group_staff'); ?></label>
						<input type="hidden" name="staff" value="0">
						<input type="checkbox" name="staff" class="js-switch" id="InputStaff" value="1" <?php if($group[0]->staff == 1){ ?> checked<?php } ?>>
					  </div>
					  <div class="form-group">
						<label for="InputModCP"><?php echo $language->get('admin', 'group_modcp'); ?></label>
						<input type="hidden" name="modcp" value="0">
						<input type="checkbox" name="modcp" class="js-switch" id="InputModCP" value="1" <?php if($group[0]->mod_cp == 1){ ?> checked<?php } ?>>
					  </div>
					  <div class="form-group">
						<label for="InputAdminCP"><?php echo $language->get('admin', 'group_admincp'); ?></label>
						<input type="hidden" name="admincp" value="0">
						<input type="checkbox" name="admincp" class="js-switch" id="InputAdminCP" value="1" <?php if($group[0]->admin_cp == 1){ ?> checked<?php } ?>>
					  </div>
					  <input type="hidden" name="token" value="<?php echo $token; ?>">
					  <input type="hidden" name="action" value="update">
					  <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>" class="btn btn-success">
					</form>
					<?php 
					if($group[0]->id == 1 || $group[0]->admin_cp == 1){
						// Can't delete basic member group or admin group
					} else {
					?>
					<br />
					<form role="form" action="" method="post">
					  <p><strong><?php echo $language->get('general', 'actions'); ?></strong></p>
					  <div class="form-group">
					    <input type="hidden" name="token" value="<?php echo $token; ?>">
					    <input type="hidden" name="action" value="delete">
					    <input type="hidden" name="id" value="<?php echo $group[0]->id; ?>">
					    <input onclick="return confirm('<?php echo str_replace('{x}', Output::getClean($group[0]->name), $language->get('admin', 'confirm_group_deletion')); ?>');" type="submit" value="<?php echo $language->get('admin', 'delete_group'); ?>" class="btn btn-danger">
					  </div>
					</form>
						<?php 
						}
					} else {
						Session::flash('adm-groups', '<div class="alert alert-info">' . $language->get('admin', 'group_not_exist') . '</div>');
						Redirect::to(URL::build('/admin/groups'));
						die();
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
	</script>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

	<script>
		$(function(){
			$('.groupColour').colorpicker({
				'color': <?php if(isset($_GET['group']) && !isset($_GET['action']) && $group[0]->group_username_css != null){ echo '\'' . $group[0]->group_username_css . '\''; } else { ?>false<?php } ?>
			});
		});
	</script>
  </body>
</html>