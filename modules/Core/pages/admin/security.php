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
$admin_page = 'security';

?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
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
			  <h3><?php echo $language->get('admin', 'security'); ?></h3>
			  <?php if(!isset($_GET['view'])){ ?>
			  <p><strong><?php echo $language->get('admin', 'please_select_logs'); ?></strong></p>
			  <ul>
			    <li><a href="<?php echo URL::build('/admin/security/', 'view=acp_logins'); ?>"><?php echo $language->get('admin', 'acp_logins'); ?></a></li>
				<li><a href="<?php echo URL::build('/admin/security/', 'view=template_changes'); ?>"><?php echo $language->get('admin', 'template_changes'); ?></a></li>
			  </ul>
			  <?php 
			  } else {
				  switch($_GET['view']){
					  case 'acp_logins':
					    // Successful AdminCP logins
						echo '<strong>' . $language->get('admin', 'acp_logins') . '</strong>';
						
						// Get logs
						$logs = $queries->orderWhere('logs', 'action = \'acp_login\'', 'time', 'DESC');
						?>
			  <hr>
			  <div class="table-responsive">
				<table class="table table-bordered table-hover dataTable">
				  <colgroup>
					<col span="1" style="width: 33%;">
					<col span="1" style="width: 33%;">
					<col span="1" style="width: 33%">
				  </colgroup>
				  <thead>
					<tr>
					  <td><?php echo $language->get('user', 'username'); ?></td>
					  <td><?php echo $language->get('admin', 'ip_address'); ?></td>
					  <td><?php echo $language->get('general', 'date'); ?></td>
					</tr>
				  </thead>
				  <tbody>
				    <?php foreach($logs as $log){ ?>
				    <tr>
					  <td><a target="_blank" style="<?php echo $user->getGroupClass($log->user_id); ?>" href="<?php echo URL::build('/profile/' . Output::getClean($user->idToName($log->user_id))); ?>"><?php echo Output::getClean($user->idToNickname($log->user_id)); ?></a></td>
					  <td><a target="_blank" href="<?php echo URL::build('/mod/ip_lookup/', 'ip=' . Output::getClean($log->ip)); ?>"><?php echo Output::getClean($log->ip); ?></a></td>
					  <td data-order="<?php echo $log->time; ?>"><?php echo date('jS M Y, g:iA', $log->time); ?></td>
				    </tr>
					<?php } ?>
				  </tbody>
			    </table>
			  </div>
						<?php
					  break;
					  case 'template_changes':
					    // Template changes
						echo '<strong>' . $language->get('admin', 'template_changes') . '</strong>';
						
						// Get logs
						$logs = $queries->orderWhere('logs', 'action = \'acp_template_update\'', 'time', 'DESC');
						
						?>
			  <hr>
			  <div class="table-responsive">
				<table class="table table-bordered table-hover dataTable">
				  <colgroup>
					<col span="1" style="width: 25%;">
					<col span="1" style="width: 25%;">
					<col span="1" style="width: 25%">
					<col span="1" style="width: 25%">
				  </colgroup>
				  <thead>
					<tr>
					  <td><?php echo $language->get('user', 'username'); ?></td>
					  <td><?php echo $language->get('admin', 'ip_address'); ?></td>
					  <td><?php echo $language->get('general', 'date'); ?></td>
					  <td><?php echo $language->get('admin', 'file_changed'); ?></td>
					</tr>
				  </thead>
				  <tbody>
				    <?php foreach($logs as $log){ ?>
				    <tr>
					  <td><a target="_blank" style="<?php echo $user->getGroupClass($log->user_id); ?>" href="<?php echo URL::build('/profile/' . Output::getClean($user->idToName($log->user_id))); ?>"><?php echo Output::getClean($user->idToNickname($log->user_id)); ?></a></td>
					  <td><a target="_blank" href="<?php echo URL::build('/mod/ip_lookup/', 'ip=' . Output::getClean($log->ip)); ?>"><?php echo Output::getClean($log->ip); ?></a></td>
					  <td data-order="<?php echo $log->time; ?>"><?php echo date('jS M Y, g:iA', $log->time); ?></td>
					  <td><?php echo Output::getClean($log->info); ?></td>
				    </tr>
					<?php } ?>
				  </tbody>
			    </table>
			  </div>
						<?php
					  break;
				  }
			  }
			  ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
	
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dataTables/jquery.dataTables.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
	
	<script type="text/javascript">
        $(document).ready(function() {
            $('.dataTable').dataTable({
                responsive: true,
				language: {
					"lengthMenu": "<?php echo $language->get('table', 'display_records_per_page'); ?>",
					"zeroRecords": "<?php echo $language->get('table', 'nothing_found'); ?>",
					"info": "<?php echo $language->get('table', 'page_x_of_y'); ?>",
					"infoEmpty": "<?php echo $language->get('table', 'no_records'); ?>",
					"infoFiltered": "<?php echo $language->get('table', 'filtered'); ?>",
					"search": "<?php echo $language->get('general', 'search'); ?> "
				},
                order: [[ 2, 'desc']]
            });
		});
	</script>
	
  </body>
</html>