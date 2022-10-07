<?php
$readme = file(ROOT_PATH . '/README.md');
$subheader = str_replace('#', '', $readme[0]);

if (isset($_SESSION['installer_language']) && is_file('custom/languages/' . $_SESSION['installer_language'] . '.json')) {
    $installer_language = $_SESSION['installer_language'];
} else {
    $installer_language = 'en_UK';
}

$languages_folders = glob('custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
$languages = [];

foreach (Language::LANGUAGES as $short_code => $meta) {
    $languages[$short_code] = $meta['name'];
}

?>

<!DOCTYPE html>
<html lang="<?php echo $language_html; ?>">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $language->get('installer', 'install'); ?> &bull; NamelessMC</title>
    <link rel="stylesheet" href="core/assets/vendor/fomantic-ui/dist/semantic.min.css">

    <style>

        body {
            background: #f3f6fa;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex-grow: 1;
        }

        .ui.masthead.segment {
            background: #005C97;
            background: -webkit-linear-gradient(to right, #363795, #005C97);
            background: linear-gradient(to right, #363795, #005C97);
            margin-bottom: 1.5rem;
        }

        .ui.masthead.segment .ui.header .ui.image {
            width: 100px;
            margin-bottom: 1rem;
        }

        .ui.masthead.segment .ui.header .sub.header {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .ui.grid {
            margin-top: -0.5rem;
            margin-bottom: -0.5rem;
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .ui.grid > .column:not(.row) {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .ui.grid > .column:not(.row), .ui.grid > .row > .column {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .ui.vertical.steps .active.step:after {
            display: none !important;
        }

        .ui.footer.segment {
            margin-top: 1.5rem;
        }

    </style>

</head>

<body>

<div class="wrapper">

    <div class="ui inverted vertical masthead very padded segment">
        <div class="ui center aligned text container">
            <h2 class="ui inverted icon header">
                <img class="ui image" src="core/assets/img/namelessmc_logo.png">
                <div class="content">
                    NamelessMC Installer
                    <div class="sub header">
                        <?php echo $subheader; ?>
                    </div>
                </div>
            </h2>
        </div>
    </div>
