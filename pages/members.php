<?php 
/* 
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  Modified by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Members addon page
$page = 'members'; // for navbar

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Member list for the <?php echo $sitename; ?> community">
    <meta name="author" content="Partydragen, Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo $members_language['members']; ?></title>

	<?php
	// Generate header and navbar content
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
	  <div class="row">
		<div class="col-md-13">
		  <?php
		  $users = $queries->orderAll("users", "USERNAME", "ASC");
		  $groups = $queries->getAll("groups", array("id", "<>", 0));
		  ?>
		  </br>
		  <div class="panel panel-primary">
			<div class="panel-heading">
			  <h3 class="panel-title"><?php echo $members_language['members']; ?></h3>
			</div>

			<div class="panel-body">
			  <table class="table table-striped table-bordered table-hover dataTables-users" >
			    <thead>
				  <tr>
				    <th><?php echo $members_language['username']; ?></th>
				    <th><?php echo $members_language['group']; ?></th>
				    <th><?php echo $members_language['created']; ?></th>
				  </tr>
			    </thead>
			    <tbody>
				  <?php
				  foreach($users as $individual){
				  	$user_group = "";
					  foreach($groups as $group){
						if($group->id === $individual->group_id){
						  $user_group = $group->name;
						  break;
						}
					  }
				  ?>
				  <tr>
				    <td><img class="img-rounded" style="margin: -10px 0px;" src="https://cravatar.eu/avatar/<?php echo htmlspecialchars($individual->mcname); ?>"> <a href="/profile/<?php echo htmlspecialchars($individual->mcname); ?>"><?php echo htmlspecialchars($individual->username); ?></a></td>
				    <td><?php echo htmlspecialchars($user_group); ?></td>
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
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>

	<script src="/core/assets/js/tables/jquery.dataTables.min.js"></script>
	<script src="/core/assets/js/tables/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript">
        $(document).ready(function() {
            $('.dataTables-users').dataTable({
                responsive: true,
				language: {
					"lengthMenu": "<?php echo $table_language['display_records_per_page']; ?>",
					"zeroRecords": "<?php echo $table_language['nothing_found']; ?>",
					"info": "<?php echo $table_language['page_x_of_y']; ?>",
					"infoEmpty": "<?php echo $table_language['no_records']; ?>",
					"infoFiltered": "<?php echo $table_language['filtered']; ?>",
					"search": "<?php echo $general_language['search']; ?> "
				}
            });
		});
	</script>
  </body>
</html>