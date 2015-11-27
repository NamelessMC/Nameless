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
			// Need to re-authenticate. Display login form
			require('core/includes/password.php'); // Require password compat library
			// Deal with input
			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					// Validate input
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
						'password' => array('required' => true)
					));
					
					if($validation->passed()) {
						$user = new User();
						$login = $user->adminLogin(Input::get('username'), Input::get('password'));
						
						if($login) {
							Redirect::to("/admin");
							die();
						} else {
							Session::flash('adm_auth_error', '<div class="alert alert-danger">' . $user_language['incorrect_details'] . '</div>');
						}
					} else {
						Session::flash('adm_auth_error', '<div class="alert alert-danger">' . $user_language['incorrect_details'] . '</div>');
					}
				} else {
					// Invalid token
					Session::flash('adm_auth_error', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
				}
			}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $admin_language['admin_cp']; ?> &bull; <?php echo $admin_language['index']; ?></title>
	
	<?php
	// Generate header and navbar content
	require('core/includes/template/generate.php');
	?>
	
  </head>
  <body>
	<div class="container">
		<div class="row">
			<br /><br />
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form role="form" action="" method="post">
					<center><h2><?php echo $admin_language['re-authenticate']; ?></h2></center>
					<?php
					if(Session::exists('adm_auth_error')){
						echo Session::flash('adm_auth_error');
					}
					?>
					<div class="form-group">
						<input type="text" name="username" id="username" autocomplete="off" value="<?php echo htmlspecialchars(Input::get('username')); ?>" class="form-control" placeholder="<?php echo $user_language['username']; ?>" tabindex="3">
					</div>
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $user_language['password']; ?>" tabindex="4">
					</div>
					<div class="row">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						<center>
						<input type="submit" value="<?php echo $user_language['sign_in']; ?>" class="btn btn-primary btn-lg" tabindex="5">
						<a href="/" class="btn btn-danger btn-lg"><?php echo $general_language['back']; ?></a>
						</center>
					</div>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>
<?php
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

$adm_page = "index";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $admin_language['admin_cp']; ?> &bull; <?php echo $admin_language['index']; ?></title>
	
	<?php
	// Generate header and navbar content
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	.graph-container {
		box-sizing: border-box;
		width: 800px;
		height: 450px;
		padding: 20px 15px 15px 15px;
		margin: 15px auto 30px auto;
		border: 1px solid #ddd;
		background: #fff;
		background: linear-gradient(#f6f6f6 0, #fff 50px);
		background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
		background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
		background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
		background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
		box-shadow: 0 3px 10px rgba(0,0,0,0.15);
		-o-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		-ms-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		-moz-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		-webkit-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	}

	.graph-placeholder {
		width: 100%;
		height: 100%;
		font-size: 14px;
		line-height: 1.2em;
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
			<strong>NamelessMC version 1.0</strong><br />
			<strong>Running PHP <?php echo phpversion(); ?></strong> <a href="/admin/phpinfo.php" target="_blank">(Full PHP information)</a><br />
			<h3><?php echo $admin_language['statistics']; ?></h3>
			<strong><?php echo $admin_language['registrations_per_day']; ?></strong>
			<div class="graph-container">
			  <div id="placeholder" class="graph-placeholder"></div>
			</div>
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
	
	<script src="<?php echo PATH; ?>core/assets/js/charts/jquery.flot.min.js"></script>
	<script src="<?php echo PATH; ?>core/assets/js/charts/jquery.flot.time.min.js"></script>
	<script src="<?php echo PATH; ?>core/assets/js/charts/jquery.flot.tooltip.min.js"></script>
	<?php
	// Get data for members statistics graph
	$latest_members = $queries->orderWhere('users', 'joined > ' . strtotime("-1 week"), 'joined', 'ASC');

	// Output array
	$output = array();
	
	foreach($latest_members as $member){
		// Turn into format for graph
		// First, order them per day
		$date = date('d M Y', $member->joined);
		$date = strtotime($date);

		if(isset($output[$date])){
			$output[$date] = $output[$date] + 1;
		} else {
			$output[$date] = 1;
		}
	}
	
	// Fill in missing dates, set registrations to 0
	$start = strtotime("-1 week");
	$start = date('d M Y', $start);
	$start = strtotime($start);
	$end = strtotime(date('d M Y'));
	while($start <= $end){
		if(!isset($output[$start])){
			$output[$start] = 0;
		}
		$start = $start + 86400;
	}
	
	// Sort by date
	ksort($output);
	
	// Turn into string for graph
	$member_data = '';
	foreach($output as $date => $member){
		$member_data .= '[' . (($date + 86400) * 1000) . ', ' . $member . '], ';
	}
	$member_data = rtrim($member_data, ', ');
	?>
	
	<script type="text/javascript">
	$(document).ready(function() {
		var member_data = [[<?php echo $member_data; ?>]];
		$.plot("#placeholder", member_data, { 
			xaxis: { 
				mode: "time" 
			},
			yaxis: {
				min: 0
			},
			grid: {
				hoverable: true 
			},
			tooltip: true,
			tooltipOpts: {
				content: "%x : %y registrations",
				onHover: function(flotItem, $tooltipEl) {} 
			}
		});
	});
	</script>
  </body>
</html>