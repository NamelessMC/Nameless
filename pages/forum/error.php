<?php
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'true'){
	// Maintenance mode is enabled, only admins can view
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		require('pages/forum/maintenance.php');
		die();
	}
}
 
// Set the page name for the active link in navbar
$page = "forum";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'];
	
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
	<br />
	<div class="container">
      <div class="jumbotron">
	    <h1><?php echo $general_language['error']; ?></h1>
		<h4><?php echo $forum_language['forum_error']; ?></h4>
		<?php echo $forum_language['are_you_logged_in']; ?><br /><br />
		<div class="btn-group" role="group" aria-label="...">
		  <a href="#" class="btn btn-primary btn-lg" onclick="window.history.back()"><?php echo $general_language['back']; ?></a>
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
