	<!-- Page Title -->
	<title><?php echo $title; ?> &bull; <?php echo SITE_NAME; ?></title>
	
	<meta name="author" content="<?php echo SITE_NAME; ?>">

	<!-- Global CSS -->
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/toastr/toastr.min.css">
	
	<?php if($page == 'admin'){ ?>
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/custom.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/font-awesome.min.css">
	<?php 
	} else { 
		require('custom/templates/' . TEMPLATE . '/template.php');
		foreach($css as $item){
			?>
	<link rel="stylesheet" href="<?php echo $item; ?>">
			<?php
		}
	} 
	?>
	
	<?php
	// Background?
	$cache->setCache('backgroundcache');
	$background_image = $cache->retrieve('background_image');
	
	if(!empty($background_image)){
	?>
	<style>
	body {
		background-image: url('<?php echo $background_image; ?>');
		background-repeat: no-repeat;
		background-attachment: fixed;
	}
	</style>
	<?php
	}
	?>

	<style>
	html {
		overflow-y: scroll;
	}
	</style> 