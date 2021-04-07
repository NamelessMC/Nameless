<?php

if (!isset($_SESSION['admin_setup']) || $_SESSION['admin_setup'] != true) {
	Redirect::to('?step=admin_account_setup');
	die();
}

$available_converters = array_filter(glob(ROOT_PATH . '/custom/converters/*'), 'is_dir');
$converters = array();

if (!empty($available_converters)) {
	foreach ($available_converters as $converter) {
		if (file_exists($converter . '/converter.php')) {
			$path = explode(DIRECTORY_SEPARATOR, $converter);
			$converters[] = $path[count($path) - 1];
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (!empty($converters)) {

		$validate = new Validate();
		$validation = $validate->check($_POST, [
            'db_address' => [
                Validate::REQUIRED => true,
            ],
			'db_port' => [
                Validate::REQUIRED => true,
            ],
            'db_username' => [
                Validate::REQUIRED => true,
            ],
            'db_name' => [
                Validate::REQUIRED => true,
            ],
        ]);

		if (!$validation->passed()) {

			$error = $language['database_error'];

		} else {

			$db_address = $_POST['db_address'];
			$db_port = $_POST['db_port'];
			$db_username = $_POST['db_username'];
			$db_password =  ((isset($_POST['db_password']) && !empty($_POST['db_password'])) ? str_replace('\'', '\\\'', $_POST['db_password']) : '');
			$db_name = $_POST['db_name'];

			$mysqli = new mysqli($db_address, $db_username, $db_password, $db_name, $db_port);
			if ($mysqli->connect_errno) {

				$error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;

			} else {

				$mysqli->close();

				if (!isset($_POST['converter']) || !in_array($_POST['converter'], $converters)) {

					$error = $language['unable_to_load_converter'];

				} else {

					$conn = DB_Custom::getInstance(Input::get('db_address'), Input::get('db_name'), Input::get('db_username'), $password, Input::get('db_port'));
					require_once(ROOT_PATH . '/custom/converters/' . $_POST['converter'] . '/converter.php');

					if (!isset($error)) {
						Redirect::to('?step=finish');
						die();
					}

				}

			}

		}

	}

}

?>

<?php if (isset($error)) { ?>
	<div class="ui error message">
		<?php echo $error; ?>
	</div>
<?php } ?>

<form action="" method="post">
	<div class="ui segments">
		<div class="ui secondary segment">
			<h4 class="ui header">
				<?php echo $language['convert']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<?php if (!empty($converters)) { ?>
				<div class="ui centered grid">
					<div class="sixteen wide mobile twelve wide tablet ten wide computer column">
						<div class="ui form">
							<?php
								$converter_options = array();
								foreach ($converters as $converter) {
									$converter_options[Output::getClean($converter)] = str_replace('_', ' ', Output::getClean($converter));
								}
								create_field('select', $language['converter'], 'converter', 'inputConverter', '', $converter_options);
								create_field('text', $language['database_address'], 'db_address', 'inputDBAddress', '127.0.0.1');
								create_field('text', $language['database_port'], 'db_port', 'inputDBPort', '3306');
								create_field('text', $language['database_username'], 'db_username', 'inputDBUsername');
								create_field('text', $language['database_password'], 'db_password', 'inputDBPassword');
								create_field('text', $language['database_name'], 'db_name', 'inputDBName');
							?>
						</div>
					</div>
				</div>
			<?php } else { ?>
				<p><?php echo $language['no_converters_available']; ?></p>
			<?php } ?>
		</div>
		<div class="ui secondary right aligned segment">
			<a href="?step=conversion" class="ui small button">
				<?php echo $language['back']; ?>
			</a>
			<?php if (!empty($converters)) { ?>
				<button type="submit" class="ui small primary button">
					<?php echo $language['proceed']; ?>
				</button>
			<?php } else { ?>
				<a href="?step=finish" class="ui small primary button">
					<?php echo $language['proceed']; ?>
				</a>
			<?php } ?>
		</div>
	</div>
</form>