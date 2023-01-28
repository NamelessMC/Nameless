<?php

if (isset($_SESSION['site_initialized']) && $_SESSION['site_initialized'] == true) {
    Redirect::to('?step=admin_account_setup');
}

if (!isset($_SESSION['database_initialized']) || $_SESSION['database_initialized'] != true) {
    Redirect::to('?step=database_configuration');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $validation = Validate::check($_POST, [
        'sitename' => [
            Validate::REQUIRED => true,
            Validate::MIN => 1,
            Validate::MAX => 32,
        ],
        'incoming' => [
            Validate::REQUIRED => true,
            Validate::MIN => 4,
            Validate::MAX => 64,
        ],
        'outgoing' => [
            Validate::REQUIRED => true,
            Validate::MIN => 4,
            Validate::MAX => 64,
        ],
        'language' => [
            Validate::REQUIRED => true,
        ],
        'timezone' => [
            Validate::REQUIRED => true,
        ],
    ]);

    if (!$validation->passed()) {

        $error = $language->get('installer', 'configuration_error');

    } else {

        try {
            Util::setSetting('sitename', Input::get('sitename'));
            Util::setSetting('incoming_email', Input::get('incoming'));
            Util::setSetting('outgoing_email', Input::get('outgoing'));

            $_SESSION['default_language'] = Input::get('language');

            $_SESSION['install_timezone'] = in_array($timezone = Input::get('timezone'), DateTimeZone::listIdentifiers())
                ? $timezone
                : 'Europe/London';

            Redirect::to('?step=site_initialization');
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
                <?php echo $language->get('installer', 'configuration'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <p><?php echo $language->get('installer', 'configuration_info'); ?></p>
            <div class="ui centered grid">
                <div class="sixteen wide mobile twelve wide tablet ten wide computer column">
                    <div class="ui form">
                        <?php create_field('text', $language->get('installer', 'site_name'), 'sitename', 'inputSitename', getenv('NAMELESS_SITE_NAME') ?: ''); ?>
                        <?php create_field('email', $language->get('installer', 'contact_email'), 'incoming', 'contact_email', getenv('NAMELESS_SITE_CONTACT_EMAIL') ?: ''); ?>
                        <?php create_field('email', $language->get('installer', 'outgoing_email'), 'outgoing', 'outgoing_email', getenv('NAMELESS_SITE_OUTGOING_EMAIL') ?: ''); ?>
                        <?php create_field('select', $language->get('installer', 'language'), 'language', 'inputLanguage', $installer_language, $languages) ?>
                        <?php create_field('select', $language->get('installer', 'timezone'), 'timezone', 'inputTimezone', '', array_map(static fn ($timezone) => "({$timezone['offset']}) - {$timezone['name']} ({$timezone['time']})", Util::listTimezones())) ?>
                    </div>
                </div>
            </div>
</form>
</div>
<div class="ui right aligned secondary segment">
    <button type="submit" class="ui small primary button">
        <?php echo $language->get('installer', 'proceed'); ?>
    </button>
</div>
</div>
</form>
<script>
    const timezone = document.getElementById('inputTimezone');
    if (timezone) {
        const dateFormatter = Intl.DateTimeFormat();
        const timezoneValue = dateFormatter.resolvedOptions().timeZone;
        if (timezoneValue) {
            timezone.value = timezoneValue;
        }
    }
</script>
