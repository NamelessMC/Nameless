	<!-- Page Title -->
	<title><?php echo $title; ?> &bull; <?php echo SITE_NAME; ?></title>

	<!-- Global CSS -->
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/custom.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/font-awesome.min.css">

	<?php
	// Dark mode?
	if($user->isLoggedIn()){
		if($user->data()->night_mode == 1){
			echo '<link rel="stylesheet" href="' . (defined('CONFIG_PATH') ? CONFIG_PATH . '/' : '/') . 'core/assets/css/cp_dark.css">';
		}
	}
	?>

	<style>
	html {
		overflow-y: scroll;
	}
	<?php if(defined('HTML_RTL')){ ?>
	.pull-right {
		float: left !important;
	}
	<?php } ?>
	</style> 