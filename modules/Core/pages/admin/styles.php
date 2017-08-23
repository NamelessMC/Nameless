<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Admin Styles/Templates page
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
$admin_page = 'styles';
$admin_styles = true;
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php');
	?>

	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/codemirror/lib/codemirror.css">

  </head>
  <body>
    <?php require('modules/Core/pages/admin/navbar.php'); ?>
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
				  <a class="nav-link active"><?php echo $language->get('admin', 'templates'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link" href="<?php echo URL::build('/admin/images'); ?>"><?php echo $language->get('admin', 'images'); ?></a>
				</li>
			  </ul>
		      <hr />

			  <h3 style="display:inline;"><?php echo $language->get('admin', 'templates'); ?></h3>
			  <?php
			  if(isset($_GET['tid']) || isset($_GET['action'])) echo '<span class="pull-right"><a href="' . URL::build('/admin/styles/') . '" class="btn btn-primary">' . $language->get('general', 'back') . '</a></span>';
			  else echo '<span class="pull-right"><a href="' . URL::build('/admin/styles/', 'action=install') . '" class="btn btn-primary">' . $language->get('admin', 'install') . '</a></span>';
			  ?>
			  <hr />
			  <?php
			  if(Session::exists('admin_templates')){
				  echo Session::flash('admin_templates');
				  echo '<hr />';
			  }
			  if(!isset($_GET['tid']) && !isset($_GET['action'])){
				  // Get all templates
				  $templates = $queries->getWhere('templates', array('id', '<>', 0));

				  // Get all active templates
				  $active_templates = $queries->getWhere('templates', array('enabled', '=', 1));

				  foreach($templates as $template){
					  $template_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template->name), 'template.php'));
					  require($template_path);

					  echo '<strong>' . Output::getClean($template->name) . '</strong> ' . Output::getClean($template_version);

					  if($nl_template_version != NAMELESS_VERSION){
						  echo ' <span class="label label-warning"><i class="fa fa-exclamation-triangle" data-container="body" data-toggle="popover" data-placement="top" title="' . $language->get('admin', 'warning') . '" data-content="' . str_replace(array('{x}', '{y}'), array(Output::getClean($nl_template_version), NAMELESS_VERSION), $language->get('admin', 'template_outdated')) . '"></i></span>';
					  }

					  echo '<span class="pull-right">';

					  if($template->enabled == 0){
						echo '<a href="' . URL::build('/admin/styles/', 'action=activate&amp;template=' . $template->id) . '" class="btn btn-primary btn-sm">' . $language->get('admin', 'activate') . '</a> ';
						echo '<a href="' . URL::build('/admin/styles/', 'action=delete&amp;template=' . $template->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'' . $language->get('admin', 'confirm_delete_template') . '\');">' . $language->get('admin', 'delete') . '</a>';
					  } else {
						// Only allow deactivating if there is more than 1 template active, and it's not default
						if(count($active_templates) > 1 && $template->is_default == 0){
							echo '<a href="' . URL::build('/admin/styles/', 'action=deactivate&amp;template=' . $template->id) . '" class="btn btn-sm btn-danger">' . $language->get('admin', 'deactivate') . '</a> ';
						} else {
							echo '<button type="button" class="btn btn-sm btn-success" disabled>' . $language->get('admin', 'active') . '</button> ';
						}

						// Is the template default?
						if($template->is_default == 1){
							echo '<button type="button" class="btn btn-sm btn-success" disabled>' . $language->get('admin', 'default') . '</button> ';
						} else {
							echo '<a href="' . URL::build('/admin/styles/', 'action=make_default&amp;template=' . $template->id) . '" class="btn btn-sm btn-info">' . $language->get('admin', 'make_default') . '</a> ';
						}

						echo '<a href="' . URL::build('/admin/styles/', 'tid=' . $template->id) . '" class="btn btn-sm btn-warning">' . $language->get('general', 'edit') . '</a>';
					  }

					  echo '</span>';

					  echo '<hr />';

				  }

			  } else {
				  if(isset($_GET['tid']) && !isset($_GET['action'])){
					  // Editing template
					  // Get the template
					  $template = $queries->getWhere('templates', array('id', '=', $_GET['tid']));
					  if(count($template)){
						  $template = $template[0];
					  } else {
						  Redirect::to(URL::build('/admin/styles'));
						  die();
					  }

					  if($_GET['tid'] == 1){
						  echo '<div class="alert alert-warning">' . $language->get('admin', 'warning_editing_default_template') . '</div>';
					  }

					  if(!isset($_GET['file'])){
						  echo '<h4>' . htmlspecialchars($template->name) . '</h4>';
						  // Get all files
						  // Build path to template folder
						  $template_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template->name)));
						  $files = scandir($template_path);

						  foreach($files as $file){
							  if(strpos($file, '.tpl') !== false){
								  echo '<a href="' . URL::build('/admin/styles/', 'tid=' . $template->id . '&amp;file=' . htmlspecialchars($file)) . '">' . htmlspecialchars($file) . '</a><br />';
							  }
						  }

					  } else {
						  // Deal with input
						  if(Input::exists()){
							  if(Token::check(Input::get('token'))){
								  // Valid token
								  $file_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template->name), htmlspecialchars($_GET['file'])));
								  if(is_writable($file_path)){
									  // Can write to template file
									  // Write
									  $file = fopen($file_path, 'w');
									  fwrite($file, Input::get('code'));
									  fclose($file);

									  // Insert into logs
									  $ip = $user->getIP();

									  $queries->create('logs', array(
										'time' => date('U'),
										'action' => 'acp_template_update',
										'ip' => $ip,
										'user_id' => $user->data()->id,
										'info' => Output::getClean($_GET['file'])
									  ));

									  // Display session success message
									  Session::flash('template_view', '<div class="alert alert-success">' . $language->get('admin', 'template_updated') . '</div>');

									  // Redirect to refresh page
									  Redirect::to(URL::build('/admin/styles/', 'tid=' . $_GET['tid']. '&file=' . Output::getClean($_GET['file'])));
									  die();

								  } else {
									  // No write permission

								  }

							  } else {
								  // Invalid token
								  Session::flash('template_view', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');

							  }
						  }

						  // Session
						  if(Session::exists('template_view')){
							  echo Session::flash('template_view');
						  }

						  echo '<h4>'. htmlspecialchars($_GET['file']) . '</h4>';
						  $file_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', htmlspecialchars($template->name), htmlspecialchars($_GET['file'])));
						?>
						<form action="" method="post">
						  <div class="form-group">
							<textarea id="code" name="code"><?php echo htmlspecialchars(file_get_contents($file_path)); ?></textarea>
						  </div>
						  <br />
						  <div class="form-group">
							<input type="hidden" name="token" value="<?php echo Token::get(); ?>">
							<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
							<a href="<?php echo URL::build('/admin/styles/', 'tid=' . $template->id); ?>" class="btn btn-warning" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
						  </div>
						</form>
						<?php
					  }
				  } else if(isset($_GET['action'])){
					  if($_GET['action'] == 'install'){
						  // Install new template

						  // Scan template directory for new templates
						  $directories = glob('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . '*' , GLOB_ONLYDIR);
						  foreach($directories as $directory){
							$folders = explode(DIRECTORY_SEPARATOR, $directory);
							// Is it already in the database?

							$exists = $queries->getWhere('templates', array('name', '=', htmlspecialchars($folders[2])));
							if(!count($exists)){
								// No, add it now
								$queries->create('templates', array(
									'name' => htmlspecialchars($folders[2])
								));
							}
						  }

						  Session::flash('admin_templates', '<div class="alert alert-success">' . $language->get('admin', 'templates_installed_successfully') . '</div>');
						  Redirect::to(URL::build('/admin/styles'));
						  die();

					  } else if($_GET['action'] == 'make_default'){
						  // Make a template default
						  // Ensure it exists
						  $new_default = $queries->getWhere('templates', array('id', '=', $_GET['template']));
						  if(!count($new_default)){
							  // Doesn't exist
							  Redirect::to(URL::build('/admin/styles/'));
							  die();
						  } else {
							  $new_default_template = $new_default[0]->name;
							  $new_default = $new_default[0]->id;
						  }

						  // Get current default template
						  $current_default = $queries->getWhere('templates', array('is_default', '=', 1));
						  if(count($current_default)){
							  $current_default = $current_default[0]->id;
							  // No longer default
							  $queries->update('templates', $current_default, array(
								'is_default' => 0
							  ));
						  }

						  // Make selected template default
						  $queries->update('templates', $new_default, array(
							'is_default' => 1
						  ));

						  // Cache
						  $cache->setCache('templatecache');
						  $cache->store('default', $new_default_template);

						  // Session
						  Session::flash('admin_templates', '<div class="alert alert-success">' . str_replace('{x}', Output::getClean($new_default_template), $language->get('admin', 'default_template_set')) . '</div>');

						  Redirect::to(URL::build('/admin/styles/'));
						  die();

					  } else if($_GET['action'] == 'deactivate'){
						  // Deactivate a template
						  // Ensure it exists
						  $template = $queries->getWhere('templates', array('id', '=', $_GET['template']));
						  if(!count($template)){
							  // Doesn't exist
							  Redirect::to(URL::build('/admin/styles/'));
							  die();
						  }
						  $template = $template[0]->id;

						  // Deactivate the template
						  $queries->update('templates', $template, array(
							'enabled' => 0
						  ));

						  // Session
						  Session::flash('admin_templates', '<div class="alert alert-success">' . $language->get('admin', 'template_deactivated') . '</div>');

						  Redirect::to(URL::build('/admin/styles/'));
						  die();

					  } else if($_GET['action'] == 'activate'){
						  // Activate a template
						  // Ensure it exists
						  $template = $queries->getWhere('templates', array('id', '=', $_GET['template']));
						  if(!count($template)){
							  // Doesn't exist
							  Redirect::to(URL::build('/admin/styles/'));
							  die();
						  }
						  $template = $template[0]->id;

						  // Activate the template
						  $queries->update('templates', $template, array(
							'enabled' => 1
						  ));

						  // Session
						  Session::flash('admin_templates', '<div class="alert alert-success">' . $language->get('admin', 'template_activated') . '</div>');

						  Redirect::to(URL::build('/admin/styles/'));
						  die();

					  } else if($_GET['action'] == 'delete' && isset($_GET['template'])){
                          $item = $_GET['template'];

                          try {
                              // Ensure template is not default or active
                              $template = $queries->getWhere('templates', array('id', '=', $item));
                              if(count($template)){
                                  $template = $template[0];
                                  if($template->enabled == 1 || $template->is_default == 1){
                                      Redirect::to(URL::build('/admin/styles'));
                                      die();
                                  }

                                  $item = $template->name;
                              } else {
                                  Redirect::to(URL::build('/admin/styles'));
                                  die();
                              }

                              Util::recursiveRemoveDirectory('custom/templates/' . $item);

                              // Delete from database
                              $queries->delete('templates', array('name', '=', $item));

                              Session::flash('admin_templates', '<div class="alert alert-success">' . $language->get('admin', 'template_deleted_successfully') . '</div>');
                              Redirect::to(URL::build('/admin/styles'));
                              die();
                          } catch(Exception $e){
                              Session::flash('admin_templates', '<div class="alert alert-danger">' . $e->getMessage() . '</div>');
                              Redirect::to(URL::build('/admin/styles'));
                              die();
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

	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/codemirror/lib/codemirror.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/codemirror/mode/smarty/smarty.js"></script>

	<script>
	var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	  lineNumbers: true,
	  mode: "smarty"
	});
	</script>

  </body>
</html>