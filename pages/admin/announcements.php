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
 
$adm_page = 'announcements';

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier
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
	<script>var groups = [];</script>
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $admin_language['announcements'];
	
	require('core/includes/template/generate.php');
	?>
	
	<link href="/core/assets/plugins/switchery/switchery.min.css" rel="stylesheet">	
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>
  <body>
	<?php
	// Announcements page
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
    <div class="container">
	  <br />
	  <div class="row">
	    <div class="col-md-3">
		  <?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="well">
		    <br />
			<h3 style="display:inline;"><?php echo $admin_language['announcements']; ?></h3>
			<?php if(!isset($_GET['action'])){ ?>
			<span class="pull-right"><a class="btn btn-primary" href="/admin/announcements/?action=create"><?php echo $admin_language['create_announcement']; ?></a></span>
			<br /><br />
			<?php if(Session::exists('announcements')) echo Session::flash('announcements'); ?>
			<div class="panel panel-primary">
			  <div class="panel-heading"><?php echo $admin_language['current_announcements']; ?></div>
			  <div class="panel-body">
			    <div class="table-responsive">
			      <table class="table table-striped">
				    <colgroup>
				      <col span="1" style="width: 50%;">
					  <col span="1" style="width: 20%;">
					  <col span="1" style="width: 20%">
					  <col span="1" style="width: 10%">
				    </colgroup>
				    <thead>
				      <tr>
					    <td><?php echo $admin_language['announcement_content']; ?></td>
					    <td><?php echo $admin_language['announcement_location']; ?></td>
					    <td><?php echo $admin_language['announcement_permissions']; ?></td>
						<td><?php echo $admin_language['announcement_actions']; ?></td>
				      </tr>
				    </thead>
				    <tbody>
					<?php
					// Get announcements
					$announcements = $queries->getWhere('announcements', array('id', '<>', 0));
					if(count($announcements)){
						// HTML Purifier
						$config = HTMLPurifier_Config::createDefault();
						$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
						$config->set('URI.DisableExternalResources', false);
						$config->set('HTML.Allowed', 'u,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
						$config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
						$config->set('HTML.AllowedAttributes', 'height, width, alt, class, *.style, frameborder');
						$purifier = new HTMLPurifier($config);
						
						foreach($announcements as $announcement){
						?>
				      <tr>
					    <td><div class="alert alert-<?php echo htmlspecialchars($announcement->type); ?>"><?php echo $purifier->purify(htmlspecialchars_decode($announcement->content)); ?></div></td>
						<td><?php
						// Get locations
						$locations = $queries->getWhere('announcements_pages', array('announcement_id', '=', $announcement->id));
						foreach($locations as $location){
							echo ucfirst(htmlspecialchars($location->page)) . '<br />';
						}
						?></td>
						<td><?php
						// Get groups
						$groups = $queries->getWhere('announcements_permissions', array('announcement_id', '=', $announcement->id));
						foreach($groups as $group){
							if($group->group_id == 0 && $group->view == 1){
								echo $general_language['guest'] . '<br />';
							} else if($group->group_id != 0 && $group->view == 1) {
								$group_name = $queries->getWhere('groups', array('id', '=', $group->group_id));
								echo htmlspecialchars($group_name[0]->name) . '<br />';
							}
						}
						?></td>
						<td><a href="/admin/announcements/?action=delete&amp;id=<?php echo $announcement->id; ?>" onclick="return confirm('<?php echo $admin_language['confirm_delete_announcement']; ?>')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a></td>
					  </tr>
						<?php } ?>
				    </tbody>
					<?php
					} else echo $admin_language['no_announcements'];
					?>
				  </table>
				</div>
			  </div>
			</div>
			<?php 
			} else {
				if($_GET['action'] == 'create'){
					// New announcement
					// Deal with input
					if(Input::exists()){
						if(Token::check(Input::get('token'))){
							// Valid token
							$validate = new Validate();
							$validation = $validate->check($_POST, array(
								'content' => array(
									'required' => true,
									'max' => 20480
								),
								'label_type' => array(
									'required' => true
								)
							));
							
							if($validation->passed()){
								try {
									// Create announcement
									if(isset($_POST['can_close']) && $_POST['can_close'] == 1) $can_close = 1;
									else $can_close = 0;
									
									$queries->create('announcements', array(
										'content' => htmlspecialchars(Input::get('content')),
										'can_close' => $can_close,
										'type' => htmlspecialchars(Input::get('label_type'))
									));
									
									$announcement_id = $queries->getLastID();
									
									// Enabled pages
									if(count($_POST['pages'])){
										foreach($_POST['pages'] as $page){
											$queries->create('announcements_pages', array(
												'announcement_id' => $announcement_id,
												'page' => htmlspecialchars($page)
											));
										}
									}
									
									// Permissions
									// Guests first
									$view = Input::get('perm-view-0');
									$queries->create('announcements_permissions', array(
										'group_id' => 0,
										'announcement_id' => $announcement_id,
										'view' => $view
									));
									
									// Groups
									$groups = $queries->getWhere('groups', array('id', '<>', 0));
									foreach($groups as $group){ 
										$view = Input::get('perm-view-' . $group->id);
										$queries->create('announcements_permissions', array(
											'group_id' => $group->id,
											'announcement_id' => $announcement_id,
											'view' => $view
										));
									}
									
									Session::flash('announcements', '<div class="alert alert-success">' . $admin_language['announcement_created'] . '</div>');
									echo '<script data-cfasync="false">window.location.replace("/admin/announcements");</script>';
									die();
									
								} catch(Exception $e){
									die($e->getMessage());
								}
							} else {
								$error = $admin_language['please_input_announcement_content'];
							}
						} else {
							$error = $admin_language['invalid_token']; 
						}
					}
					
					$token = Token::generate();
					?>
			<br /><br />
			<form action="" method="post">
			  <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
			  <strong><?php echo $admin_language['announcement_content']; ?></strong><br />
			  <textarea rows="3" name="content" id="content_editor">
			  <?php 
			  if(Input::exists()){
				  // Initialise HTML Purifier
				  $config = HTMLPurifier_Config::createDefault();
				  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
				  $config->set('URI.DisableExternalResources', false);
				  $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
				  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
				  $config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style, frameborder');
				  $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
				  $purifier = new HTMLPurifier($config);
				  echo $purifier->purify(Input::get('content')); 
			  }
			  ?>
			  </textarea>
			  <br />
			  <div class="form-group">
				<label for="InputCanClose"><?php echo $admin_language['announcement_can_close']; ?></label>
				<input id="InputCanClose" name="can_close" type="checkbox" class="js-switch" value="1" />
			  </div>
			  <div class="form-group">
				<label><?php echo $admin_language['announcement_type']; ?></label>
				<div class="row">
				  <div class="col-md-3">
					<input type="radio" name="label_type" id="label_type" value="success"> <span class="label label-success"><?php echo $admin_language['label_success']; ?></span><br />
				  </div>
				  <div class="col-md-3">
					<input type="radio" name="label_type" id="label_type" value="info"> <span class="label label-info"><?php echo $admin_language['label_info']; ?></span><br />
				  </div>
				  <div class="col-md-3">
					<input type="radio" name="label_type" id="label_type" value="warning"> <span class="label label-warning"><?php echo $admin_language['label_warning']; ?></span><br />
				  </div>
				  <div class="col-md-3">
					<input type="radio" name="label_type" id="label_type" value="danger"> <span class="label label-danger"><?php echo $admin_language['label_danger']; ?></span><br />
				  </div>
				</div>
			  </div>
			  <div class="form-group">
			    <label for="InputPages"><?php echo $admin_language['announcement_location']; ?></label> <a class="btn btn-info btn-xs" data-toggle="popover" data-content="<?php echo $admin_language['announcement_location_help']; ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
				<span class="pull-right"><a id="select_all" class="btn btn-primary btn-xs"><?php echo $admin_language['select_all']; ?></a> <a id="deselect_all" class="btn btn-warning btn-xs"><?php echo $admin_language['deselect_all']; ?></a></span>
				<select name="pages[]" id="pages" multiple class="form-control">
				  <option value="index"><?php echo $navbar_language['home']; ?></option>
				  <option value="forum"><?php echo $navbar_language['forum']; ?></option>
				  <option value="play"><?php echo $navbar_language['play']; ?></option>
				  <?php if(isset($donate_language)){ ?><option value="donate"><?php echo $donate_language['donate']; ?></option><?php } ?>
				  <?php if(isset($vote_language)){ ?><option value="vote"><?php echo $vote_language['vote']; ?></option><?php } ?>
				  <?php if(isset($infractions_language)){ ?><option value="infractions"><?php echo $infractions_language['infractions']; ?></option><?php } ?>
				  <?php if(isset($members_language)){ ?><option value="members"><?php echo $members_language['members']; ?></option><?php } ?>
				  <?php if(isset($stats_language)){ ?><option value="stats"><?php echo $stats_language['stats']; ?></option><?php } ?>
				</select>
			  </div>
			  <div class="form-group">
				<strong><?php echo $admin_language['announcement_permissions']; ?></strong><br />
				<strong><?php echo $general_language['guests']; ?>:</strong><br />
				<div class="row">
					<div class="col-md-8">
						<table class="table">
							<thead>
							  <tr>
								<th></th>
								<th></th>
							  </tr>
							</thead>
							<tbody>
							  <tr>
								<td><input type="hidden" name="perm-view-0" value="0" />
									<label for="Input-view-0"><?php echo $admin_language['can_view_announcement']; ?></label></td>
								<td class="info"> <input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"></td>
							  </tr>
							</tbody>
						</table>
					</div>
				</div>
				<script>groups.push("0");</script>
				<br />
				<?php
				$groups = $queries->getWhere('groups', array('id', '<>', 0));
				foreach($groups as $group){
					// Get the existing group permissions
				?>
				<strong onclick="toggle(<?php echo "'" . $group->id . "'"; ?>)"><?php echo htmlspecialchars($group->name); ?>:</strong><br />
				<div class="row">
					<div class="col-md-8">
						<table class="table">
							<thead>
							  <tr>
								<th></th>
								<th></th>
							  </tr>
							</thead>
							<tbody>
							  <tr>
								<td><input type="hidden" name="perm-view-<?php echo $group->id; ?>" value="0" />
									<label for="Input-view-<?php echo $group->id; ?>"><?php echo $admin_language['can_view_announcement']; ?></label></td>
								<td class="info"> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"></td>
							  </tr>
							</tbody>
						</table>
					</div>
				</div>
				<script>groups.push("<?php echo $group->id; ?>");</script>
				<?php
				}
				?>
			  </div>
			  <input type="hidden" name="token" value="<?php echo $token; ?>">
			  <br /><br />
			  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			  <a href="/admin/announcements" onclick="return confirm('<?php echo $admin_language['confirm_cancel_announcement']; ?>')" class="btn btn-danger"><?php echo $general_language['cancel']; ?></a>
			</form>
					<?php
				} else if($_GET['action'] == 'delete'){
					if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
						echo 'window.location.replace("/admin/announcements");';
						die();
					}
					
					// Delete
					$queries->delete('announcements', array('id', '=', $_GET['id']));
					$queries->delete('announcements_permissions', array('announcement_id', '=', $_GET['id']));
					$queries->delete('announcements_pages', array('announcement_id', '=', $_GET['id']));
					
					Session::flash('announcements', '<div class="alert alert-info">' . $admin_language['announcement_deleted'] . '</div>');
					echo '<script>window.location.replace("/admin/announcements");</script>';
					die();
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
	<script src="/core/assets/js/ckeditor.js"></script>
	<script type="text/javascript">
		CKEDITOR.replace( 'content_editor', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"links","groups":["links"]},
				{"name":"colors","groups":["colors"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash'
		} );
		CKEDITOR.config.disableNativeSpellChecker = false;
	</script>
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

			colourUpdate(document.getElementById('Input-view-' + group));
		}
		for(var g in groups) {
			colourUpdate(document.getElementById('Input-view-' + groups[g]));
		}
    </script>
	<script src="/core/assets/plugins/switchery/switchery.min.js"></script>
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  var switchery = new Switchery(html, {size: 'small'});
	});
	
	$('#select_all').click(function() {
		$('#pages option').prop('selected', true);
	});
	
	$('#deselect_all').click(function() {
		$('#pages option').prop('selected', false);
	});
	</script>
  </body>
</html>
