<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Admin index page
 */

// Can the user view the AdminCP?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else {
		// Check the user has re-authenticated
		if(!$user->isAdmLoggedIn()){
			// They haven't, do so now
			Redirect::to(URL::build('/admin/auth'));
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}


$page = 'admin';
$admin_page = 'overview';

?>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php');
	?>

  </head>
  <body>
    <?php require('modules/Core/pages/admin/navbar.php'); ?>
	<div class="container">
	  <div class="row">
	    <div class="col-md-3">
		  <?php require('modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <h3><?php echo $language->get('admin', 'overview'); ?></h3>
			  <?php echo str_replace('{x}', NAMELESS_VERSION, $language->get('admin', 'running_nameless_version')); ?><br />
			  <?php echo str_replace('{x}', phpversion(), $language->get('admin', 'running_php_version')); ?>

			  <br /><br />
			  <h3 style="display:inline;"><?php echo $language->get('admin', 'statistics'); ?></h3>
			  <span class="pull-right"><!-- dropdown to select stats here --></span>

			  <br />

			  <canvas id="registrationChart" width="100%" height="40"></canvas>

		    </div>
		  </div>
		</div>
	  </div>
    </div>

	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>


	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/moment/moment.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/charts/Chart.min.js"></script>

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
	$labels = '';
	$registration_data = '';
	foreach($output as $date => $member){
		$labels .= '"' . date('D', $date) . '", ';
		$registration_data .= $member . ', ';
	}
	$labels = '[' . rtrim($labels, ', ') . ']';
	$registration_data = '[' . rtrim($registration_data, ', ') . ']';
	?>

	<script type="text/javascript">
	$(document).ready(function() {
		var ctx = $("#registrationChart").get(0).getContext("2d");

		var data = {
			labels: <?php echo $labels; ?>,
			datasets: [
				{
					label: "Registrations",
					fill: false,
					borderColor: "rgba(255,12,0,0.5)",
					pointBorderColor: "rgba(255,0,5,0.5)",
					pointBackgroundColor: "#fff",
					tension: 0.1,
					data: <?php echo $registration_data; ?>
				}
			]
		}

		var registrationLineChart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: {
				scales: {
					yAxes: [{
						display: true,
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	});
	</script>

  </body>
</html>
