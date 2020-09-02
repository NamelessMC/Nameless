<?php

$s = (isset($_GET['s']) ? (int)$_GET['s'] : 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['perform']) && $_POST['perform'] == 'true') {

		try {

			require(realpath(__DIR__ . '/../includes/upgrade_perform.php'));
			$redirect_url = ($s < 9 ? '?step=upgrade_perform&s=' . ($s + 1) : '?step=finish');
			
			if (!empty($errors)) {

				if (!isset($message)) {
					$message = '<p>' . $language['errors_logged'] . '</p>';
					$message .= '<div class="ui bulleted list">' . implode('', array_map(function($err) {
						return '<div class="item">' . $err . '</div>';
					}, $errors)) . '</div>';
				}

				$json = array(
					'error' => true,
					'message' => $message,
					'redirect_url' => $redirect_url,
				);

			} else {

				$json = array(
					'success' => true,
					'message' => $message,
					'redirect_url' => $redirect_url,
				);

			}

		} catch (Exception $e) {

			$json = array(
				'error' => true,
				'message' => $e->getMessage(),
				'redirect_url' => '',
			);

		}

		ob_clean();
		header('Content-Type: application/json');
		echo json_encode($json);
		die();

	}

}

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