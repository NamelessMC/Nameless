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
            $converters[] = $path[count($path) - 1];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($converters)) {

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
            if (!isset($_POST['converter']) || !in_array($_POST['converter'], $converters)) {
                $error = $language->get('installer', 'unable_to_load_converter');
            } else {
                try {
                    $conn = DB::getCustomInstance(
                        Input::get('db_address'),
                        Input::get('db_name'),
                        Input::get('db_username'),
                        Input::get('db_password'),
                        Input::get('db_port')
                    );

                    $converter_dir = ROOT_PATH . '/custom/converters/' . $_POST['converter'];

                    $converter_dirs = glob(ROOT_PATH . '/custom/converters/*', GLOB_ONLYDIR);

                    if (!in_array($converter_dir, $converter_dirs)) {
                        throw new InvalidArgumentException("Invalid converter");
                    }

                    require_once($converter_dir . '/converter.php');

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

<form action="" method="post">
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
                            foreach ($converters as $converter) {
                                $converter_options[Output::getClean($converter)] = str_replace('_', ' ', Output::getClean($converter));
                            }
                            create_field('select', $language->get('installer', 'converter'), 'converter', 'inputConverter', '', $converter_options);
                            create_field('text', $language->get('installer', 'database_address'), 'db_address', 'inputDBAddress', '127.0.0.1');
                            create_field('text', $language->get('installer', 'database_port'), 'db_port', 'inputDBPort', '3306');
                            create_field('text', $language->get('installer', 'database_username'), 'db_username', 'inputDBUsername');
                            create_field('text', $language->get('installer', 'database_password'), 'db_password', 'inputDBPassword');
                            create_field('text', $language->get('installer', 'database_name'), 'db_name', 'inputDBName');
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
