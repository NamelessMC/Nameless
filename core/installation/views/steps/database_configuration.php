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
					'initialise_charset' => true,
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
					'friendly' => $_SESSION['friendly_urls'] == 'true' ? true : false,
					'force_https' => false,
					'force_www' => false,
					'captcha' => false,
				),
				'allowedProxies' => '',
			);

			try {

				if (!is_writable(ROOT_PATH . '/core/config.php')) {

					$error = $language['config_not_writable'];

				} else {

					$config_content = '<?php' . PHP_EOL . '$conf = ' . var_export($conf, true) . ';';
					file_put_contents(ROOT_PATH . '/core/config.php', $config_content);

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
							$default_addr = getenv('NAMELESS_DATABASE_ADDRESS') ?: '127.0.0.1';
							$default_port = getenv('NAMELESS_DATABASE_PORT') ?: '3306';
							$default_user = getenv('NAMELESS_DATABASE_USERNAME') ?: 'root';
							$default_pass = getenv('NAMELESS_DATABASE_PASSWORD') ?: '';
							$default_name = getenv('NAMELESS_DATABASE_NAME') ?: 'nameless';
							$default_charset = getenv('NAMELESS_DATABASE_CHARSET') ?: 'utf8mb4';
							$default_engine = getenv('NAMELESS_DATABASE_ENGINE') ?: 'InnoDB';
							create_field('text', $language['database_address'], 'db_address', 'inputDBAddress', $default_addr);
							create_field('text', $language['database_port'], 'db_port', 'inputDBPort', $default_port);
							create_field('text', $language['database_username'], 'db_username', 'inputDBUsername', $default_user);
							create_field('password', $language['database_password'], 'db_password', 'inputDBPassword', $default_pass);
							create_field('text', $language['database_name'], 'db_name', 'inputDBName', $default_name);
							create_field('select', $language['character_set'], 'charset', 'inputCharset', $default_charset, array(
								'utf8mb4' => 'Unicode (utf8mb4)',
								'latin1' => 'Latin (latin1)',
							));
							create_field('select', $language['database_engine'], 'engine', 'inputEngine', $default_engine, array(
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
