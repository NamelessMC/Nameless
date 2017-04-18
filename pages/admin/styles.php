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
 
$adm_page = "styles";
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
	$title = $admin_language['style'];
	
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
	// Styles page
	// Load navbar
	if(is_file('styles/templates/' . $template . '/navbar.tpl')){
		$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	} else {
		// template not defined, allow user to navigate straight to styles page to change this
		$smarty->display('styles/templates/Default/navbar.tpl');
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
			<li<?php if(!isset($_GET['type'])){ ?> class="active"<?php } ?>><a href="/admin/styles"><?php echo $admin_language['themes']; ?></a></li>
			<li<?php if(isset($_GET['type']) && $_GET['type'] == 'templates'){ ?> class="active"<?php } ?>><a href="/admin/styles/?type=templates"><?php echo $admin_language['templates']; ?></a></li>
		  </ul>
		  <hr>
		  <?php
			if(Session::exists('scan_complete')){
				echo Session::flash('scan_complete');
			}
		  ?>
		  <div class="well">
		    <?php 
			if(!isset($_GET['action']) && !isset($_GET['type']) && !isset($_GET['activate'])){ 
				if(Input::exists()){
					if(Token::check(Input::get('token'))){
						// Valid token
						// Is the inverse navbar enabled or disabled?
						$inverse_navbar = Input::get('inverse_navbar');
						if($inverse_navbar == 'on'){
							$inverse_navbar = 1;
						} else {
							$inverse_navbar = 0;
						}
						
						$inverse_navbar_id = $queries->getWhere('settings', array('name', '=', 'inverse_navbar'));
						$inverse_navbar_id = $inverse_navbar_id[0]->id;
						
						$queries->update('settings', $inverse_navbar_id, array(
							'value' => $inverse_navbar
						));
						
						// Update cache
						$c->setCache('themecache');
						$c->store('inverse_navbar', $inverse_navbar);
						
						Session::flash('themes', '<div class="alert alert-success">' . $admin_language['successfully_updated'] . '</div>');
						echo '<script data-cfasync="false">window.location.reload();</script>';
						die();
						
					} else {
						// Invalid Token
						Session::flash('themes', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
					}
				}

				if(Session::exists('themes')){
					echo Session::flash('themes');
				}
		    ?>
			<strong><?php echo $admin_language['installed_themes']; ?>:</strong>
			<span class="pull-right"><a href="/admin/styles/?action=new" class="btn btn-info"><?php echo $admin_language['install_theme']; ?></a></span><br /><br />
			<hr>
			<?php
			// Get a list of themes
			$styles = $queries->getWhere('themes', array('id', '<>', '0'));
			foreach($styles as $style){
			?>
		    <div class="row">
			&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo htmlspecialchars($style->name); ?></strong>
			<?php if($style->enabled == 1){ ?>
			<span class="pull-right"><a href="#" style="width: 90px;" class="btn btn-success" disabled><?php echo $admin_language['active']; ?></a> <a href="#" class="btn btn-danger" disabled><i class="fa fa-trash-o"></i></a></span>
			<?php } else { ?>
			<span class="pull-right"><a href="/admin/styles/?activate=<?php echo htmlspecialchars($style->name); ?>" style="width: 90px;" class="btn btn-primary"><?php echo $admin_language['activate']; ?></a> <a data-toggle="modal" data-target="#<?php echo 'delete-' . htmlspecialchars($style->name); ?>" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></span>
			<?php } ?>
			<hr>
			</div>
			<?php
			}
			?>
			<hr>
			<h4><?php echo $admin_language['settings']; ?></h4>
			<form action="" method="post">
			  <div class="form-group">
			    <label for="inverse_navbar"><?php echo $admin_language['inverse_navbar']; ?></label>
			    <input id="inverse_navbar" name="inverse_navbar" type="checkbox" class="js-switch"<?php if($inverse_navbar == 1){ ?> checked<?php } ?> />
			  </div>
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			</form>
			<?php
			// Modals for confirming deletion of themes
			foreach($styles as $style){
			?>
			<div class="modal fade" id="delete-<?php echo htmlspecialchars($style->name); ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo htmlspecialchars($style->name); ?>ModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="<?php echo htmlspecialchars($style->name); ?>ModalLabel"><?php echo $admin_language['confirm_action']; ?></h4>
				  </div>
				  <div class="modal-body">
					<?php echo str_replace('{x}', htmlspecialchars($style->name), $admin_language['confirm_theme_deletion']); ?>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $general_language['cancel']; ?></button>
					<a href="/admin/styles/?action=delete&amp;theme=<?php echo htmlspecialchars($style->name); ?>" type="button" class="btn btn-danger"><?php echo $general_language['confirm']; ?></a>
				  </div>
				</div>
			  </div>
			</div>
			<?php
			}

			} else if(isset($_GET['action']) && $_GET['action'] == 'new' && !isset($_GET['type'])){
				// install theme
			?>
			<strong><?php echo $admin_language['install_a_theme']; ?></strong>
			<hr>
			<center>
			<p><?php echo $admin_language['theme_install_instructions']; ?></p>
			<a href="/admin/styles/?action=scan" class="btn btn-primary"><?php echo $admin_language['scan']; ?></a>
			</center>
			<?php
			} else if(isset($_GET['action']) && $_GET['action'] == 'scan' && !isset($_GET['type'])){
				// Get a list of all folders in the 'style/themes' directory
				$directories = glob('styles/themes/*' , GLOB_ONLYDIR);
				foreach($directories as $directory){
					$folders = explode('/', $directory);
					// Is it already in the database?
					$exists = $queries->getWhere('themes', array('name', '=', htmlspecialchars($folders[2])));
					if(!count($exists)){
						// No, add it now
						$queries->create('themes', array(
							'name' => htmlspecialchars($folders[2])
						));
					}
				}
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['style_scan_complete'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/styles\');</script>';
				die();
			} else if(isset($_GET['activate']) && !isset($_GET['type'])){
				// Make a theme active
				// First, check the theme actually exists
				$theme = $queries->getWhere('themes', array('name', '=', htmlspecialchars($_GET['activate'])));
				if(!count($theme)){
					Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['theme_not_exist'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace(\'/admin/styles\');</script>';
					die();
				}
				$theme_name = $theme[0]->name;
				$theme = $theme[0]->id;
				
				// Theme exists
				// Get currently selected theme and disable it
				$active_theme = $queries->getWhere('themes', array('enabled', '=', 1));
				$active_theme = $active_theme[0]->id;
				$queries->update('themes', $active_theme, array(
					'enabled' => 0
				));
				
				// Make new theme active
				$queries->update('themes', $theme, array(
					'enabled' => 1
				));
				
				// Finally, we need to write to cache
				$c->setCache('themecache');
				$c->store('theme', htmlspecialchars($theme_name));
				
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['theme_enabled'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/styles\');</script>';
				die();
			} else if(isset($_GET['action']) && $_GET['action'] == 'delete' && !isset($_GET['type'])){
				// Delete template
				$item = $_GET['theme'];
				
				require('core/includes/remove_directories.php');
				recursiveRemoveDirectory('styles/themes/' . $item);
				
				// Delete from database
				$queries->delete('themes', array('name', '=', $item));
				
				Session::flash('themes', '<div class="alert alert-success">' . $admin_language['theme_deleted'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/styles/\');</script>';
				die();
			} else if(!isset($_GET['action']) && isset($_GET['type']) && $_GET['type'] == 'templates' && !isset($_GET['activate'])){ 
				if(Session::exists('templates')){
					echo Session::flash('templates');
				}
		    ?>
			<strong><?php echo $admin_language['installed_templates']; ?>:</strong>
			<span class="pull-right"><a href="/admin/styles/?type=templates&amp;action=new" class="btn btn-info"><?php echo $admin_language['install_template']; ?></a></span><br /><br />
			<hr>
			<?php
			// Get a list of templates
			$templates = $queries->getWhere('templates', array('id', '<>', '0'));
			foreach($templates as $item){
			?>
			<div class="row">
			&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo htmlspecialchars($item->name); ?></strong>
			<?php if($item->enabled == 1){ ?>
			<span class="pull-right"><a href="#" style="width: 90px;" class="btn btn-success" disabled><?php echo $admin_language['active']; ?></a> <a href="#" class="btn btn-danger" disabled><i class="fa fa-trash-o"></i></a></span>
			<?php } else { ?>
			<span class="pull-right"><a href="/admin/styles/?type=templates&amp;activate=<?php echo htmlspecialchars($item->name); ?>" style="width: 90px;" class="btn btn-primary"><?php echo $admin_language['activate']; ?></a> <a data-toggle="modal" data-target="#<?php echo 'delete-' . htmlspecialchars($item->name); ?>" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></span>
			<?php } ?>
			<hr>
			</div>
			<?php
			}
			// Modals for confirming deletion of templates
			foreach($templates as $item){
			?>
			<div class="modal fade" id="delete-<?php echo htmlspecialchars($item->name); ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo htmlspecialchars($item->name); ?>ModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="<?php echo htmlspecialchars($item->name); ?>ModalLabel"><?php echo $admin_language['confirm_action']; ?></h4>
				  </div>
				  <div class="modal-body">
					<?php echo str_replace('{x}', htmlspecialchars($item->name), $admin_language['confirm_template_deletion']); ?>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $general_language['cancel']; ?></button>
					<a href="/admin/styles/?type=templates&amp;action=delete&amp;template=<?php echo htmlspecialchars($item->name); ?>" type="button" class="btn btn-danger"><?php echo $general_language['confirm']; ?></a>
				  </div>
				</div>
			  </div>
			</div>
			<?php
			}

			} else if(isset($_GET['action']) && $_GET['action'] == 'new' && isset($_GET['type']) && $_GET['type'] == 'templates'){
				// install template
			?>
			<strong><?php echo $admin_language['install_a_template']; ?></strong>
			<hr>
			<center>
			<p><?php echo $admin_language['template_install_instructions']; ?></p>
			<a href="/admin/styles/?type=templates&amp;action=scan" class="btn btn-primary"><?php echo $admin_language['scan']; ?></a>
			</center>
			<?php
			} else if(isset($_GET['action']) && $_GET['action'] == 'scan' && isset($_GET['type']) && $_GET['type'] == 'templates'){
				// Get a list of all folders in the 'style/templates' directory
				$directories = glob('styles/templates/*' , GLOB_ONLYDIR);
				foreach($directories as $directory){
					$folders = explode('/', $directory);
					// Is it already in the database?
					$exists = $queries->getWhere('templates', array('name', '=', htmlspecialchars($folders[2])));
					if(!count($exists)){
						// No, add it now
						$queries->create('templates', array(
							'name' => htmlspecialchars($folders[2])
						));
					}
				}
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['style_scan_complete'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/styles/?type=templates\');</script>';
				die();
			} else if(isset($_GET['activate']) && isset($_GET['type']) && $_GET['type'] == 'templates'){
				// Make a template active
				// First, check the template actually exists
				$template_query = $queries->getWhere('templates', array('name', '=', htmlspecialchars($_GET['activate'])));
				if(!count($template_query)){
					Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['template_not_exist'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace(\'/admin/styles/?type=templates\');</script>';
					die();
				}
				$template_name = $template_query[0]->name;
				$template_query = $template_query[0]->id;
				
				// Template exists
				// Get currently selected template and disable it
				$active_template = $queries->getWhere('templates', array('enabled', '=', 1));
				$active_template = $active_template[0]->id;
				$queries->update('templates', $active_template, array(
					'enabled' => 0
				));
				
				// Make new template active
				$queries->update('templates', $template_query, array(
					'enabled' => 1
				));
				
				// Finally, we need to write to cache
				$c->setCache('templatecache');
				$c->store('template', htmlspecialchars($template_name));
				
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['template_enabled'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/styles/?type=templates\');</script>';
				die();
			} else if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['type']) && $_GET['type'] == 'templates'){
				// Delete template
				$item = $_GET['template'];
				
				require('core/includes/remove_directories.php');
				recursiveRemoveDirectory('styles/templates/' . $item);
				
				// Delete from database
				$queries->delete('templates', array('name', '=', $item));
				
				Session::flash('templates', '<div class="alert alert-success">' . $admin_language['template_deleted'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace(\'/admin/styles/?type=templates\');</script>';
				die();
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
	<script src="/core/assets/plugins/switchery/switchery.min.js"></script>
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  var switchery = new Switchery(html, {size: 'small'});
	});

	</script>
  </body>
</html>
