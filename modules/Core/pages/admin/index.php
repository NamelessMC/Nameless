<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
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
			Redirect::to(URL::build('/panel/auth'));
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
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
  <head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>

  </head>
  <body>
    <?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
	<div class="container">
	  <div class="row">
	    <div class="col-md-3">
		  <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
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

          <hr />
          <h3><?php echo $language->get('admin', 'notices'); ?></h3>
            <?php
            // Email errors?
            $email_errors = $queries->getWhere('email_errors', array('id', '<>', 0));
            if(count($email_errors)){
              ?>
                <div class="alert alert-warning"><?php echo str_replace('{x}', URL::build('/admin/core/', 'view=email&amp;action=errors'), $language->get('admin', 'email_errors_logged')); ?></div>
              <?php
            } else {
              echo $language->get('admin', 'no_notices');
            }

            // Minecraft service query
            // Check if Minecraft is enabled
            $minecraft_enabled = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
            $minecraft_enabled = $minecraft_enabled[0]->value;

            if($minecraft_enabled == '1') {
                // Query Minecraft services
                $cache->setCache('mc_service_cache');
                if($cache->isCached('services')){
                    $results = $cache->retrieve('services');
                } else {
                    $results = ExternalMCQuery::queryMinecraftServices();
                    $cache->store('services', $results, 120);
                }
                echo '<hr /><h3>' . $language->get('admin', 'mc_service_status') . '</h3>';
                if(count((array)$results) == 8) {
                    $n = 1;
                    foreach ($results as $key => $result) {
                        if($n == 1)
                            echo '<div class="row">';
                        else if(($n + 1) % 2 == 0)
                            echo '</div><div class="row">';
						
						switch($result->status){
							case 'Online':
								$status = 'success';
							break;
							case 'Slow':
								$status = 'warning';
							break;
							default:
								$status = 'danger';
							break;
						}
                        ?>
                        <div class="col-6">
                          <div class="card card-inverse card-<?php echo $status; ?> mb-3">
                            <div class="card-block">
                              <h4 class="card-title"><?php echo Output::getClean($key); ?></h4>
                              <p class="card-text"><?php echo Output::getClean($result->status); ?></p>
                            </div>
                          </div>
                        </div>
                        <?php
                        if($n == 8)
                            echo '</div>';

                        $n++;
                    }
                } else
                    echo '<div class="alert alert-danger">' . $language->get('admin', 'service_query_error') . '</div>';
            }
            ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>

	<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>

    <?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>


	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/moment/moment.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/charts/Chart.min.js"></script>

	<?php
	// Get data for members statistics graph
	$latest_members = $queries->orderWhere('users', 'joined > ' . strtotime("-1 week"), 'joined', 'ASC');

	// Get data for topics and posts
	$latest_topics = $queries->orderWhere('topics', 'topic_date > ' . strtotime("-1 week"), 'topic_date', 'ASC');
	$latest_posts = $queries->orderWhere('posts', 'post_date > "' . date('Y-m-d G:i:s', strtotime("-1 week")) . '"', 'post_date', 'ASC');

	// Output array
	$output = array();

	foreach($latest_members as $member){
		// Turn into format for graph
		// First, order them per day
		$date = date('d M Y', $member->joined);
		$date = strtotime($date);

		if(isset($output[$date]['users'])){
			$output[$date]['users'] = $output[$date]['users'] + 1;
		} else {
			$output[$date]['users'] = 1;
		}
	}

	foreach($latest_topics as $topic){
	  $date = date('d M Y', $topic->topic_date);
	  $date = strtotime($date);

	  if(isset($output[$date]['topics'])){
	    $output[$date]['topics'] = $output[$date]['topics'] + 1;
    } else {
	    $output[$date]['topics'] = 1;
    }
  }

  foreach($latest_posts as $post){
      $date = date('d M Y', strtotime($post->post_date));
      $date = strtotime($date);

      if(isset($output[$date]['posts'])){
          $output[$date]['posts'] = $output[$date]['posts'] + 1;
      } else {
          $output[$date]['posts'] = 1;
      }
  }

	// Fill in missing dates, set registrations to 0
	$start = strtotime("-1 week");
	$start = date('d M Y', $start);
	$start = strtotime($start);
	$end = strtotime(date('d M Y'));
	while($start <= $end){
		if(!isset($output[$start]['users']))
      $output[$start]['users'] = 0;

    if(!isset($output[$start]['topics']))
      $output[$start]['topics'] = 0;

    if(!isset($output[$start]['posts']))
      $output[$start]['posts'] = 0;

		$start = $start + 86400;
	}

	// Sort by date
	ksort($output);

	// Turn into string for graph
	$labels = '';
	$registration_data = '';
	$topics_data = '';
	$posts_data = '';
	foreach($output as $date => $member){
		$labels .= '"' . date('Y-m-d', $date) . '", ';
		$registration_data .= $member['users'] . ', ';
		$topics_data .= $member['topics'] . ', ';
		$posts_data .= $member['posts'] . ', ';
	}
	$labels = '[' . rtrim($labels, ', ') . ']';
	$registration_data = '[' . rtrim($registration_data, ', ') . ']';
	$topics_data = '[' . rtrim($topics_data, ', ') . ']';
	$posts_data = '[' . rtrim($posts_data, ', ') . ']';
	?>

	<script type="text/javascript">
	$(document).ready(function() {
		var ctx = $("#registrationChart").get(0).getContext("2d");

		moment.locale('<?php echo (defined('HTML_LANG') ? strtolower(HTML_LANG) : 'en'); ?>');

		var data = {
			labels: <?php echo $labels; ?>,
			datasets: [
				{
					label: "<?php echo $language->get('admin', 'registrations'); ?>",
					fill: false,
					borderColor: "rgba(255,12,0,0.5)",
					pointBorderColor: "rgba(255,0,5,0.5)",
					pointBackgroundColor: "#fff",
					tension: 0.1,
					data: <?php echo $registration_data; ?>
				},
				{
					label: "<?php echo $language->get('admin', 'topics'); ?>",
					fill: false,
					borderColor: "#0004FF",
					pointBorderColor: "#0004FF",
					pointBackgroundColor: "#fff",
					tension: 0.1,
					data: <?php echo $topics_data; ?>
				},
				{
					label: "<?php echo $language->get('admin', 'posts'); ?>",
					fill: false,
					borderColor: "#00931D",
					pointBorderColor: "#00931D",
					pointBackgroundColor: "#fff",
					tension: 0.1,
					data: <?php echo $posts_data; ?>
				}
			]
		};

		var registrationLineChart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: {
				scales: {
					yAxes: [{
						display: true,
						ticks: {
							beginAtZero: true,
							userCallback: function(label, index, labels) {
											 // when the floored value is the same as the value we have a whole number
											 if (Math.floor(label) === label) {
													 return label;
											 }

							}
						}
					}],
					xAxes: [{
					    type: 'time',
					    time: {
					        unit: 'day'
					    }
					}]
				}
			}
		});
	});
	</script>

  </body>
</html>
