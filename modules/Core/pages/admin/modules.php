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

require('modules/Core/views/admin/modules.view.php');

?>
