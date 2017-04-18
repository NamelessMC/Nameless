<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
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
 
$adm_page = "misc";

if(isset($_GET['action']) && $_GET['action'] == 'reset_website' && isset($_GET['confirm'])){
	// Reset settings
	
	echo '<script>window.location.replace("/admin/misc");</script>';
	die();
}

// Deal with input
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Valid token
		// Is error reporting enabled or disabled?
		$error_reporting = Input::get('errors');
		if($error_reporting == 'on'){
			$error_reporting = 1;
		} else {
			$error_reporting = 0;
		}
		
		$error_reporting_id = $queries->getWhere('settings', array('name', '=', 'error_reporting'));
		$error_reporting_id = $error_reporting_id[0]->id;
		
		$queries->update('settings', $error_reporting_id, array(
			'value' => $error_reporting
		));
		
		$page_loading = Input::get('page_speed');
		if($page_loading == 'on'){
			$page_loading = 1;
		} else {
			$page_loading = 0;
		}
		
		// Is page load time enabled or disabled?
		$c->setCache('page_load_cache');
		$c->store('page_load', $page_loading);
	}
}

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
	$title = $admin_language['misc'];
	
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
	// Misc page
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
			<h3><?php echo $admin_language['other_settings']; ?></h3>
			<form action="" method="post">
			  <div class="row">
			    <div class="col-md-4">
				  <div class="form-group">
					<label for="errors"><?php echo $admin_language['enable_error_reporting']; ?></label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="<?php echo $admin_language['error_reporting_description']; ?>"><i class="fa fa-question-circle"></i></a>
					<span class="pull-right">
					  <input id="errors" name="errors" type="checkbox" class="js-switch"<?php if($error_reporting == '1'){ ?> checked<?php } ?> />
					</span>
				  </div>
				  <div class="form-group">
					<label for="page_speed"><?php echo $admin_language['display_page_load_time']; ?></label> <a class="btn btn-info btn-xs" href="#" data-toggle="popover" data-content="<?php echo $admin_language['page_load_time_description']; ?>"><i class="fa fa-question-circle"></i></a>
					<span class="pull-right">
					  <input id="page_speed" name="page_speed" type="checkbox" class="js-switch"<?php if($page_loading == '1'){ ?> checked<?php } ?> />
					</span>
				  </div>
			      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
				</div>
			  </div>
			</form>
			<hr>
			<h3><?php echo $admin_language['reset_website']; ?></h3>
			<?php echo $admin_language['reset_website_info']; ?><br /><br />
			<a href="/admin/misc/?action=reset_website&amp;confirm=true" disabled onclick="return confirm('<?php echo $admin_language['confirm_reset_website']; ?>');" class="btn btn-warning">Coming Soon<?php //echo $admin_language['reset_website']; ?></a>
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
	<script src="/core/assets/plugins/switchery/switchery.min.js"></script>
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  var switchery = new Switchery(html, {size: 'small'});
	});
	
	</script>
  </body>
</html>
