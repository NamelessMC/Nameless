<?php
if (isset($_SESSION['database_initialized']) && $_SESSION['database_initialized'] == true) {
	Redirect::to('?step=site_configuration');
	die();
}

if (!isset($_SESSION['charset']) || !isset($_SESSION['engine'])) {
	Redirect::to('?step=database_configuration');
	die();
}

$scripts = array(
	'
	<script>
		$(document).ready(function() {
			$.post("?step=ajax_initialise&initialise=db", {perform: "true"}, function(response) {
				if (response.success) {
					window.location.replace(response.redirect_url);
				} else {
					$("#info").html(response.message);
					if (response.redirect_url) {
						$("#continue-button").attr("href", response.redirect_url);
						$("#continue-button").removeClass("disabled");
					}
					if (response.error) {
						$("#continue-button").before("<button onclick=\"window.location.reload()\" class=\"ui small button\" id=\"reload-button\">' . $language['reload'] . '</button>");
					}
				}
			});
		});
	</script>
	'
);
?>

<div class="ui segments">
	<div class="ui secondary segment">
		<h4 class="ui header">
			<?php echo $language['database_configuration']; ?>
		</h4>
	</div>
	<div class="ui segment">
		<span id="info">
			<i class="blue circular notched circle loading icon"></i>
			<?php echo $language['installer_now_initialising_database']; ?>
		</span>
	</div>
	<div class="ui right aligned secondary segment">
		<a href="#" class="ui small primary disabled button" id="continue-button">
			<?php echo $language['continue']; ?>
		</a>
	</div>
</div>
