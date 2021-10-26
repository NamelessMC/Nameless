<?php
if (isset($_POST) && isset($_POST['perform']) && $_POST['perform'] == 'true') {
	try {
		if ($_GET['initialise'] === 'db') {
			$charset = $_SESSION['charset'];
			$engine = $_SESSION['engine'];

			$queries = new Queries();
			$success = $queries->dbInitialise($charset, $engine);

			$redirect_url = (($_SESSION['action'] == 'install') ? '?step=site_configuration' : '?step=upgrade');

			$json = array(
				'success' => $success,
				'redirect_url' => $redirect_url,
			);

			$_SESSION['database_initialized'] = true;

		} else if ($_GET['initialise'] === 'site') {
			require(realpath(__DIR__ . '/../includes/site_initialize.php'));

			$json = array(
				'success' => true,
				'redirect_url' => '?step=admin_account_setup',
			);

			$_SESSION['site_initialized'] = true;

		} else if ($_GET['initialise'] === 'upgrade') {
            require(realpath(__DIR__ . '/../includes/upgrade_perform.php'));

			$json = array(
				'success' => true,
				'redirect_url' => '?step=finish',
			);

			$_SESSION['admin_setup'] = true;

		} else {
			throw new Exception('Invalid initialisation');
		}
	} catch (Exception $e) {
		$json = array(
			'error' => true,
			'message' => $e->getMessage(),
		);
	}

	header('Content-Type: application/json');
	echo json_encode($json);
	die();
}
