<?php 
/* 
 *	Made by Partydragen
 *  http://partydragen.com/
 *  License: MIT
 */
 
// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'false'){
	// Maintenance mode is disabled, redirect back to forum
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		Redirect::to('/forum');
		die();
	}
}

// Maintenance page
$page = 'forum'; // for navbar
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Maintenance <?php echo $sitename; ?> community">
    <meta name="author" content="Partydragen">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	<?php
	// Generate header and navbar content
	// Page title
	$title = $admin_language['maintenance_mode'];
	
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
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
</br>
    <div class="container">
      <div class="jumbotron">
	    <h1><?php echo $admin_language['maintenance_mode']; ?></h1>
		<h4><?php echo $admin_language['forum_in_maintenance']; ?></h4>
		<div class="btn-group" role="group" aria-label="...">
		  <button class="btn btn-primary btn-lg" onclick="javascript:history.go(-1)"><?php echo $general_language['back']; ?></button>
		  <a href="/" class="btn btn-success btn-lg"><?php echo $navbar_language['home']; ?></a>
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
