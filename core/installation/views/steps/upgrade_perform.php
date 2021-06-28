<?php

$scripts = array(
	'
	<script>
		$(document).ready(function() {
			$.post("?step=ajax_initialise&initialise=upgrade", {perform: "true"}, function(response) {
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

<form action="" method="post">
	<div class="ui segments">
		<div class="ui secondary segment">
			<h4 class="ui header">
				<?php echo $language['upgrade']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<span id="info">
				<i class="blue circular notched circle loading icon"></i>
				<?php echo $language['installer_upgrading_database']; ?>
			</span>
		</div>
		<div class="ui right aligned secondary segment">
			<a href="#" class="ui primary disabled button" id="continue-button">
				<?php echo $language['continue']; ?>
			</a>
		</div>
	</div>
</form>