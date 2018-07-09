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
	$title = $admin_language['groups'];

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
	// "Groups" page
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
			<li><a href="/admin/users"><?php echo $admin_language['users']; ?></a></li>
			<li class="active"><a href="/admin/groups"><?php echo $admin_language['groups']; ?></a></li>
		  </ul>

		  <hr>

		  <div class="well well-sm">
			<?php
				if(Session::exists('adm-groups')){
					echo Session::flash('adm-groups');
				}
			?>
			<?php
			if(!isset($_GET["action"]) && !isset($_GET["group"])){
			?>
			<a href="/admin/groups/?action=new" class="btn btn-default"><?php echo $admin_language['new_group']; ?></a>
			<br /><br />
			<?php
			$groups = $queries->getAll("groups", array("id", "<>", 0));
			?>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th><?php echo $admin_language['id']; ?></th>
						<th><?php echo $admin_language['name']; ?></th>
						<th><?php echo $admin_language['users']; ?></th>
					</tr>
				</thead>
				<tbody>
			<?php
			foreach($groups as $group){
			?>
					<tr>
						<td><?php echo $group->id; ?></td>
						<td><a href="/admin/groups/?group=<?php echo $group->id; ?>"><?php echo $group->name; ?></a></td>
						<td><?php echo count($queries->getWhere("users", array("group_id", "=", $group->id))); ?></td>
					</tr>
			<?php
			}
			?>
				</tbody>
			</table>
		  </div>
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
								)
							));

							if($validation->passed()){
								try {
									$queries->create("groups", array(
										'name' => htmlspecialchars(Input::get('groupname')),
										'group_html' => '',
										'group_html_lg' => '',
										'buycraft_id' => htmlspecialchars(Input::get('buycraft_id'))
									));

									echo '<script data-cfasync="false">window.location.replace("/admin/groups");</script>';
									die();

								} catch(Exception $e){
									die($e->getMessage());
								}
							}
						}
					}

					// Generate token for form
					$token = Token::generate();

					if(isset($validation)){
						if(!$validation->passed()){
					?>
					<div class="alert alert-danger">
						<?php
						foreach($validation->errors() as $error){
							if(strpos($error, 'is required') !== false){
								echo $admin_language['group_name_required'];
							} else if(strpos($error, 'minimum') !== false){
								echo $admin_language['group_name_minimum'];
							} else if(strpos($error, 'maximum') !== false){
								echo $admin_language['group_name_maximum'];
							}
						}
						?>
					</div>
					<?php
						}
					}
					?>
					<form action="" method="post">
						<h2><?php echo $admin_language['create_group']; ?></h2>
						<div class="form-group">
							<input class="form-control" type="text" name="groupname" id="groupname" value="<?php echo escape(Input::get('groupname')); ?>" placeholder="<?php echo $admin_language['group_name']; ?>" autocomplete="off">
						</div>
						<div class="input-group">
							<input class="form-control" type="text" name="buycraft_id" id="buycraft_id" placeholder="<?php echo $admin_language['donor_group_id']; ?>"> <span class="input-group-addon" ><a href="#" style="color:#000;" data-toggle="modal" data-target="#donor_package_help"><i class="fa fa-question-circle"></i></a></span>
						</div>
						<br />
						<input type="hidden" name="token" value="<?php echo $token; ?>">
						<input class="btn btn-success" type="submit" value="<?php echo $general_language['submit']; ?>">
					</form>
					<br />
					<div class="alert alert-info">
						<?php echo $admin_language['donor_group_instructions']; ?>
					</div>
					<div class="modal fade" id="donor_package_help" tabindex="-1" role="dialog" aria-labelledby="donor_package_ModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="donor_package_ModalLabel"><?php echo $general_language['help']; ?></h4>
						  </div>
						  <div class="modal-body">
							<?php echo $admin_language['donor_group_id_help']; ?>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $general_language['close']; ?></button>
						  </div>
						</div>
					  </div>
					</div>
					<?php
				}
			} else if(isset($_GET["group"])){
				if(Input::exists()) {
					if(Token::check(Input::get('token'))) {
						if(Input::get('action') === "update"){
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
									$queries->update('groups', $_GET["group"], array(
										'name' => Input::get('groupname'),
										'buycraft_id' => Input::get('buycraft_id'),
										'group_html' => Input::get('html'),
										'group_html_lg' => Input::get('html_lg'),
										'mod_cp' => Input::get('modcp'),
										'admin_cp' => Input::get('admincp'),
										'staff' => Input::get('staff')
									));

									echo '<script data-cfasync="false">window.location.replace("/admin/groups/?group=' . htmlspecialchars($_GET['group'], ENT_QUOTES) . '");</script>';
									die();
								} catch(Exception $e) {
									die($e->getMessage());
								}

							} else {
								echo '<div class="alert alert-danger">';
								foreach($validation->errors() as $error) {
									if(strpos($error, 'is required') !== false){
										echo $admin_language['group_name_required'];
									} else if(strpos($error, 'minimum') !== false){
										echo $admin_language['group_name_minimum'];
									} else if(strpos($error, 'maximum') !== false){
										switch($error){
											case (strpos($error, 'groupname') !== false):
												echo $admin_language['group_name_maximum'] . '<br />';
											break;
											case (strpos($error, 'html') !== false):
												echo $admin_language['html_maximum'] . '<br />';
											break;
										}
									}
								}
								echo '</div>';
							}
						} else if(Input::get('action') == "delete"){
							try {
								$queries->delete('groups', array('id', '=' , Input::get('id')));
								echo '<script data-cfasync="false">window.location.replace("/admin/groups/");</script>';
								die();
							} catch(Exception $e) {
								die($e->getMessage());
							}
						}
					}
				}

				// Generate token for form
				$token = Token::generate();

				if(!is_numeric($_GET["group"])){
					$group = $queries->getWhere("groups", array("name", "=", $_GET["group"]));
				} else {
					$group = $queries->getWhere("groups", array("id", "=", $_GET["group"]));
				}
				if(count($group)){
					echo '<h2>' . htmlspecialchars($group[0]->name) . '</h2>';
					?>
					<form role="form" action="" method="post">
					  <div class="form-group">
						<label for="InputGroupname"><?php echo $admin_language['group_name']; ?></label>
						<input type="text" name="groupname" class="form-control" id="InputGroupname" placeholder="<?php echo $admin_language['group_name']; ?>" value="<?php echo htmlspecialchars($group[0]->name); ?>">
					  </div>
					  <div class="form-group">
						<label for="InputHTML"><?php echo $admin_language['group_html']; ?></label>
						<input type="text" name="html" class="form-control" id="InputHTML" placeholder="<?php echo $admin_language['group_html']; ?>" value="<?php echo htmlspecialchars($group[0]->group_html); ?>">
					  </div>
					  <div class="form-group">
						<label for="InputHTML_Lg"><?php echo $admin_language['group_html_lg']; ?></label>
						<input type="text" name="html_lg" class="form-control" id="InputHTML_Lg" placeholder="<?php echo $admin_language['group_html_lg']; ?>" value="<?php echo htmlspecialchars($group[0]->group_html_lg); ?>">
					  </div>
					  <div class="form-group">
						<label for="InputStaff"><?php echo $admin_language['group_staff']; ?></label>
						<input type="hidden" name="staff" value="0">
						<input type="checkbox" name="staff" id="InputStaff" placeholder="<?php echo $admin_language['group_staff']; ?>" value="1" <?php if($group[0]->staff == 1){ ?> checked<?php } ?>>
					  </div>
					  <div class="form-group">
						<label for="InputModCP"><?php echo $admin_language['group_modcp']; ?></label>
						<input type="hidden" name="modcp" value="0">
						<input type="checkbox" name="modcp" id="InputModCP" placeholder="<?php echo $admin_language['group_modcp']; ?>" value="1" <?php if($group[0]->mod_cp == 1){ ?> checked<?php } ?>>
					  </div>
					  <div class="form-group">
						<label for="InputAdminCP"><?php echo $admin_language['group_admincp']; ?></label>
						<input type="hidden" name="admincp" value="0">
						<input type="checkbox" name="admincp" id="InputAdminCP" placeholder="<?php echo $admin_language['group_admincp']; ?>" value="1" <?php if($group[0]->admin_cp == 1){ ?> checked<?php } ?>>
					  </div>
					  <?php
					  if($group[0]->staff == 1){} else {
					  ?>
					  <div class="form-group">
						<label for="InputBuycraft"><?php echo $admin_language['donor_group_id']; ?></label>
						<input type="text" name="buycraft_id" class="form-control" id="InputBuycraft" placeholder="<?php echo $admin_language['donor_group_id']; ?>" value="<?php echo htmlspecialchars($group[0]->buycraft_id); ?>">
					  </div>
					  <?php
					  }
					  ?>
					  <input type="hidden" name="token" value="<?php echo $token; ?>">
					  <input type="hidden" name="action" value="update">
					  <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-default">
					</form>
					<?php
					if($group[0]->id == 2 || $group[0]->id == 3 || $group[0]->id == 1){} else {
					?>
					<br />
					<form role="form" action="" method="post">
					  <input type="hidden" name="token" value="<?php echo $token; ?>">
					  <input type="hidden" name="action" value="delete">
					  <input type="hidden" name="id" value="<?php echo $group[0]->id; ?>">
					  <input onclick="return confirm('<?php echo str_replace('{x}', htmlspecialchars($group[0]->name), $admin_language['confirm_group_deletion']); ?>');" type="submit" value="<?php echo $admin_language['delete_group']; ?>" class="btn btn-danger">
					</form>
					<?php
					}
				} else {
					Session::flash('adm-groups', '<div class="alert alert-info">' . $admin_language['group_not_exist'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace("/admin/groups/");</script>';
					die();
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
</html>
