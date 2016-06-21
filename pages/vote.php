<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Donation addon page
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
    <meta name="author" content="Samerton">
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
	
	if(count($sites) > 4){
		// How many buttons on the second row?
		$second_row = count($sites) - 4;
		if($second_row == 1){
			// one central button
			$col = '12';
		} else if($second_row == 2){
			// two central buttons
			$col = '6';
		} else if($second_row == 3){
			// three wider buttons
			$col = '4';
		} else if($second_row == 4){
			// four buttons
			$col = '3';
		}
	} else {
		// How many buttons on the top row?
		$top_row = count($sites);
		if($top_row == 1){
			// one central button
			$col = '12';
		} else if($top_row == 2){
			// two central buttons
			$col = '6';
		} else if($top_row == 3){
			// three wider buttons
			$col = '4';
		} else if($top_row == 4){
			// four buttons
			$col = '3';
		}
	}
	
	if(isset($top_row)){
		// One row only
	?>
	<div class="row">
	<?php
		foreach($sites as $site){
	?>
	  <div class="col-md-<?php echo $col; ?>">
	    <center><a class="btn btn-lg btn-block btn-primary" href="<?php echo str_replace("&amp;", "&", htmlspecialchars($site->site)); ?>" target="_blank" role="button"><?php echo htmlspecialchars($site->name); ?></a></center>
	  </div>
	<?php 
		} 
	?>
	</div>
	<?php
	} else if(isset($second_row)){
		// Two rows
	?>
	<div class="row">
	<?php
		$n = 0;
		while($n < 4){
	?>
	  <div class="col-md-3">
	    <center><a class="btn btn-lg btn-block btn-primary" href="<?php echo str_replace("&amp;", "&", htmlspecialchars($sites[$n]->site)); ?>" target="_blank" role="button"><?php echo htmlspecialchars($sites[$n]->name); ?></a></center>
	  </div>
	<?php
			$n++;
		}
	?>
	</div><br />
	<div class="row">
	<?php
		$n = 0;
		while($n < $second_row){
	?>
	  <div class="col-md-<?php echo $col; ?>">
        <center><a class="btn btn-lg btn-block btn-primary" href="<?php echo str_replace("&amp;", "&", htmlspecialchars($sites[$n + 4]->site)); ?>" target="_blank" role="button"><?php echo htmlspecialchars($sites[$n + 4]->name); ?></a></center>
	  </div>
	<?php
			$n++;
		}
	?>
	</div>
	<?php
	}
	?>
	<hr>
	
	<div class="alert alert-info"><?php echo $vote_language['top_voters']; ?></div>
	  
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
