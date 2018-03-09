<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  UserCP overview
 */

// Must be logged in
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Always define page name for navbar
define('PAGE', 'cc_overview');

require(ROOT_PATH . '/core/templates/cc_navbar.php');
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('user', 'user_cp');
	require(ROOT_PATH . '/core/templates/header.php');
	?>
  
  </head>
  <body>
    <?php
	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	$user_details = array(
		$language->get('user', 'username') => $user->data()->username,
		$language->get('admin', 'group') => $user->getGroupName($user->data()->group_id),
		$language->get('admin', 'registered') => date('d M Y, H:i', $user->data()->joined)
	);
	
	// Language values
	$smarty->assign(array(
		'USER_CP' => $language->get('user', 'user_cp'),
		'USER_DETAILS' => $language->get('user', 'user_details'),
		'USER_DETAILS_VALUES' => $user_details,
		'OVERVIEW' => $language->get('user', 'overview')
	));

	// Get graph data
	$cache->setCache('modulescache');
	$enabled_modules = $cache->retrieve('enabled_modules');
	foreach($enabled_modules as $module){
      // Forum module enabled?
      if($module['name'] == 'Forum'){
          // Enabled
          $forum_enabled = true;
          break;
      }
  }

  if(isset($forum_enabled)){
	  $forum_query_user = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM nl2_posts WHERE post_creator = ? AND created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", array($user->data()->id, strtotime('-7 days')))->results();
	  $forum_query_average = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), (COUNT(*) / COUNT(Distinct post_creator)) FROM nl2_posts WHERE created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", array(strtotime('-7 days')))->results();
	  $forum_query_total = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM nl2_posts WHERE created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", array(strtotime('-7 days')))->results();

	  $output = array();
	  foreach($forum_query_user as $item){
		  $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
		  $output[$date]['user'] = $item->{'COUNT(*)'};
	  }
	  foreach($forum_query_average as $item){
		  $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
		  $output[$date]['average'] = $item->{'(COUNT(*) / COUNT(Distinct post_creator))'};
    }
	  foreach($forum_query_total as $item){
		  $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
		  $output[$date]['total'] = $item->{'COUNT(*)'};
	  }

	  // Fill in missing dates
	  $start = strtotime("-7 days");
	  $start = date('d M Y', $start);
	  $start = strtotime($start);
	  $end = strtotime(date('d M Y'));
	  while($start <= $end){
		  if(!isset($output[$start]['user']))
			  $output[$start]['user'] = 0;

		  if(!isset($output[$start]['average']))
			  $output[$start]['average'] = 0;

		  if(!isset($output[$start]['total']))
			  $output[$start]['total'] = 0;

		  $start = $start + 86400;
	  }

	  ksort($output);

	  // Turn into string for graph
	  $labels = '';
	  $user_data = '';
	  $average_data = '';
	  $total_data = '';
	  foreach($output as $date => $item){
		  $labels .= '"' . date('D', $date) . '", ';
		  $user_data .= $item['user'] . ', ';
		  $average_data .= $item['average'] . ', ';
		  $total_data .= $item['total'] . ', ';
	  }
	  $labels = '[' . rtrim($labels, ', ') . ']';
	  $user_data = '[' . rtrim($user_data, ', ') . ']';
	  $average_data = '[' . rtrim($average_data, ', ') . ']';
	  $total_data = '[' . rtrim($total_data, ', ') . ']';

	  $smarty->assign('FORUM_GRAPH', $forum_language->get('forum', 'last_7_days_posts'));
  }
	
	$smarty->display(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/user/index.tpl');

	require(ROOT_PATH . '/core/templates/scripts.php');

	if(isset($forum_enabled)){
	  ?>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/moment/moment.min.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/charts/Chart.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var ctx = $("#dataChart").get(0).getContext("2d");

            var data = {
                labels: <?php echo $labels; ?>,
                datasets: [
                    {
                        label: "<?php echo $forum_language->get('forum', 'your_posts'); ?>",
                        fill: false,
                        borderColor: "rgba(255,12,0,0.5)",
                        pointBorderColor: "rgba(255,0,5,0.5)",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: <?php echo $user_data; ?>
                    },
                    {
                        label: "<?php echo $forum_language->get('forum', 'average_posts'); ?>",
                        fill: false,
                        borderColor: "#0004FF",
                        pointBorderColor: "#0004FF",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: <?php echo $average_data; ?>
                    },
                    {
                        label: "<?php echo $forum_language->get('forum', 'total_posts'); ?>",
                        fill: false,
                        borderColor: "#00931D",
                        pointBorderColor: "#00931D",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: <?php echo $total_data; ?>
                    },
                ]
            }

            var dataLineChart = new Chart(ctx, {
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
                        }]
                    }
                }
            });
        });
    </script>
    <?php
  }
	?>
	
  </body>
</html>