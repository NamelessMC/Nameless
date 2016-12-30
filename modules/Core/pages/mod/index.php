<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Moderator Overview page
 */

// Can the user view the ModCP?
if($user->isLoggedIn()){
	if(!$user->canViewMCP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
define('PAGE', 'mod_overview');

?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php 
	$title = $language->get('moderator', 'mod_cp');
	require('core/templates/header.php'); 
	?>
  
  </head>
  <body>
    <?php
	require('core/templates/navbar.php');
	require('core/templates/footer.php');
	require('core/templates/mod_navbar.php');
	
	// Count number of open reports
	$count_reports = $queries->getWhere('reports', array('status', '=', 0));
	$count_reports = count($count_reports);
	
	// Smarty variables
	$smarty->assign(array(
		'OVERVIEW' => $language->get('admin', 'overview'),
		'OPEN_REPORTS' => str_replace('{x}', $count_reports, $language->get('moderator', 'open_reports'))
	));
	
	// Display template
	$smarty->display('custom/templates/' . TEMPLATE . '/mod/index.tpl');

    require('core/templates/scripts.php'); 
	?>
  </body>
</html>