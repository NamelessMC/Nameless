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
            display: none;
        }

        body.dark {
            background: #222;
        }

        .visible {
            display: block;
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

        body.dark .ui.card,
        body.dark .ui.segment:not(.inverted),
        body.dark .step {
            background-color: #303030 !important;
            border: 1px solid rgba(0, 0, 0, 0.125);
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        body.dark .ui.card>.content>.header a,
        body.dark .ui.segment a {
            color: #fff;
        }
        
        body.dark .ui.card .meta,
        body.dark .ui.header,
        body.dark .ui.header .sub.header,
        body.dark .ui.card>.content>.header,
        body.dark .ui.cards>.card>.content>.header,
        body.dark .ui.card>.content>.description .list .item .text,
        body.dark .ui.dropdown .menu>.item,
        body.dark .ui.segment:not(.inverted),
        body.dark .ui.form .field>label,
        body.dark .ui.list .list>.item .header,
        body.dark .ui.list>.item .header,
        body.dark .ui.dropdown .menu>.header:not(.ui),
        body.dark .ui.segment .ui.message:not(.small),
        body.dark .step,
        body.dark .ui.steps .step.active .icon,
        body.dark .ui.steps .step.active .content .title {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        body.dark .ui.segment .ui.message:not(.small) {
            background-color: #282828;
            box-shadow: 0 0 0 1px #282828 inset, 0 0 0 0 transparent;
            -webkit-box-shadow: 0 0 0 1px #282828 inset, 0 0 0 0 transparent;
        }

        body.dark .ui.form input:not([type]),
        body.dark .ui.form input[type=datetime-local],
        body.dark .ui.form input[type=email],
        body.dark .ui.form input[type=password],
        body.dark .ui.form input[type=text] {
            color: #fff;
        }

        body.dark .ui.image.label,
        body.dark .ui.selection.dropdown,
        body.dark .ui.selection.visible.dropdown>.text:not(.default) {
            background-color: #282828;
            color: #fff;
        }

        body.dark .ui.form .field .ui.selection.active.dropdown .menu {
            border: 1px solid #282828;
        }

        body.dark .ui.form input:not(.button) {
            background-color: #303030 !important;
        }

        body.dark .ui.menu .dropdown.item .menu,
        body.dark .ui.dropdown .menu {
            background-color: #222;
            border: 1px solid #444;
            color: rgba(255, 255, 255, 0.6);
        }

        body.dark .ui.menu .ui.dropdown .menu>.item:hover,
        body.dark .ui.dropdown .menu>.item:hover,
        body.dark .ui.menu .ui.dropdown .menu>.item.active,
        body.dark .ui.list>.item a.header {
            color: #fff !important;
        }

        body.dark .ui.form .field .ui.dropdown .menu .item {
            background-color: #282828;
            border-top: 1px solid #303030;
            color: #fff;
        }

        body.dark .ui.menu .ui.dropdown .menu>.item:hover {
            background-color: rgba(0, 0, 0, .05) !important;
        }

        body.dark .ui.button {
            background-color: #282828;
            color: rgba(255, 255, 255, 0.6);
        }

        #darkmode {
            display: inline-block;
        }

        .darkmode-toggle {
            opacity: 0;
            position: absolute;
        }

        .darkmode-toggle:checked + .darkmode-toggle-label .darkmode-ball {
            transform: translateX(24px);
        }

        .darkmode-toggle-label {
            background-color: #111;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 50px;
            position: relative;
            padding: 5px;
            height: 26px;
            width: 50px;
        }

        .darkmode-ball {
            background-color: #fff;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            height: 22px;
            width: 22px;
            transition: transform 0.2s linear;
        }

        .moon.icon {
            color: #f1c400;
        }

        .sun.icon {
            color: #f39c00;
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
