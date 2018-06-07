<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Admin Update page
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
		} else if(!$user->hasPermission('admincp.update')){
            // Can't view this page
            require(ROOT_PATH . '/404.php');
            die();
        }
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
$page = 'admin';
$admin_page = 'update';

// Check for updates
$current_version = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
$current_version = $current_version[0]->value;

$uid = $queries->getWhere('settings', array('name', '=', 'unique_id'));
$uid = $uid[0]->value;

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/nl_core/nl2/stats.php?uid=' . $uid . '&version=' . $current_version);

$update_check = curl_exec($ch);

if(curl_error($ch)){
	$error = curl_error($ch);
} else {
	if($update_check == 'Failed'){
		$error = 'Unknown error';
	}
}

curl_close($ch);
?>
<!DOCTYPE html>
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
			  <h3><?php echo $language->get('admin', 'update'); ?></h3>
			  <?php
			  if(!isset($error)){
				  if($update_check == 'None'){
					  echo '<div class="alert alert-success">' . $language->get('admin', 'up_to_date') . '</div>';
				  } else {
					  // Update database value to say when we last checked
					  $update_needed_id = $queries->getWhere('settings', array('name', '=', 'version_checked'));
					  $update_needed_id = $update_needed_id[0]->id;
					  $queries->update('settings', $update_needed_id, array(
						  'value' => date('U')
					  ));

					  echo '<p><strong>' . $language->get('admin', 'new_update_available') . '</strong></p>';
					  $update_check = json_decode($update_check);

                      // Update new version in database
                      $new_version_id = $queries->getWhere('settings', array('name', '=', 'new_version'));
                      if(count($new_version_id)) {
                          $new_version_id = $new_version_id[0]->id;
                          $queries->update('settings', $new_version_id, array(
                              'value' => $update_check->new_version
                          ));
                      } else {
                          $queries->create('settings', array(
                              'name' => 'new_version',
                              'value' => $update_check->new_version
                          ));
                      }

					  if(isset($update_check->urgent) && $update_check->urgent == 'true'){
						  echo '<div class="alert alert-danger">' . $language->get('admin', 'urgent') . '</div>';
						  $need_update = 'urgent';
					  } else {
                          $need_update = 'true';
                      }
                      // Update database values to say we need a version update
                      $update_needed_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
                      $update_needed_id = $update_needed_id[0]->id;
                      $queries->update('settings', $update_needed_id, array(
                          'value' => $need_update
                      ));
					  
					  echo '<ul><li>' . str_replace('{x}', Output::getClean($current_version), $language->get('admin', 'current_version_x')) . '</li>
					  <li>' . str_replace('{x}', Output::getClean($update_check->new_version), $language->get('admin', 'new_version_x')) . '</li></ul>';
					  
					  echo '<h4>' . $language->get('admin', 'instructions') . '</h4>';
					  // Get instructions
					  $ch = curl_init();
					  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                      curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/nl_core/nl2/instructions.php?uid=' . $uid . '&version=' . $current_version);

					  $instructions = curl_exec($ch);

					  if(curl_error($ch)){
					 	$instructions_error = curl_error($ch);
					  } else {
						if($instructions == 'Failed'){
							$instructions_error = 'Unknown error';
						}
					  }

					  curl_close($ch);
					  
					  echo Output::getPurified($instructions);
					  
					  echo '<hr />';

                      echo '<a href="https://namelessmc.com/nl_core/nl2/updates/' . str_replace(array('.', '-'), '', Output::getClean($update_check->new_version)) . '.zip" class="btn btn-primary">' . $language->get('admin', 'download') . '</a> ';
				      echo '<a href="' . URL::build('/admin/update_execute') . '" class="btn btn-info" onclick="return confirm(\'' . $language->get('admin', 'install_confirm') . '\');">' . $language->get('admin', 'update') . '</a>';
				  }
			  } else {
			  ?>
			  <div class="alert alert-danger">
			    <?php echo $language->get('admin', 'update_check_error'); ?><br />
				<?php echo $error; ?>
			  </div>
			  <?php
			  }
			  ?>
		    </div>
		  </div>
		</div>
	  </div>

    </div>
	
	<?php 
	require(ROOT_PATH . '/modules/Core/pages/admin/footer.php');
	require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php');
	?>
	
  </body>
</html>
