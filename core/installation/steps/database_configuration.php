<?php

if (isset($_SESSION['database_initialized']) && $_SESSION['database_initialized'] == true) {
    Redirect::to('?step=site_configuration');
}

if (!isset($_SESSION['hostname'], $_SESSION['install_path']) || !isset($_SESSION['friendly_urls'])) {
    Redirect::to('?step=general_configuration');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validation = Validate::check($_POST, [
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

        $error = $language->get('installer', 'database_error');

    } else {
        $db_address = $_POST['db_address'];
        $db_port = $_POST['db_port'];
        $db_username = $_POST['db_username'];
        $db_password = ((isset($_POST['db_password']) && !empty($_POST['db_password'])) ? str_replace('\'', '\\\'', $_POST['db_password']) : '');
        $db_name = $_POST['db_name'];

        try {
            // This throws a PDOException if the connection fails
            $db = DB::getCustomInstance($db_address, $db_name, $db_username, $db_password, $db_port, $force_charset=$db_charset);

            // Throw an exception if they attempt to reinstall with a database that contains some NamelessMC data already
            if ($db->showTables('modules') > 0) {
                throw new RuntimeException('Database already contains tables');
            }

            $conf = [
                'mysql' => [
                    'host' => $db_address,
                    'port' => $db_port,
                    'username' => $db_username,
                    'password' => $db_password,
                    'db' => $db_name,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'initialise_charset' => true,
                    'initialise_collation' => true,
                ],
                'remember' => [
                    'cookie_name' => 'nl2',
                    'cookie_expiry' => 604800,
                ],
                'session' => [
                    'session_name' => '2user',
                    'admin_name' => '2admin',
                    'token_name' => '2token',
                ],
                'core' => [
                    'hostname' => $_SESSION['hostname'],
                    'path' => $_SESSION['install_path'],
                    'friendly' => $_SESSION['friendly_urls'] === 'true',
                    'force_https' => false,
                    'force_www' => false,
                    'captcha' => false,
                    'date_format' => 'd M Y, H:i',
                    'trustedProxies' => null,
                ],
            ];

            try {
                Config::write($conf);
                $_SESSION['database_configured'] = true;
                Redirect::to('?step=database_initialization');
            } catch (RuntimeException $e) {
                $error = $language->get('installer', 'config_write_failed', ['message' => $e->getMessage()]);
            }
        } catch (PDOException $e) {
            $error = $language->get('installer', 'database_connection_failed', ['message' => $e->getMessage()]);
        } catch (Exception $e) {
            $error = $e->getMessage();
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
                <?php echo $language->get('installer', 'database_configuration'); ?>
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
                        create_field('text', $language->get('installer', 'database_address'), 'db_address', 'inputDBAddress', $default_addr);
                        create_field('text', $language->get('installer', 'database_port'), 'db_port', 'inputDBPort', $default_port);
                        create_field('text', $language->get('installer', 'database_username'), 'db_username', 'inputDBUsername', $default_user);
                        create_field('password', $language->get('installer', 'database_password'), 'db_password', 'inputDBPassword', $default_pass);
                        create_field('text', $language->get('installer', 'database_name'), 'db_name', 'inputDBName', $default_name);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui secondary right aligned segment">
            <button type="submit" class="ui small primary button">
                <?php echo $language->get('installer', 'proceed'); ?>
            </button>
        </div>
    </div>
</form>
