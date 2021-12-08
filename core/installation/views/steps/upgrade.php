<?php

if (!isset($_SESSION['database_initialized']) || $_SESSION['database_initialized'] != true) {
	Redirect::to('?step=database_configuration');
	die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$validate = new Validate();
	$validation = $validate->check($_POST, [
        'db_address' => [
            Validate::REQUIRED => true
        ],
        'db_port' => [
            Validate::REQUIRED => true
        ],
        'db_username' => [
            Validate::REQUIRED => true
        ],
		'db_name' => [
            Validate::REQUIRED => true
        ],
    ]);

	if (!$validation->passed()) {

		$error = $language['database_error'];

	} else {

		$db_address = $_POST['db_address'];
		$db_port = $_POST['db_port'];
		$db_username = $_POST['db_username'];
		$db_password =  ((isset($_POST['db_password']) && !empty($_POST['db_password'])) ? $_POST['db_password'] : '');
		$db_name = $_POST['db_name'];

		$mysqli = new mysqli($db_address, $db_username, $db_password, $db_name, $db_port);
		if ($mysqli->connect_errno) {

			$error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;

		} else {

			$_SESSION['db_address'] = $db_address;
			$_SESSION['db_port'] = $db_port;
			$_SESSION['db_username'] = $db_username;
			$_SESSION['db_password'] = $db_password;
			$_SESSION['db_name'] = $db_name;
			
			Redirect::to('?step=upgrade_perform');
			die();

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
				<?php echo $language['upgrade']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<p><?php echo $language['input_v1_details']; ?></p>
			<div class="ui centered grid">
				<div class="sixteen wide mobile twelve wide tablet ten wide computer column">
					<div class="ui form">
						<?php
							create_field('text', $language['database_address'], 'db_address', 'inputDBAddress', '127.0.0.1');
							create_field('text', $language['database_port'], 'db_port', 'inputDBPort', '3306');
							create_field('text', $language['database_username'], 'db_username', 'inputDBUsername');
							create_field('password', $language['database_password'], 'db_password', 'inputDBPassword');
							create_field('text', $language['database_name'], 'db_name', 'inputDBName');
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="ui secondary right aligned segment">
			<button type="submit" class="ui small primary button">
				<?php echo $language['proceed']; ?>
			</button>
		</div>
	</div>
</form>
