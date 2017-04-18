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

$adm_page = "custom_pages"; // For admin sidebar

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

if(!isset($_GET['action'])){
	// Get a list of all groups
	$groups = $queries->getWhere('groups', array('id', '<>', '0'));

	// Deal with input for editing pages only
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'url' => array(
					'required' => true,
					'min' => 1,
					'max' => 20
				),
				'title' => array(
					'required' => true,
					'min' => 1,
					'max' => 30
				),
                'icon' => array(
                    'max' => 50
                ),
				'content' => array(
					'max' => 20480
				),
				'link_location' => array(
					'required' => true
				),
				'redirect_link' => array(
					'max' => 512
				)
			));

			if($validation->passed()){
				try {
					// Is redirect enabled, and is a link set?
					if(isset($_POST['redirect_page']) && $_POST['redirect_page'] == 'on') $redirect = 1;
					else $redirect = 0;

					if(isset($_POST['redirect_link'])) $link = $_POST['redirect_link'];
					else $link = '';

					$queries->update("custom_pages", $_GET["page"], array(
						"url" => htmlspecialchars(Input::get('url')),
						"title" => htmlspecialchars(Input::get('title')),
                        "icon" => htmlspecialchars_decode(Input::get('icon')),
						"content" => htmlspecialchars(Input::get('content')),
						"link_location" => Input::get('link_location'),
						'redirect' => $redirect,
						'link' => htmlspecialchars($link)
					));

					// Permissions
					// Guests first
					$view = Input::get('perm-view-0');

					$page_perm_exists = 0;

					$page_perm_query = $queries->getWhere('custom_pages_permissions', array('page_id', '=', $_GET["page"]));
					if(count($page_perm_query)){
						foreach($page_perm_query as $query){
							if($query->group_id == 0){
								$page_perm_exists = 1;
								$update_id = $query->id;
								break;
							}
						}
					}

					if($page_perm_exists != 0){ // Permission already exists, update
						// Update the permission
						$queries->update('custom_pages_permissions', $update_id, array(
							'view' => $view
						));
					} else { // Permission doesn't exist, create
						$queries->create('custom_pages_permissions', array(
							'group_id' => 0,
							'page_id' => $_GET["page"],
							'view' => $view
						));
					}

					// Groups
					foreach($groups as $group){
						$view = Input::get('perm-view-' . $group->id);

						$page_perm_exists = 0;

						if(count($page_perm_query)){
							foreach($page_perm_query as $query){
								if($query->group_id == $group->id){
									$page_perm_exists = 1;
									$update_id = $query->id;
									break;
								}
							}
						}

						if($page_perm_exists != 0){ // Permission already exists, update
							// Update the forum
							$queries->update('custom_pages_permissions', $update_id, array(
								'view' => $view
							));

						} else { // Permission doesn't exist, create
							$queries->create('custom_pages_permissions', array(
								'group_id' => $group->id,
								'page_id' => $_GET["page"],
								'view' => $view
							));
						}
					}

				} catch(Exception $e){
					die($e->getMessage());
				}

				Session::flash('custom-pages', '<div class="alert alert-info">' . $admin_language['page_successfully_edited'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/pages\');</script>';
				die();

			} else {
				$error = '<div class="alert alert-warning"><p><strong>' . $admin_language['unable_to_edit_page'] . '</strong></p><p>' . $admin_language['create_page_error'] . '</p></div>';
			}
		} else {
			$error = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
		}
	}
} else if(isset($_GET['action']) && $_GET['action'] == 'new'){
	// Deal with input for creating new pages
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			// Valid token
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'url' => array(
					'required' => true,
					'min' => 1,
					'max' => 20
				),
				'title' => array(
					'required' => true,
					'min' => 1,
					'max' => 30
				),
                		'icon' => array(
                		 	'max' => 50
                		 ),
				'content' => array(
					'max' => 20480
				),
				'link_location' => array(
					'required' => true
				),
				'redirect_link' => array(
					'max' => 512
				)
			));

			if($validation->passed()){
				// Go ahead and input the page data
				try {
					// Is redirect enabled, and is a link set?
					if(isset($_POST['redirect_page']) && $_POST['redirect_page'] == 'on') $redirect = 1;
					else $redirect = 0;

					if(isset($_POST['redirect_link'])) $link = $_POST['redirect_link'];
					else $link = '';

					$queries->create("custom_pages", array(
						"url" => htmlspecialchars(Input::get('url')),
						"title" => htmlspecialchars(Input::get('title')),
                        "icon" => htmlspecialchars_decode(Input::get('icon')),
						"content" => htmlspecialchars(Input::get('content')),
						"link_location" => Input::get('link_location'),
						'redirect' => $redirect,
						'link' => htmlspecialchars($link)
					));

					$page_id = $queries->getLastId();

				} catch(Exception $e){
					die($e->getMessage());
				}

				Session::flash('custom-pages', '<div class="alert alert-info">' . $admin_language['page_successfully_created'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/pages/?page=' . $page_id . '\');</script>';
				die();
			} else {
				$error = '<div class="alert alert-warning"><p><strong>' . $admin_language['unable_to_create_page'] . '</strong></p><p>' . $admin_language['create_page_error'] . '</p></div>';
			}
		} else {
			// Invalid token
			$error = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
		}
	}
}

