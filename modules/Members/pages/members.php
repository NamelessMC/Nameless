<?php
/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *
 *  License: MIT
 */

// Always define page name
define('PAGE', 'members');
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
	$title = $language->get('general', 'home');
	require('core/templates/header.php'); 
	?>
  
  </head>
  <body>
  
  
  
  
  
  
<div class="container" style="padding-top: 5rem;">
	<div class="card">
	<div class="card-block">
	  <div class="row">
		<div class="col-md-12">
		  
		  <?php
			  $users = $queries->orderAll("users", "USERNAME", "ASC");
			  $groups = $queries->getAll("groups", array("id", "<>", 0));
		  ?>
		  
			  <table class="table table-striped table-bordered table-hover dataTables-users" >
			    <thead>
				  <tr>
				    <th><?php echo $members_language->get('members', 'username'); ?></th>
				    <th><?php echo $members_language->get('members', 'group'); ?></th>
				    <th><?php echo $members_language->get('members', 'created'); ?></th>
				  </tr>
			    </thead>
			    <tbody>
				  <?php
				  foreach($users as $individual){
					if(isset($selected_staff_group)){
						$user_group = $selected_staff_group->group_html;
					} else {
						$user_group = "";
						foreach($groups as $group){
							if($group->id === $individual->group_id){
							  $user_group = $group->group_html;
							  break;
							}
						}
					}
					// Get avatar
					$avatar = '<img class="img-rounded" style="width:35px; height:35px;" src="' . $user->getAvatar($individual->id, "../", 35) . '" />';
				  ?>
				  <tr>
				    <td><?php echo ($avatar) ?> <a href="<?php echo URL::build('/profile/' . $individual->username); ?>"><?php echo htmlspecialchars($individual->username); ?></a></td>
				    <td><?php echo $user_group; ?></td>
				    <td><?php echo date('d M Y', $individual->joined); ?></td>
				  </tr>
				  <?php
				  }
				  ?>
			    </tbody>
			  </table>
		</div>
		</div>
    </div>
	</div>
</div>
	
	
	
	
	
	
	
	
	
	
    <?php 
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	
	$smarty->display('custom/templates/' . TEMPLATE . '/members.tpl');

    require('core/templates/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dataTables/jquery.dataTables.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dataTables/dataTables.bootstrap.js"></script>

	<script type="text/javascript">
        $(document).ready(function() {
            $('.dataTables-users').dataTable({
                responsive: true,
				language: {
					"lengthMenu": "<?php echo $language->get('table', 'display_records_per_page'); ?>",
					"zeroRecords": "<?php echo $language->get('table', 'nothing_found'); ?>",
					"info": "<?php echo $language->get('table', 'page_x_of_y'); ?>",
					"infoEmpty": "<?php echo $language->get('table', 'no_records'); ?>",
					"infoFiltered": "<?php echo $language->get('table', 'filtered'); ?>",
					"search": "<?php echo $language->get('general', 'search'); ?> "
				}
            });
		});
	</script>

  </body>
</html>