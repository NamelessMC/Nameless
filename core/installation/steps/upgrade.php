<?php

if (!isset($_SESSION['database_initialized']) || $_SESSION['database_initialized'] != true) {
    Redirect::to('?step=database_configuration');
}

if ($_SESSION['action'] !== 'upgrade') {
    Redirect::to('?step=site_configuration');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['not_upgrading'])) {
        $_SESSION['action'] = 'install';
        Redirect::to('?step=site_configuration');
    }

    $validation = Validate::check($_POST, [
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

        $error = $language->get('installer', 'database_error');

    } else {

        $db_address = $_POST['db_address'];
        $db_port = $_POST['db_port'];
        $db_username = $_POST['db_username'];
        $db_password = ((isset($_POST['db_password']) && !empty($_POST['db_password'])) ? $_POST['db_password'] : '');
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
        }

    }

}

?>

<?php if (isset($error)) { ?>
    <div class="ui error message">
        <?php echo $error; ?>
    </div>
<?php } ?>

<div class="ui segments">
    <div class="ui secondary segment">
        <h4 class="ui header">
            <?php echo $language->get('installer', 'upgrade'); ?>
        </h4>
    </div>
    <div class="ui segment">
        <p><?php echo $language->get('installer', 'input_v1_details'); ?></p>
        <form action="" method="post" id="upgrade_db">
            <div class="ui centered grid">
                <div class="sixteen wide mobile twelve wide tablet ten wide computer column">
                    <div class="ui form">
                        <?php
                        create_field('text', $language->get('installer', 'database_address'), 'db_address', 'inputDBAddress', '127.0.0.1');
                        create_field('text', $language->get('installer', 'database_port'), 'db_port', 'inputDBPort', '3306');
                        create_field('text', $language->get('installer', 'database_username'), 'db_username', 'inputDBUsername', 'root');
                        create_field('password', $language->get('installer', 'database_password'), 'db_password', 'inputDBPassword');
                        create_field('text', $language->get('installer', 'database_name'), 'db_name', 'inputDBName');
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ui secondary right aligned segment">
        <form action="" method="post" id="not_upgrading">
            <input type="hidden" name="not_upgrading" value="not_upgrading">
        </form>
        <button type="submit" form="not_upgrading" class="ui small info button">
            <?php echo $language->get('installer', 'not_upgrading'); ?>
        </button>
        <button type="submit" form="upgrade_db" class="ui small primary button">
            <?php echo $language->get('installer', 'proceed'); ?>
        </button>
    </div>
</div>
