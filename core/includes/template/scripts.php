<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
/*
 *  Load scripts
 */

echo '<script src="' . PATH . 'core/assets/js/jquery.min.js"></script>' . PHP_EOL;
echo '	<script src="' . PATH . 'styles/themes/' . $theme_result . '/js/bootstrap.min.js"></script>' . PHP_EOL;
echo '	<script src="' . PATH . 'core/assets/js/jquery.cookie.js"></script>' . PHP_EOL;
echo '	<script src="' . PATH . 'core/assets/js/toastr.js"></script>' . PHP_EOL;
?>
	<script type="text/javascript">
	jQuery(function( $ ){
		<?php
		if(!$user->isLoggedIn()){
		?>
		// Check if cookie alert has been closed
		if( $.cookie('alert-box') === 'closed' ){

			$('.alert-cookie').hide();

		}

		 // Grab your button (based on your posted html)
		$('.close-cookie').click(function( e ){

			// Do not perform default action when button is clicked
			e.preventDefault();

			/* If you just want the cookie for a session don't provide an expires
			 Set the path as root, so the cookie will be valid across the whole site */
			$.cookie('alert-box', 'closed', { path: '/' });

		});
		<?php
		}
		?>
		
		// Announcements
		$("div[class*='alert-announcement-']").each(function() {
			var id = this.id;
			
			// Check if cookie alert has been closed
			if( $.cookie('announcement-' + this.id) === 'closed' ){

				$(this).hide();

			}

			// Grab your button (based on your posted html)
			$('.close-announcement').click(function( e ){

				// Do not perform default action when button is clicked
				e.preventDefault();
				
				/* If you just want the cookie for a session don't provide an expires
				 Set the path as root, so the cookie will be valid across the whole site */
				$.cookie('announcement-' + id, 'closed', { path: '/' });

			});
		});

	});
	</script>
<?php
foreach($custom_js as $item){
	echo $item;
}
?>
	<script type="text/javascript">
	<?php if($user->isLoggedIn()){ ?>
	$(document).ready(function() {
		toastr.options.closeButton = true;
		toastr.options.timeOut = 0;
		toastr.options.positionClass = 'toast-bottom-left';
		
		// Get alerts and messages, and then set them to refresh every 20 seconds
		$.getJSON('/core/queries/private_messages.php?uid=<?php echo $user->data()->id; ?>', function(data) {
			if(data.value > 0 && $('#pms').is(':empty')){
				$("#pms").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
			}
		});
		$.getJSON('/core/queries/alerts.php?uid=<?php echo $user->data()->id; ?>', function(data) {
			if(data.value > 0 && $('#alerts').is(':empty')){
				$("#alerts").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
			}
		});
		
		window.setInterval(function(){
		  $.getJSON('/core/queries/private_messages.php?uid=<?php echo $user->data()->id; ?>', function(data) {
			if(data.value > 0 && $('#pms').is(':empty')){
				$("#pms").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
				toastr.info('You have ' + data.value + ' new messages');
			}
		  });
		  $.getJSON('/core/queries/alerts.php?uid=<?php echo $user->data()->id; ?>', function(data) {
			if(data.value > 0 && $('#alerts').is(':empty')){
				$("#alerts").html(' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>');
				toastr.info('You have ' + data.value + ' new alerts');
			}
		  });
		}, 20000);
	});
	<?php } ?>
	// popovers
	$(function () { $("[data-toggle='popover']").popover({trigger: 'hover', placement: 'top'}); });
	// tooltips
	$(document).ready(function(){
		$("[rel=tooltip]").tooltip({ placement: 'top'});
	});
	<?php
	// End page load timer
	$page_load = microtime(true) - $start;
	?>
	var timer = 'Page loaded in <?php echo round($page_load, 3); ?>s';
	$('#page_load_tooltip').attr('title', timer).tooltip('fixTitle');
	
    $(function() {
        $(".alert-dropdown").hover(
            function(){ $(this).addClass('open') },
            function(){ $(this).removeClass('open') }
        );
    });	
	</script>