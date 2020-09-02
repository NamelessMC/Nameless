<?php

if (isset($_SESSION['site_initialized']) && $_SESSION['site_initialized'] == true) {
	Redirect::to('?step=admin_account_setup');
	die();
}

if (!isset($_SESSION['database_initialized']) || $_SESSION['database_initialized'] != true) {
	Redirect::to('?step=database_configuration');
	die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['perform']) && $_POST['perform'] == 'true') {

		try {

			require(realpath(__DIR__ . '/../includes/site_initialize.php'));

			$json = array(
				'success' => true,
				'redirect_url' => '?step=admin_account_setup',
			);

			$_SESSION['site_initialized'] = true;

		} catch (Exception $e) {

			$json = array(
				'error' => true,
				'message' => $e->getMessage(),
			);

		}

		ob_clean();
		header('Content-Type: application/json');
		echo json_encode($json);
		die();

	}

}

?>

<div class="ui segments">
	<div class="ui secondary segment">
		<h4 class="ui header">
			<?php echo $language['configuration']; ?>
		</h4>
	</div>
	<div class="ui segment">
		<span id="info">
			<i class="blue circular notched circle loading icon"></i>
			<?php echo $language['initialising_database_and_cache']; ?>
		</span>
	</div>
	<div class="ui right aligned secondary segment">
		<a href="#" class="ui small primary disabled button" id="continue-button">
			<?php echo $language['continue']; ?>
		</a>
	</div>
</div>

<script>

	window.addEventListener('load', function() {
		$.post(window.location.href, {perform: 'true'}, function(response) {
			if (!response.message) {
				window.location.replace(response.redirect_url);
			} else {
				$('#info').html(response.message);
				if (response.redirect_url) {
					$('#continue-button').attr('href', response.redirect_url);
					$('#continue-button').removeClass('disabled');
				}
				if (response.error) {
					$('#continue-button').before('<button onclick="window.location.reload()" class="ui small button" id="reload-button"><?php echo $language['reload']; ?></button>');
				}
			}
		});
	});
	
</script>