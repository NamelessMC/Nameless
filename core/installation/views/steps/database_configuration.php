<?php

if (isset($_SESSION['database_initialized']) && $_SESSION['database_initialized'] == true) {
	Redirect::to('?step=site_configuration');
	die();
}

if (!isset($_SESSION['hostname']) || !isset($_SESSION['install_path']) || !isset($_SESSION['friendly_urls'])) {
	Redirect::to('?step=general_configuration');
	die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'db_address' => array(
			'required' => true,
		),
		'db_port' => array(
			'required' => true,
		),
		'db_username' => array(
			'required' => true,
		),
		'db_name' => array(
			'required' => true,
		),
	));

	if (!$validation->passed()) {

		$error = $language['database_error'];

	} else {

		$db_address = $_POST['db_address'];
		$db_port = $_POST['db_port'];
		$db_username = $_POST['db_username'];
		$db_password =  ((isset($_POST['db_password']) && !empty($_POST['db_password'])) ? str_replace('\'', '\\\'', $_POST['db_password']) : '');
		$db_name = $_POST['db_name'];

		$charset = ($_POST['charset'] == 'latin1') ? 'latin1' : 'utf8mb4';
		$engine = ($_POST['engine'] == 'MyISAM') ? 'MyISAM' : 'InnoDB';

		$mysqli = new mysqli($db_address, $db_username, $db_password, $db_name, $db_port);
		if ($mysqli->connect_errno) {

			$error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;

		} else {

			$mysqli->close();
			
			$conf = array(
				'mysql' => array(
					'host' => $db_address,
					'port' => $db_port,
					'username' => $db_username,
					'password' => $db_password,
					'db' => $db_name,
					'prefix' => 'nl2_',
					'charset' => $charset,
					'engine' => $engine,
				),
				'remember' => array(
					'cookie_name' => 'nl2',
					'cookie_expiry' => 604800,
				),
				'session' => array(
					'session_name' => '2user',
					'admin_name' => '2admin',
					'token_name' => '2token',
				),
				'core' => array(
					'hostname' => $_SESSION['hostname'],
					'path' => $_SESSION['install_path'],
					'friendly' => $_SESSION['friendly_urls'] === true ? true : false,
				),
				'allowedProxies' => '',
			);

			try {

				if (!is_writable('core/config.php')) {

					$error = $language['config_not_writable'];

				} else {

					$config_content = '<?php' . PHP_EOL . '$conf = ' . var_export($conf, true) . ';';
					file_put_contents('core/config.php', $config_content);

					$_SESSION['charset'] = $charset;
					$_SESSION['engine'] = $engine;

					Redirect::to('?step=database_initialization');
					die();

				}

			} catch (Exception $e) {

				$error = $e->getMessage();
				
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
				<?php echo $language['database_configuration']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<div class="ui centered grid">
				<div class="sixteen wide mobile twelve wide tablet ten wide computer column">
					<div class="ui form">
						<?php
							create_field('text', $language['database_address'], 'db_address', 'inputDBAddress', '127.0.0.1');
							create_field('text', $language['database_port'], 'db_port', 'inputDBPort', '3306');
							create_field('text', $language['database_username'], 'db_username', 'inputDBUsername');
							create_field('password', $language['database_password'], 'db_password', 'inputDBPassword');
							create_field('text', $language['database_name'], 'db_name', 'inputDBName');
							create_field('select', $language['character_set'], 'charset', 'inputCharset', 'utf8mb4', array(
								'utf8mb4' => 'Unicode (utf8mb4)',
								'latin1' => 'Latin (latin1)',
							));
							create_field('select', $language['database_engine'], 'engine', 'inputEngine', 'InnoDB', array(
								'InnoDB' => 'InnoDB',
								'MyISAM' => 'MyISAM',
							));
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