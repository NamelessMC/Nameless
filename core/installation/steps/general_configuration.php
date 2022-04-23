<?php
if (!isset($_SESSION['requirements_validated']) || $_SESSION['requirements_validated'] != true) {
    Redirect::to('?step=requirements_validation');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (str_starts_with($_POST['hostname'], 'http://') || str_starts_with($_POST['hostname'], 'https://')) {
        $error = $language->get('installer', 'hostname_error');
    } else {
        $_SESSION['hostname'] = $_POST['hostname'] ?? $_SERVER['SERVER_NAME'];
        $_SESSION['install_path'] = $_POST['install_path'] ?? '';
        $_SESSION['friendly_urls'] = $_POST['friendly'] ?? false;

        Redirect::to('?step=database_configuration');
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
                <?php echo $language->get('installer', 'general_configuration'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <div class="ui centered grid">
                <div class="sixteen wide mobile twelve wide tablet ten wide computer column">
                    <div class="ui form">
                        <div <?php if (getenv('NAMELESS_HOSTNAME_HIDE') !== false) echo 'style="display: none"' ?>>
                            <?php create_field('text', $language->get('installer', 'host'), 'hostname', 'inputHostname', getenv('NAMELESS_HOSTNAME') ?: Output::getClean($_SERVER['SERVER_NAME'])); ?>
                            <p><?php echo $language->get('installer', 'host_help'); ?></p>
                            <div class="ui divider"></div>
                        </div>

                        <div <?php if (getenv('NAMELESS_PATH_HIDE') !== false) echo 'style="display: none"' ?>>
                            <?php create_field('text', $language->get('installer', 'nameless_path'), 'install_path', 'inputPath', getenv('NAMELESS_PATH') ?: Output::getClean($install_path)); ?>
                            <p><?php echo $language->get('installer', 'nameless_path_info'); ?></p>
                            <div class="ui divider"></div>
                        </div>

                        <div <?php if (getenv('NAMELESS_FRIENDLY_URLS_HIDE') !== false) echo 'style="display: none"' ?>>
                            <?php create_field('select', $language->get('installer', 'friendly_urls'), 'friendly', 'inputFriendly', getenv('NAMELESS_FRIENDLY_URLS') ?: 'false', [
                                'true' => $language->get('installer', 'enabled'),
                                'false' => $language->get('installer', 'disabled'),
                            ]); ?>
                            <p><?php echo $language->get('installer', 'friendly_urls_info'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui right aligned secondary segment">
            <button type="submit" class="ui small primary button">
                <?php echo $language->get('installer', 'proceed'); ?>
            </button>
        </div>
    </div>
</form>
