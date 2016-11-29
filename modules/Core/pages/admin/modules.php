<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Admin index page
 */

if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to('/');
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
$admin_page = 'modules';

if(isset($_GET['action'])){
	if($_GET['action'] == 'install'){
		// Install any new modules
		$directories = glob('modules/*' , GLOB_ONLYDIR);
		
		foreach($directories as $directory){
			$folders = explode('/', $directory);
			// Is it already in the database?
			$exists = $queries->getWhere('modules', array('name', '=', htmlspecialchars($folders[1])));
			if(!count($exists)){
				// No, add it now
				$queries->create('modules', array(
					'name' => htmlspecialchars($folders[1])
				));
				
				// Require installer if necessary
				if(file_exists('modules/' . $folders[1] . '/install.php')){
					require('modules/' . $folders[1] . '/install.php');
				}
			}
		}
		
		Session::flash('admin_modules', '<div class="alert alert-success">' . $language->get('admin', 'modules_installed_successfully') . '</div>');
		
		Redirect::to(URL::build('/admin/modules'));
		
		die();
		
	} else if($_GET['action'] == 'enable'){
		// Enable a module
		if(!isset($_GET['m']) || !is_numeric($_GET['m']) || $_GET['m'] == 1) die('Invalid module!');
		
		$queries->update('modules', $_GET['m'], array(
			'enabled' => 1
		));
		
		// Get module name
		$name = $queries->getWhere('modules', array('id', '=', $_GET['m']));
		$name = htmlspecialchars($name[0]->name);
		
		// Cache
		$cache->setCache('modulescache');
		
		// Get existing enabled modules
		$enabled_modules = $cache->retrieve('enabled_modules');

		$modules = array();
		
		foreach($enabled_modules as $module){
			$modules[] = $module;
		}
		
		$modules[] = array(
			'name' => $name,
			'priority' => 4
		);
		
		// Store
		$cache->store('enabled_modules', $modules);
		
		Session::flash('admin_modules', '<div class="alert alert-success">' . $language->get('admin', 'module_enabled') . '</div>');
		
		Redirect::to(URL::build('/admin/modules'));
		
		die();
		
	} else if($_GET['action'] == 'disable'){
		// Disable a module
		if(!isset($_GET['m']) || !is_numeric($_GET['m']) || $_GET['m'] == 1) die('Invalid module!');
		
		$queries->update('modules', $_GET['m'], array(
			'enabled' => 0
		));
		
		// Get module name
		$name = $queries->getWhere('modules', array('id', '=', $_GET['m']));
		$name = htmlspecialchars($name[0]->name);
		
		// Cache
		$cache->setCache('modulescache');
		
		// Get existing enabled modules
		$enabled_modules = $cache->retrieve('enabled_modules');

		$modules = array();
		
		foreach($enabled_modules as $module){
			if($module['name'] != $name) $modules[] = $module;
		}

		// Store
		$cache->store('enabled_modules', $modules);
		
		Session::flash('admin_modules', '<div class="alert alert-success">' . $language->get('admin', 'module_disabled') . '</div>');
		
		Redirect::to(URL::build('/admin/modules'));
		
		die();
		
	}
}

?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php 
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php'); 
	?>
  
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
			  <h3 style="display:inline;"><?php echo $language->get('admin', 'modules'); ?></h3>
			  <span class="pull-right">
			    <a class="btn btn-primary" href="<?php echo URL::build('/admin/modules/', 'action=install'); ?>"><?php echo $language->get('admin', 'install'); ?></a>
			  </span>
			  <br />
			  <hr />
			  <?php
			  if(Session::exists('admin_modules')){
				  echo Session::flash('admin_modules');
			  }
			  
			  // Get all modules
			  $modules = $queries->getWhere('modules', array('id', '<>', 0));
			  
			  foreach($modules as $module){
				  if(isset($module_version)) unset($module_version);
				  if(isset($nameless_version)) unset($nameless_version);
				  
				  if(file_exists('modules/' . $module->name . '/module.php')) require('modules/' . $module->name . '/module.php');
			  ?>
			  <div class="row">
			    <div class="col-md-9">
			      <strong><?php echo htmlspecialchars($module->name); ?></strong> <?php if(isset($module_version)){ ?><small><?php echo $module_version; ?></small><?php } ?>
				</div>
				<div class="col-md-3">
				  <span class="pull-right">
				    <?php
					if($module->id == 1){
					?>
				    <a href="#" class="btn btn-warning disabled"><i class="fa fa-lock" aria-hidden="true"></i></a>
					<!--<a href="<?php //echo URL::build('/admin/modules/', 'action=edit&m=' . $module->id); ?>" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i></a>-->
					<?php
					} else {
						if($module->enabled == 1){
					?>
					<a href="<?php echo URL::build('/admin/modules/', 'action=disable&m=' . $module->id); ?>" class="btn btn-success"><?php echo $language->get('admin', 'enabled'); ?></a>
					<!--<a href="<?php //echo URL::build('/admin/modules/', 'action=edit&m=' . $module->id); ?>" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i></a>-->
					<?php
						} else {
					?>
					<a href="<?php echo URL::build('/admin/modules/', 'action=enable&m=' . $module->id); ?>" class="btn btn-danger"><?php echo $language->get('admin', 'disabled'); ?></a>
					<?php
						}
					}
					?>
				  </span>
				</div>
			  </div>
			  <hr />
			  <?php
			  }
			  ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
	
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
	
  </body>
</html>