$token = Token::generate(); // generate token
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
	$title = $admin_language['custom_pages'];

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
	// Custom pages page
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
				<?php if(!isset($_GET["page"]) && !isset($_GET['action'])) { ?>
				<h2 style="display:inline;"><?php echo $admin_language['custom_pages']; ?></h2>
				<span class="pull-right"><a href="/admin/pages/?action=new" class="btn btn-primary"><?php echo $admin_language['new_page']; ?></a></span>
				<br /><br />
				<?php
				if(Session::exists('custom-pages')){
					echo Session::flash('custom-pages');
				}
				?>
				<?php echo $admin_language['click_on_page_to_edit']; ?><br /><br />
				<?php
				$pages = $queries->getAll("custom_pages", array("id", "<>", "0"));
				foreach($pages as $page){
				?>
				<a href="/admin/pages/?page=<?php echo $page->id; ?>"><?php echo htmlspecialchars($page->title); ?></a><br />
				<?php
				}
				?>
				<?php
				} else if(isset($_GET['page']) && !isset($_GET['action'])) {
					$page = $queries->getWhere("custom_pages", array("id", "=", $_GET["page"]));
					if(!count($page)){
						echo '<script data-cfasync="false">window.location.replace(\'/admin/pages\');</script>';
						die();
					}
				?>
				<h2 style="display:inline;"><?php echo $admin_language['page']; ?> <?php echo htmlspecialchars($page[0]->title); ?></h2>
				<span class="pull-right"><a onclick="return confirm('<?php echo $admin_language['confirm_delete_page']; ?>');" href="/admin/pages/?action=delete&amp;pid=<?php echo $page[0]->id; ?>" class="btn btn-danger"><?php echo $admin_language['delete_page']; ?></a></span>
				<br /><br />
				<strong><?php echo $admin_language['url']; ?></strong> http://<?php echo $_SERVER['SERVER_NAME'] . htmlspecialchars($page[0]->url); ?><br /><br />
				<?php
				if(Session::exists('custom-pages')){
					echo Session::flash('custom-pages');
				}
				if(isset($error)){
					echo $error;
				}
				?>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="url"><?php echo $admin_language['page_url']; ?> <em><?php echo $admin_language['page_url_example']; ?></em></label>
				    <input class="form-control" type="text" name="url" id="url" value="<?php echo htmlspecialchars($page[0]->url); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="title"><?php echo $admin_language['page_title']; ?></label>
					<input class="form-control" type="text" name="title" id="title" value="<?php echo htmlspecialchars($page[0]->title); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="icon"><?php echo $admin_language['page_icon']; ?></label>
					<input class="form-control" type="text" name="icon" id="icon" value="<?php echo htmlspecialchars($page[0]->icon); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="link_location"><?php echo $admin_language['page_link_location']; ?></label>
					<select class="form-control" id="link_location" name="link_location">
					  <option value="1" <?php if($page[0]->link_location == 1){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_navbar']; ?></option>
					  <option value="2" <?php if($page[0]->link_location == 2){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_more']; ?></option>
					  <option value="3" <?php if($page[0]->link_location == 3){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_footer']; ?></option>
					  <option value="4" <?php if($page[0]->link_location == 4){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_none']; ?></option>
					</select>
				  </div>
				  <strong><?php echo $admin_language['page_content']; ?></strong><br />
				  <textarea rows="10" name="content" id="content_editor">
				  <?php
			      // Initialise HTML Purifier
				  $config = HTMLPurifier_Config::createDefault();
				  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
				  $config->set('URI.DisableExternalResources', false);
				  $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img,div[well]');
				  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
				  $config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style, frameborder');
				  $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
				  $config->set('HTML.SafeIframe', true);
				  $config->set('URI.SafeIframeRegexp', '%%');
				  $config->set('URI.AllowedSchemes', array('http' => true, 'https' => true, 'ts3server' => true));
				  $purifier = new HTMLPurifier($config);
				  echo $purifier->purify(htmlspecialchars_decode($page[0]->content));
				  ?>
				  </textarea>
				  <br />
				  <div class="form-group">
				    <label for="InputRedirectPage"><?php echo $admin_language['redirect_page']; ?></label>
					<input id="InputRedirectPage" name="redirect_page" type="checkbox" class="js-switch"<?php if($page[0]->redirect == 1){ ?> checked<?php } ?> />
				  </div>
				  <div class="form-group">
					<label for="InputRedirectLink"><?php echo $admin_language['redirect_link']; ?></label>
					<input type="text" class="form-control" name="redirect_link" id="InputRedirectLink" value="<?php echo htmlspecialchars($page[0]->link); ?>">
				  </div>
				  <div class="form-group">
					<strong><?php echo $admin_language['page_permissions']; ?></strong><br />
					<?php
					// Get all forum permissions
					$group_perms = $queries->getWhere('custom_pages_permissions', array('page_id', '=', $_GET["page"]));
					?>
					<strong><?php echo $general_language['guests']; ?>:</strong><br />
					<?php
					foreach($group_perms as $group_perm){
						if($group_perm->group_id == 0){
							$view = $group_perm->view;
							break;
						}
					}
					?>
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
										<label for="Input-view-0"><?php echo $admin_language['can_view_page']; ?></label></td>
									<td class="info"> <input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
								  </tr>
								</tbody>
							</table>
						</div>
					</div>
					<script>groups.push("0");</script>
					<br />
					<?php
					foreach($groups as $group){
						// Get the existing group permissions
						$view = 0;

						foreach($group_perms as $group_perm){
							if($group_perm->group_id == $group->id){
								$view = $group_perm->view;
								break;
							}
						}
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
										<label for="Input-view-<?php echo $group->id; ?>"><?php echo $admin_language['can_view_page']; ?></label></td>
									<td class="info"> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
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
				</form>
				<?php
				} else if(isset($_GET['action'])){
					if(!isset($_GET['page']) && $_GET['action'] == 'new'){
						// new page
						?>
				<h2><?php echo $admin_language['new_page']; ?></h2>
				<?php
				if(isset($error)){
					echo $error;
				}
				?>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="url"><?php echo $admin_language['page_url']; ?> <em><?php echo $admin_language['page_url_example']; ?></em></label>
				    <input class="form-control" type="text" name="url" id="url" value="<?php echo htmlspecialchars(Input::get('url')); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="title"><?php echo $admin_language['page_title']; ?></label>
					<input class="form-control" type="text" name="title" id="title" value="<?php echo htmlspecialchars(Input::get('title')); ?>" />
				  </div>
                  <div class="form-group">
                    <label for="icon"><?php echo $admin_language['page_icon']; ?></label>
                    <input class="form-control" type="text" name="icon" id="icon" value="<?php echo htmlspecialchars(Input::get('icon')); ?>" />
                  </div>
				  <div class="form-group">
				    <label for="link_location"><?php echo $admin_language['page_link_location']; ?></label>
					<select class="form-control" id="link_location" name="link_location">
					  <option value="1" selected><?php echo $admin_language['page_link_navbar']; ?></option>
					  <option value="2"><?php echo $admin_language['page_link_more']; ?></option>
					  <option value="3"><?php echo $admin_language['page_link_footer']; ?></option>
					  <option value="4"><?php echo $admin_language['page_link_none']; ?></option>
					</select>
				  </div>
				  <strong><?php echo $admin_language['page_content']; ?></strong><br />
				  <textarea rows="10" name="content" id="content_editor">
				  <?php
			      // Initialise HTML Purifier
				  $config = HTMLPurifier_Config::createDefault();
				  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
				  $config->set('URI.DisableExternalResources', false);
				  $config->set('URI.DisableResources', false);
				  $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img,div[well]');
				  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
				  $config->set('HTML.AllowedAttributes', 'href, src, height, width, alt, class, *.style, frameborder');
				  $config->set('HTML.SafeIframe', true);
				  $config->set('URI.SafeIframeRegexp', '%%');
				  $purifier = new HTMLPurifier($config);
				  echo $purifier->purify(Input::get('content'));
				  ?>
				  </textarea>
				  <br />
				  <div class="form-group">
				    <label for="InputRedirectPage"><?php echo $admin_language['redirect_page']; ?></label>
					<input id="InputRedirectPage" name="redirect_page" type="checkbox" class="js-switch" />
				  </div>
				  <div class="form-group">
					<label for="InputRedirectLink"><?php echo $admin_language['redirect_link']; ?></label>
					<input type="text" class="form-control" name="redirect_link" id="InputRedirectLink">
				  </div>
				  <input type="hidden" name="token" value="<?php echo $token; ?>">
				  <br /><br />
				  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
				</form>
						<?php
					} else if(isset($_GET['action']) && $_GET['action'] == 'delete'){
						// Delete a page
						// Check the page exists
						if(!isset($_GET['pid']) || !is_numeric($_GET['pid'])){
							echo '<script data-cfasync="false">window.location.replace(\'/admin/pages\');</script>';
							die();
						}

						// Try to delete it
						try {
							$queries->delete("custom_pages", array('id', '=', $_GET['pid']));
						} catch(Exception $e){
							die($e->getMessage());
						}

						Session::flash('custom-pages', '<div class="alert alert-info">' . $admin_language['page_deleted_successfully'] . '</div>');
						echo '<script data-cfasync="false">window.location.replace(\'/admin/pages\');</script>';
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
			extraAllowedContent: 'div(panel,panel-*,well)',
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"links","groups":["links"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"insert","groups":["insert"]},
				{"name":"styles","groups":["styles"]},
				{"name":"colors","groups":["colors"]},
				{"name":"about","groups":["about"]},
				{"name":"mode","groups":["mode"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash'
		} );
		CKEDITOR.timestamp = '2';
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
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
	</script>
  </body>
</html>
