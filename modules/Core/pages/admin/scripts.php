    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/jquery.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/tether.min.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/js/bootstrap.min.js"></script>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/toastr/toastr.min.js"></script>
	
	<script type="text/javascript">
	  $(function () {
		$('[data-toggle="tooltip"]').tooltip()
	  });
	  $(function () {
		$('[data-toggle="popover"]').popover({
		  placement: 'top',
		  trigger: 'hover'
		})
	  });
	</script>
	
	<script type="text/javascript">
	  function nightMode(){
		$.ajax({
		  'url' : '<?php echo URL::build('/admin/night_mode'); ?>',
		  'type' : 'GET',
		  'success' : function(data) {
			if(data == "OK"){
			  window.location.reload();
		    }
		  }
		});
	  }
	</script>