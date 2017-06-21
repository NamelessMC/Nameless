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
 
$adm_page = "addons";
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
	$title = $admin_language['addons'];
	
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
	// Addons page
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
		  <?php
			if(Session::exists('scan_complete')){
				echo Session::flash('scan_complete');
			}
		  ?>
		  <div class="well">
		    <?php if(!isset($_GET['action']) && !isset($_GET['activate']) && !isset($_GET['deactivate'])){ ?>
			<strong><?php echo $admin_language['installed_addons']; ?>:</strong>
			<span class="pull-right"><a href="/admin/addons/?action=new" class="btn btn-info"><?php echo $admin_language['install_addon']; ?></a></span><br /><br />
			<hr>
			<?php
			if(Session::exists('addon_error')){
				echo Session::flash('addon_error');
			}
			// Get a list of addons
			$addons = $queries->getWhere('addons', array('id', '<>', '0'));
			// Order alphabetically
			usort($addons, function ($elem1, $elem2) {
				return strcmp($elem1->name, $elem2->name);
			});
			// Display addons on page
			foreach($addons as $addon){
			?>
			<div class="row">
			&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo htmlspecialchars($addon->name); ?></strong>
			<?php if($addon->enabled == 1){ ?>
			<span class="pull-right"><a href="/admin/addons/?deactivate=<?php echo htmlspecialchars($addon->name); ?>" style="width: 100px;" class="btn btn-danger"><?php echo $admin_language['deactivate']; ?></a> <a href="/admin/addons/?action=edit&addon=<?php echo htmlspecialchars($addon->name); ?>" class="btn btn-info"><i class="fa fa-cog"></i></a> <a href="#" class="btn btn-danger" disabled><i class="fa fa-trash-o"></i></a></span>
			<?php } else { ?>
			<span class="pull-right"><a href="/admin/addons/?activate=<?php echo htmlspecialchars($addon->name); ?>" style="width: 100px;" class="btn btn-success"><?php echo $admin_language['activate']; ?></a> <a href="/admin/addons/?action=edit&addon=<?php echo htmlspecialchars($addon->name); ?>" class="btn btn-info"><i class="fa fa-cog"></i></a> <a href="/admin/addons/?action=delete&addon=<?php echo htmlspecialchars($addon->name); ?>" class="btn btn-danger" disabled><i class="fa fa-trash-o"></i></a></span>
			<?php } ?>
			<hr>
			</div>
			<?php
			}
			} else if(isset($_GET['action']) && $_GET['action'] == 'new'){
				// install theme
			?>
			<strong><?php echo $admin_language['install_an_addon']; ?></strong>
			<hr>
			<center>
			<p><?php echo $admin_language['addon_install_instructions']; ?></p>
			<div class="alert alert-danger">
			  <div class="row">
			    <div class="col-md-1"><i class="fa fa-exclamation-triangle"></i></div>
				<div class="col-md-11"><?php echo $admin_language['addon_install_warning']; ?></div>
			  </div>
			</div>
			<a href="/admin/addons/?action=scan" class="btn btn-primary"><?php echo $admin_language['scan']; ?></a>
			</center>
			<?php
			} else if(isset($_GET['action']) && $_GET['action'] == 'scan'){
				// Get a list of all folders in the 'addons' directory
				$directories = glob('addons/*' , GLOB_ONLYDIR);
				foreach($directories as $directory){
					$folders = explode('/', $directory);
					// Is it already in the database?
					
					$exists = $queries->getWhere('addons', array('name', '=', htmlspecialchars($folders[1])));
					if(!count($exists)){
						// No, add it now
						$queries->create('addons', array(
							'name' => htmlspecialchars($folders[1])
						));
					}
				}
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['addon_scan_complete'] . '</div>');
				echo '<script>window.location.replace(\'/admin/addons\');</script>';
				die();
			} else if(isset($_GET['action']) && $_GET['action'] == 'edit'){
				// Edit addon settings
				// First, check the addon actually exists
				$addon = $queries->getWhere('addons', array('name', '=', htmlspecialchars($_GET['addon'])));
				if(!count($addon)){
					Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['addon_not_exist'] . '</div>');
					echo '<script>window.location.replace(\'/admin/addons\');</script>';
					die();
				}
				require('addons/' . $_GET['addon'] . '/settings.php');
				
				
			} else if(isset($_GET['activate'])){
				// Make an addon active
				// First, check the addon actually exists
				$addon = $queries->getWhere('addons', array('name', '=', htmlspecialchars($_GET['activate'])));
				if(!count($addon)){
					Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['addon_not_exist'] . '</div>');
					echo '<script>window.location.replace(\'/admin/addons\');</script>';
					die();
				}
				$addon_name = $addon[0]->name;
				$addon = $addon[0]->id;
				
				// Addon exists
				
				// Make new addon active
				$queries->update('addons', $addon, array(
					'enabled' => 1
				));
				
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['addon_enabled'] . '</div>');
				echo '<script>window.location.replace(\'/admin/addons\');</script>';
				die();
			} else if(isset($_GET['deactivate'])){
				// Disable an addon
				// First, check the addon actually exists
				$addon = $queries->getWhere('addons', array('name', '=', htmlspecialchars($_GET['deactivate'])));
				if(!count($addon)){
					Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['addon_not_exist'] . '</div>');
					echo '<script>window.location.replace(\'/admin/addons\');</script>';
					die();
				}
				$addon_name = $addon[0]->name;
				$addon = $addon[0]->id;
				
				// Addon exists
				
				// Disable addon
				$queries->update('addons', $addon, array(
					'enabled' => 0
				));
				
				Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['addon_disabled'] . '</div>');
				echo '<script>window.location.replace(\'/admin/addons\');</script>';
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
