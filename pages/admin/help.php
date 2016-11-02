<?php
/*
 *	Made by Partydragen
 *  http://partydragen.com/
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
 
$adm_page = "help";
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
	$title = $admin_language['help'];
	
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
    <div class="container">
	  <?php
	  // Index page
	  // Load navbar
	  $smarty->display('styles/templates/' . $template . '/navbar.tpl');
	  ?>
	  <br />
	  <div class="row">
	    <div class="col-md-3">
		  <?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
			<div class="well">
				<h3>If you need help you can ask for help here</h3>
				* <a href="http://namelessmc.com/" target="_blank">NamelessMC Website</a></br>
				* <a href="https://www.spigotmc.org/threads/88001/" target="_blank">Spigot Thread</a></br>
				* <a href="https://www.spigotmc.org/threads/88001/" target="_blank">Spigot Resource</a></br>

				<h3>FAQs</h3>
				* <a href="https://github.com/NamelessMC/Nameless/wiki/FAQs" target="_blank">FAQs</a>

				<h3>Open Source</h3>
				* <a href="https://github.com/NamelessMC/Nameless" target="_blank">Source Code on GitHub</a></br>
			</div>
	    </div>
	  </div>
	</div>
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	?>
  </body>
</html>
