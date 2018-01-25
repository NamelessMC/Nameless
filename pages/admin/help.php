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
	<?php
	// Help page
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
			<div class="well">
				<h3>If you need help you can ask for help here</h3>
				* <a href="https://namelessmc.com/" target="_blank">NamelessMC Website</a></br>
				* <a href="https://www.spigotmc.org/threads/34810/" target="_blank">Spigot Thread</a></br>
				* <a href="https://www.spigotmc.org/resources/11434/" target="_blank">Spigot Resource</a></br>
			        * <a href="http://simba.spi.gt/iris/?channels=NamelessMC" target="_blank">IRC Chat</a></br>
	  			* <a href="https://discordapp.com/invite/QWdS9CB" target="_blank">Discord</a></br>

				<h3>FAQs</h3>
				* <a href="https://github.com/NamelessMC/Nameless/wiki/FAQs" target="_blank">FAQs</a></br>

				<h3>Open Source</h3>
				* <a href="https://github.com/NamelessMC/Nameless" target="_blank">Source Code on GitHub</a></br>

				<h3>NamelessMC Plugin</h3>
				* <a href="https://www.spigotmc.org/resources/42698/">Download from Spigot</a></br>

				<h3>Video Tutorial</h3>
				* <a href="https://youtu.be/BlRz9gpS-Ew?t=3m12s" target="_blank">Setup Forums (v1.0.2)</a></br>
				* <a href="https://youtu.be/BlRz9gpS-Ew?t=5m56s" target="_blank">Connect your Minecraft server (v1.0.2)</a></br>
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
  </body>
</html>
