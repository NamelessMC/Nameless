    <?php 
	if(isset($js_sources)){ 
		foreach($js_sources as $script){
		?>
	<script src="<?php echo htmlspecialchars($script); ?>"></script>
		<?php
		}
	} else { 
	?>
	<script src="/core/assets/js/jquery.min.js"></script>
	<script src="/core/assets/js/tether.min.js"></script>
    <script src="/core/assets/js/bootstrap.min.js"></script>
	<?php 
	} 
	if(isset($js)) echo $js;
	?>
	
	<script src="/core/assets/plugins/toastr/toastr.min.js"></script>
	
	<script type="text/javascript">
	  $(function () {
		$('[data-toggle="tooltip"]').tooltip()
	  });
	  $(function () {
		$('[rel="tooltip"]').tooltip()
	  });
	  
	  $('[data-toggle="popover"]').popover({ trigger: "manual" , html: true, animation:false}).on("mouseenter", function () {
		var _this = this;
		$(this).popover("show");
		$(".popover").on("mouseleave", function () {
			$(_this).popover('hide');
		});
	  }).on("mouseleave", function () {
		var _this = this;
		setTimeout(function () {
			if (!$(".popover:hover").length) {
				$(_this).popover("hide");
			}
		}, 300);
	  });
	</script>
	
	<script>
	function copyToClipboard(element) {
		var $temp = $("<input>")
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
		
		toastr.options.onclick = function () {};
		toastr.options.progressBar = true;
		toastr.options.closeButton = true;
		toastr.options.positionClass = 'toast-bottom-left'
		toastr.success('Copied!');
	}
	</script>
	
	<?php if($user->isLoggedIn()){ ?>
	<script type="text/javascript">
	<!-- Alerts -->
	$(document).ready(function() {
		// Request permission for browser notifications
		if(Notification){
			if (Notification.permission !== "granted")
			Notification.requestPermission();
		}
		
		toastr.options.closeButton = true;
		toastr.options.positionClass = 'toast-bottom-left';
		
		// Get alerts and messages, and then set them to refresh every 20 seconds
		$.getJSON('/queries/pms', function(data) {
			var pm_dropdown = document.getElementById('pm_dropdown');
			
			if(data.value > 0){
				$("#pms").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');

				if(pm_dropdown.innerHTML == '<?php echo $language->get('general', 'loading'); ?>'){
					
					var new_pm_dropdown = '';
					
					for(i in data.pms){ 
						new_pm_dropdown += '<a class="dropdown-item" href="<?php echo URL::build('/user/messages/', 'view='); ?>' + data.pms[i].id + '">' + data.pms[i].title + '</a>';
					}
					
					pm_dropdown.innerHTML = new_pm_dropdown;
				}

			} else {
				pm_dropdown.innerHTML = '<a class="dropdown-item"><?php echo $language->get('user', 'no_messages'); ?></a>';
			}
		});
		$.getJSON('/queries/alerts', function(data) {
			var alert_dropdown = document.getElementById('alert_dropdown');
			
			if(data.value > 0){
				$("#alerts").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');

				if(alert_dropdown.innerHTML == '<?php echo $language->get('general', 'loading'); ?>'){
					
					var new_alert_dropdown = '';
					
					for(i in data.alerts){ 
						new_alert_dropdown += '<a class="dropdown-item" href="<?php echo URL::build('/user/alerts/', 'view='); ?>' + data.alerts[i].id + '">' + data.alerts[i].content_short + '</a>';
					}
					
					alert_dropdown.innerHTML = new_alert_dropdown;
				}

			} else {
				alert_dropdown.innerHTML = '<a class="dropdown-item"><?php echo $language->get('user', 'no_alerts'); ?></a>';
			}
		});
		
		window.setInterval(function(){
		  $.getJSON('/queries/pms', function(data) {
			if(data.value > 0 && $('#pms').is(':empty')){
				$("#pms").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
				toastr.options.onclick = function () {
					window.location.href = "<?php echo URL::build('/user/messaging'); ?>";
				};
				
				var x_messages = '<?php echo $language->get('user', 'x_new_messages'); ?>';
				toastr.info(x_messages.replace("{x}", data.value));
				
				// Update navbar dropdown
				var pm_dropdown = document.getElementById('pm_dropdown');
				
				$("#pms").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
					
				var new_pm_dropdown = '';
				
				for(i in data.pms){ 
					new_pm_dropdown += '<a class="dropdown-item" href="<?php echo URL::build('/user/messages/', 'view='); ?>' + data.pms[i].id + '">' + data.pms[i].title + '</a>';
				}
				
				pm_dropdown.innerHTML = new_pm_dropdown;
				
				// Desktop notification
				if (Notification.permission !== "granted")
					Notification.requestPermission();
				else {
					var notification = new Notification('<?php echo SITE_NAME; ?>', {
						body: x_messages.replace("{x}", data.value),
					});

					notification.onclick = function () {
						// TODO
						//window.open("<?php echo URL::build('/user/messaging'); ?>");      
					};

				}
			}
		  });
		  $.getJSON('/queries/alerts', function(data) {
			if(data.value > 0 && $('#alerts').is(':empty')){
				$("#alerts").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
				toastr.options.onclick = function () {
					window.location.href = "<?php echo URL::build('/user/alerts'); ?>";
				};
				
				var x_alerts = '<?php echo $language->get('user', 'x_new_alerts'); ?>';
				toastr.info(x_alerts.replace("{x}", data.value));
				
				// Update navbar dropdown
				var alert_dropdown = document.getElementById('alert_dropdown');
				
				$("#alerts").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
					
				var new_alert_dropdown = '';
				
				for(i in data.alerts){ 
					new_alert_dropdown += '<a class="dropdown-item" href="<?php echo URL::build('/user/alerts/', 'view='); ?>' + data.alerts[i].id + '">' + data.alerts[i].content_short + '</a>';
				}
				
				alert_dropdown.innerHTML = new_alert_dropdown;
				
				// Desktop notification
				if (Notification.permission !== "granted")
					Notification.requestPermission();
				else {
					var notification = new Notification('<?php echo SITE_NAME; ?>', {
						body: x_alerts.replace("{x}", data.value),
					});

					notification.onclick = function () {
						// TODO
						//window.open("<?php echo URL::build('/user/alerts'); ?>");      
					};

				}
			}
		  });
		}, 200);
	});
	
    $('.alert-dropdown, .pm-dropdown').hover(
        function() {
            $(this).find('.dropdown-menu').stop(true, true).delay(25).fadeIn();
        }, 
        function() {
            $(this).find('.dropdown-menu').stop(true, true).delay(25).fadeOut();
        }
    );

    $('.alert-dropdown-menu, .pm-dropdown-menu').hover(
        function() {
            $(this).stop(true, true);
        },
        function() {
            $(this).stop(true, true).delay(25).fadeOut();
        }
    );
	
	</script>
	<?php }	?>