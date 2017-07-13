<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Vote addon page
$page = $vote_language['vote_icon'] . $vote_language['vote']; // for navbar

// Ensure the addon is enabled
if(!in_array('Vote', $enabled_addon_pages)){
	// Not enabled, redirect to homepage
	echo '<script data-cfasync="false">window.location.replace(\'/\');</script>';
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Vote page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	<?php
	// Generate header and navbar content
	// Page title
	$title = $vote_language['vote'];
	
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
  
    <div class="container">
	<br />
	<?php 
	$vote_message = $queries->getWhere("vote_settings", array("name", "=", "vote_message"));
	$vote_message = $vote_message[0]->value;
	
	if(!empty($vote_message)){
	?>
	<div class="alert alert-info"><center><?php echo htmlspecialchars($vote_message); ?></center></div>
	<?php 
	}
	
	$sites = $queries->getWhere("vote_sites", array("id", "<>", 0));
	
	// How many rows/columns?
	$total = count($sites);
	
	for($i = 0; $i < $total; $i++){
		if($i % 4 == 0){
			// Determine number of columns in the row
			$remaining = count($sites);
			
			if($remaining >= 4)
				$col_type = 3;
			else {
				$end = $i + $remaining;
				
				switch($remaining){
					case 1:
						$col_type = 12;
					break;
					case 2:
						$col_type = 6;
					break;
					case 3:
						$col_type = 4;
					break;
				}
			}
			
			echo '<div class="row">';
		}
		
		echo '<div class="col-md-' . $col_type . '">';
		echo '<center><a class="btn btn-lg btn-block btn-primary" href="' . str_replace("&amp;", "&", htmlspecialchars($sites[$i]->site)) . '" target="_blank" role="button">' . htmlspecialchars($sites[$i]->name) . '</a></center>';
		echo '</div>';
		unset($sites[$i]);
		
		if(($i + 1) % 4 == 0)
			echo '</div><br />';

	}
	?>

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
