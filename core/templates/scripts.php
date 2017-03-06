    <?php
	if(isset($js_sources)){
		foreach($js_sources as $script){
		?>
	<script src="<?php echo htmlspecialchars($script); ?>"></script>
		<?php
		}
	} else {
	?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/jquery.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/tether.min.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/bootstrap.min.js"></script>
	<?php
	}
	if(isset($js)) echo $js;
	?>

	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/toastr/toastr.min.js"></script>
