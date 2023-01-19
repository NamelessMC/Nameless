<?php

if (!isset($_SESSION['admin_setup']) || $_SESSION['admin_setup'] != true) {
    Redirect::to('?step=admin_account_setup');
}

$available_converters = array_filter(glob(ROOT_PATH . '/custom/converters/*'), 'is_dir');
$converters = [];

if (!empty($available_converters)) {
    foreach ($available_converters as $converter) {
        if (file_exists($converter . '/converter.php')) {
            $path = explode(DIRECTORY_SEPARATOR, $converter);
            $converters[$path[count($path) - 1]] = require $converter . '/converter_config.php';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($converters)) {

        if (!isset($_POST['converter']) || !array_key_exists($_POST['converter'], $converters)) {
            $error = $language->get('installer', 'unable_to_load_converter');
        } else {
            $converter = $_POST['converter'];
            $converter_config = $converters[$converter];

            if ($converter_config['input'] === 'sqlite') {
                $to_validate = [
                    'db_file' => [
                        Validate::FILE => [
                            'mime' => 'application/x-sqlite3',
                            'extension' => 'db',
                        ],
                    ],
                ];
            } else {
                $to_validate = [
                    'db_host' => [
                        Validate::REQUIRED => true,
                    ],
                    'db_port' => [
                        Validate::REQUIRED => true,
                    ],
                    'db_name' => [
                        Validate::REQUIRED => true,
                    ],
                    'db_user' => [
                        Validate::REQUIRED => true,
                    ],
                    'db_pass' => [
                        Validate::REQUIRED => true,
                    ],
                ];
            }

            $validation = Validate::check($_POST, $to_validate + [
                    'converter' => [
                        Validate::REQUIRED => true,
                        Validate::IN => array_keys($converters),
                    ],
                ]);

            if (!$validation->passed()) {
                $error = $language->get('installer', 'database_error');
            } else {
                try {
                    switch ($converter_config['input']) {
                        case 'sqlite':
                            $conn = new PDO('sqlite:' . $_FILES['db_file']['tmp_name']);
                            break;
                        case 'mysql':
                            $conn = DB::getCustomInstance(
                                Input::get('db_address'),
                                Input::get('db_name'),
                                Input::get('db_username'),
                                Input::get('db_password'),
                                Input::get('db_port')
                            );
                            break;
                        default:
                            throw new InvalidArgumentException("Invalid input type {$converter_config['input']}");
                    }

                    $nameless_conn = DB::getInstance();

                    require_once(ROOT_PATH . '/custom/converters/' . $converter . '/converter.php');

                    if (!isset($error)) {
                        Redirect::to('?step=finish');
                    }
                } catch (PDOException $e) {
                    $error = $language->get('installer', 'database_connection_failed', ['message' => $e->getMessage()]);
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

<form action="" method="post" enctype="multipart/form-data">
    <div class="ui segments">
        <div class="ui secondary segment">
            <h4 class="ui header">
                <?php echo $language->get('installer', 'convert'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <?php if (!empty($converters)) { ?>
                <div class="ui centered grid">
                    <div class="sixteen wide mobile twelve wide tablet ten wide computer column">
                        <div class="ui form">
                            <?php
                            $converter_options = [];
                            foreach (array_keys($converters) as $converter) {
                                $converter_options[Output::getClean($converter)] = ucfirst(str_replace('_', ' ', Output::getClean($converter)));
                            }
                            create_field('select', $language->get('installer', 'converter'), 'converter', 'inputConverter', '', $converter_options);
                            create_field('text', $language->get('installer', 'database_address'), 'db_address', 'inputDBAddress', '127.0.0.1');
                            create_field('text', $language->get('installer', 'database_port'), 'db_port', 'inputDBPort', '3306');
                            create_field('text', $language->get('installer', 'database_username'), 'db_username', 'inputDBUsername');
                            create_field('text', $language->get('installer', 'database_password'), 'db_password', 'inputDBPassword');
                            create_field('text', $language->get('installer', 'database_name'), 'db_name', 'inputDBName');
                            create_field('file', $language->get('installer', 'database_file'), 'db_file', 'inputDBFile');
                            ?>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <p><?php echo $language->get('installer', 'no_converters_available'); ?></p>
            <?php } ?>
        </div>
        <div class="ui secondary right aligned segment">
            <a href="?step=conversion" class="ui small button">
                <?php echo $language->get('installer', 'back'); ?>
            </a>
            <?php if (!empty($converters)) { ?>
                <button type="submit" class="ui small primary button">
                    <?php echo $language->get('installer', 'proceed'); ?>
                </button>
            <?php } else { ?>
                <a href="?step=finish" class="ui small primary button">
                    <?php echo $language->get('installer', 'proceed'); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</form>
<script>
    const converters = <?php echo json_encode($converters); ?>;
    const mysqlInputs = ['inputDBAddress', 'inputDBPort', 'inputDBUsername', 'inputDBPassword', 'inputDBName'];
    const sqliteInputs = ['inputDBFile'];
    document.getElementById('inputConverter').addEventListener('change', (e) => {
        const config = converters[e.target.value];

        if (config.input === 'sqlite') {
            console.log('sqlite');
            mysqlInputs.forEach(input => {
                document.getElementById(input).parentElement.style.display = 'none';
            });
            sqliteInputs.forEach(input => {
                document.getElementById(input).parentElement.style.display = 'block';
            });
        } else if (config.input === 'mysql') {
            mysqlInputs.forEach(input => {
                document.getElementById(input).parentElement.style.display = 'block';
            });
            sqliteInputs.forEach(input => {
                document.getElementById(input).parentElement.style.display = 'none';
            });
        }
    });

    document.getElementById('inputConverter').dispatchEvent(new Event('change'));
</script>
