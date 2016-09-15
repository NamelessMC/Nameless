<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
/*
 *  Create page header (which css to load)
 */

// Page title first
echo '<title>' . str_replace('&amp;', '&', $title) . ' &bull; ' . $sitename . '</title>';
 
/*
 *  Check to see if the theme actually exists..
 */
if(!is_dir('styles/themes/' . $theme_result)){
	// Doesn't exist
	// Display an error
	Session::flash('global', '<div class="alert alert-danger">' . $general_language['theme_not_exist'] . '</div>');
	
	// Load default css instead
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/custom.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/font-awesome.min.css" rel="stylesheet">' . PHP_EOL;
	
} else {
	// Exists
	// Load the css
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/custom.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/font-awesome.min.css" rel="stylesheet">' . PHP_EOL;
}

// Global
echo '<link href="' . PATH . 'core/assets/css/toastr.css" rel="stylesheet">' . PHP_EOL;
echo '<link href="' . PATH . 'core/assets/css/custom_core.css" rel="stylesheet">' . PHP_EOL;
echo '<link rel="icon" href="' . PATH . 'core/assets/favicon.ico">';

// Custom
foreach($custom_css as $item){
	echo $item;
}

// Google Analytics module
if(isset($ga_script)){
	echo $ga_script;
}

// Announcements
if(isset($page)){
	$page_announcements = $queries->getWhere('announcements_pages', array('page', '=', $page));
	if(count($page_announcements)){
		if($user->isLoggedIn()) $group_id = $user->data()->group_id;
		else $group_id = 0;
		
		$announcements = array();
		
		foreach($page_announcements as $page_announcement){
			// Permissions
			$permissions = $queries->getWhere('announcements_permissions', array('announcement_id', '=', $page_announcement->announcement_id));
			foreach($permissions as $permission){
				if($permission->view == 1 && $permission->group_id == $group_id){
					$announcement = $queries->getWhere('announcements', array('id', '=', $page_announcement->announcement_id));
					$announcement = $announcement[0];
					
					$announcements[] = array(
						'type' => htmlspecialchars($announcement->type),
						'content' => Output::getPurified(htmlspecialchars_decode($announcement->content)),
						'can_close' => $announcement->can_close,
						'id' => $announcement->id
					);
				}
			}
		}
		
		$smarty->assign('ANNOUNCEMENTS', $announcements);
	}
}