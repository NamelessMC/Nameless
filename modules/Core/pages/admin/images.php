<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Admin images page
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
$admin_page = 'styles';

// Reset background
if(isset($_GET['action']) && $_GET['action'] == 'reset_bg'){
	$cache->setCache('backgroundcache');
	$cache->store('background_image', '');
	
	Redirect::to(URL::build('/admin/images'));
	die();
}


// Deal with input
if(Input::exists()){
	// Check token
	if(Token::check(Input::get('token'))){
		// Valid token
		$cache->setCache('backgroundcache');
		$cache->store('background_image', ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/backgrounds/' . Input::get('bg'));
		
	} else {
		// Invalid token
		Session::flash('admin_images', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
	}
}

// Generate token for multiple forms
$token = Token::generate();
?>
<!DOCTYPE html>
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
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dropzone/dropzone.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.css">
	
	<style type="text/css">
		.thumbnails li img{
			width: 200px;
		}
	</style>
  
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
			  <ul class="nav nav-pills">
				<li class="nav-item">
				  <a class="nav-link" href="<?php echo URL::build('/admin/styles'); ?>"><?php echo $language->get('admin', 'templates'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link active"><?php echo $language->get('admin', 'images'); ?></a>
				</li>
			  </ul>
		      <hr />
			  <h3 style="display:inline;"><?php echo $language->get('admin', 'images'); ?></h3>
			  <span class="pull-right"><button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal"><?php echo $language->get('admin', 'upload_new_image'); ?></button></span>
			  <hr>
			  <?php
				// Check session
				if(Session::exists('admin_images')){
					echo Session::flash('admin_images');
				}
			  
			    // Get background from cache
				$cache->setCache('backgroundcache');
				$background_image = $cache->retrieve('background_image');
			  ?>
			  Background image: <?php if($background_image == '') echo '<strong>' . $language->get('general', 'none') . '</strong>'; else echo '<strong>' . Output::getClean($background_image) . '</strong>'; ?>
			  <form action="" method="post" style="display:inline;" >
				<select name="bg" class="image-picker show-html">
				  <?php
				  $image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'backgrounds'));
				  $images = scandir($image_path);

				  // Only display jpeg, png, jpg, gif
				  $allowed_exts = array('gif', 'png', 'jpg', 'jpeg');
				  
				  foreach($images as $image){
					  $ext = pathinfo($image, PATHINFO_EXTENSION);
					  if(!in_array($ext, $allowed_exts)){
						continue;
					  }
				  ?>
				  <option data-img-src="<?php echo ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/'); ?>uploads/backgrounds/<?php echo $image; ?>" value="<?php echo Output::getClean($image); ?>" <?php if($background_image == '/uploads/backgrounds/' . $image) echo 'selected'; ?>><?php echo $n; ?></option>
				  <?php
				  }
				  ?>
				</select>
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
			  </form>
			  <a href="<?php echo URL::build('/admin/images/', 'action=reset_bg'); ?>" class="btn btn-danger"><?php echo $language->get('admin', 'reset_background'); ?></a>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
	
	<!-- Modal -->
	<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="uploadModalLabel"><?php echo $language->get('admin', 'upload_new_image'); ?></h4>
		  </div>
		  <div class="modal-body">
			<!-- Upload modal -->
			<form action="/core/includes/image_upload.php" class="dropzone" id="upload_background_dropzone">
			<div class="dz-message" data-dz-message><span><?php echo $language->get('admin', 'drag_files_here'); ?></span></div>
			  <input type="hidden" name="token" value="<?php echo $token; ?>">
			  <input type="hidden" name="type" value="background">
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-danger" onclick="location.reload();" data-dismiss="modal"><?php echo $language->get('general', 'cancel'); ?></button>
		  </div>
		</div>
	  </div>
	</div>
	  
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dropzone/dropzone.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.min.js"></script>
	
	<script>
	// Dropzone options
	Dropzone.options.upload_background_dropzone = {
	  maxFilesize: 2,
	  dictDefaultMessage: "<?php echo $language->get('admin', 'drag_files_here'); ?>",
	  dictInvalidFileType: "<?php echo $language->get('admin', 'invalid_file_type'); ?>",
	  dictFileTooBig: "<?php echo $language->get('admin', 'file_too_big'); ?>"
	};
	
	$("select").imagepicker();
	</script>
	
  </body>
</html>