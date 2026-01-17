<?php

/**
 * Default Revamp template settings.
 *
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 *
 * @var Cache        $cache
 * @var Language     $language
 * @var TemplateBase $current_template
 */
if (Input::exists()) {
    if (Token::check()) {
        if (isset($_POST['darkMode'])) {
            Settings::set('dark_mode', $_POST['darkMode']);
        }

        if (isset($_POST['navbarColour'])) {
            Settings::set('default_revamp_navbar_color', $_POST['navbarColour']);
        }

        Settings::set('home_custom_content', Input::get('home_custom_content'));

        Session::flash('admin_templates', $language->get('admin', 'successfully_updated'));
    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

// Get values
$darkMode = Settings::get('dark_mode', '0');
$navbarColour = Settings::get('default_revamp_navbar_color', 'white');

$nav_colours = [
    [
        'value' => 'white',
        'name' => $language->get('general', 'default'),
        'selected' => ($navbarColour == 'white'),
    ],
    [
        'value' => 'red',
        'name' => $language->get('general', 'red'),
        'selected' => ($navbarColour == 'red'),
    ],
    [
        'value' => 'orange',
        'name' => $language->get('general', 'orange'),
        'selected' => ($navbarColour == 'orange'),
    ],
    [
        'value' => 'yellow',
        'name' => $language->get('general', 'yellow'),
        'selected' => ($navbarColour == 'yellow'),
    ],
    [
        'value' => 'olive',
        'name' => $language->get('general', 'olive'),
        'selected' => ($navbarColour == 'olive'),
    ],
    [
        'value' => 'green',
        'name' => $language->get('general', 'green'),
        'selected' => ($navbarColour == 'green'),
    ],
    [
        'value' => 'teal',
        'name' => $language->get('general', 'teal'),
        'selected' => ($navbarColour == 'teal'),
    ],
    [
        'value' => 'blue',
        'name' => $language->get('general', 'blue'),
        'selected' => ($navbarColour == 'blue'),
    ],
    [
        'value' => 'violet',
        'name' => $language->get('general', 'violet'),
        'selected' => ($navbarColour == 'violet'),
    ],
    [
        'value' => 'purple',
        'name' => $language->get('general', 'purple'),
        'selected' => ($navbarColour == 'purple'),
    ],
    [
        'value' => 'pink',
        'name' => $language->get('general', 'pink'),
        'selected' => ($navbarColour == 'pink'),
    ],
    [
        'value' => 'brown',
        'name' => $language->get('general', 'brown'),
        'selected' => ($navbarColour == 'brown'),
    ],
    [
        'value' => 'grey',
        'name' => $language->get('general', 'grey'),
        'selected' => ($navbarColour == 'grey'),
    ],
];

$current_template->assets()->include([
    AssetTree::TINYMCE,
]);

$current_template->addJSScript(Input::createTinyEditor($language, 'inputHomeCustomContent', Settings::get('home_custom_content')));

$current_template->getEngine()->addVariables([
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLED' => $language->get('admin', 'enabled'),
    'DISABLED' => $language->get('admin', 'disabled'),
    'DARK_MODE' => $language->get('admin', 'dark_mode'),
    'DARK_MODE_VALUE' => $darkMode,
    'NAVBAR_COLOUR' => $language->get('admin', 'navbar_colour'),
    'NAVBAR_COLOURS' => $nav_colours,
    'HOME_CUSTOM_CONTENT' => $language->get('admin', 'home_custom_content'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/templates/DefaultRevamp/template_settings/settings.tpl',
]);
