<?php
require(__DIR__ . '/includes/functions.php');

if (!defined('DEFAULT_LANGUAGE')) {
    define('DEFAULT_LANGUAGE', 'en_UK');
}

if (isset($_GET['do'])) {
    $_SESSION['action'] = 'install';

    Redirect::to('?step=requirements_validation');
}

if (isset($_GET['step'])) {
    $step = strtolower($_GET['step']);
    if (!file_exists(__DIR__ . '/steps/' . $step . '.php')) {
        $error = 'Unknown step.';
    }
}

if (isset($step) && $step == 'ajax_initialise') {
    require(__DIR__ . '/steps/' . $step . '.php');
    die();
}

require(__DIR__ . '/includes/header.php');
?>

    <div class="main-content">
        <div class="ui container">
            <div class="ui stackable grid">
                <div class="five wide computer only column">
                    <div class="ui fluid vertical steps">
                        <?php
                        create_step($language->get('installer', 'step_home'), 'home icon', ['welcome']);
                        create_step($language->get('installer', 'step_requirements'), 'tasks icon', ['requirements_validation']);
                        create_step($language->get('installer', 'step_general_config'), 'cog icon', ['general_configuration']);
                        create_step($language->get('installer', 'step_database_config'), 'server icon', ['database_configuration', 'database_initialization', 'upgrade', 'upgrade_perform']);
                        create_step($language->get('installer', 'step_site_config'), 'globe icon', ['site_configuration', 'site_initialization']);
                        create_step($language->get('installer', 'step_admin_account'), 'user icon', ['admin_account_setup']);
                        create_step($language->get('installer', 'step_select_modules'), 'puzzle piece icon', ['select_modules']);
                        create_step($language->get('installer', 'step_conversion'), 'exchange icon', ['conversion']);
                        create_step($language->get('installer', 'step_finish'), 'check icon', ['finish']);
                        ?>
                    </div>
                </div>
                <div class="sixteen wide tablet eleven wide computer column">
                    <?php if (!isset($step)) { ?>
                        <div class="ui segments">
                            <div class="ui secondary segment">
                                <h4 class="ui header">
                                    <?php echo $language->get('installer', 'installer_welcome'); ?>
                                </h4>
                            </div>
                            <div class="ui segment">
                                <p><?php echo $language->get('installer', 'installer_information'); ?></p>
                                <p><?php echo $language->get('installer', 'terms_and_conditions'); ?></p>
                                <div class="ui message"><?php echo $nameless_terms; ?></div>
                            </div>
                            <div class="ui right aligned secondary segment">
                                <a href="?do=install" class="ui small primary button">
                                    <?php echo $language->get('installer', 'continue'); ?>
                                </a>
                            </div>
                        </div>
                    <?php } else if (isset($error)) { ?>
                        <div class="ui red message">
                            <?php echo $error; ?>
                        </div>
                    <?php } else if (!isset($_SESSION['action'])) { ?>
                        <div class="ui red message">
                            <?php echo $language->get('installer', 'session_doesnt_exist'); ?>
                        </div>
                    <?php
                    } else {
                        require(__DIR__ . '/steps/' . $step . '.php');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php

require(__DIR__ . '/includes/footer.php');
