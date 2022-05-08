<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  DefaultRevamp template settings
 */

if (Input::exists()) {
    if (Token::check()) {
        $cache->setCache('template_settings');

        if (isset($_POST['darkMode'])) {
            $cache->store('darkMode', $_POST['darkMode']);
        }

        if (isset($_POST['navbarColour'])) {
            $cache->store('navbarColour', $_POST['navbarColour']);
        }

        Session::flash('admin_templates', $language->get('admin', 'successfully_updated'));

    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

// Get values
$cache->setCache('template_settings');
if ($cache->isCached('darkMode')) {
    $darkMode = $cache->retrieve('darkMode');
} else {
    $darkMode = '0';
    $cache->store('darkMode', $darkMode);
}

if ($cache->isCached('navbarColour')) {
    $navbarColour = $cache->retrieve('navbarColour');
} else {
    $navbarColour = 'white';
    $cache->store('navbarColour', $navbarColour);
}

$smarty->assign([
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLED' => $language->get('admin', 'enabled'),
    'DISABLED' => $language->get('admin', 'disabled'),
    'DARK_MODE' => $language->get('admin', 'dark_mode'),
    'DARK_MODE_VALUE' => $darkMode,
    'NAVBAR_COLOUR' => $language->get('admin', 'navbar_colour'),
    'NAVBAR_COLOUR_VALUE' => $navbarColour,
    'NAVBAR_COLOUR_DEFAULT' => $language->get('admin', 'navbar_colour_default'),
    'NAVBAR_COLOUR_RED' => $language->get('admin', 'navbar_colour_red'),
    'NAVBAR_COLOUR_ORANGE' => $language->get('admin', 'navbar_colour_orange'),
    'NAVBAR_COLOUR_YELLOW' => $language->get('admin', 'navbar_colour_yellow'),
    'NAVBAR_COLOUR_OLIVE' => $language->get('admin', 'navbar_colour_olive'),
    'NAVBAR_COLOUR_GREEN' => $language->get('admin', 'navbar_colour_green'),
    'NAVBAR_COLOUR_TEAL' => $language->get('admin', 'navbar_colour_teal'),
    'NAVBAR_COLOUR_BLUE' => $language->get('admin', 'navbar_colour_blue'),
    'NAVBAR_COLOUR_VIOLET' => $language->get('admin', 'navbar_colour_violet'),
    'NAVBAR_COLOUR_PURPLE' => $language->get('admin', 'navbar_colour_purple'),
    'NAVBAR_COLOUR_PINK' => $language->get('admin', 'navbar_colour_pink'),
    'NAVBAR_COLOUR_BROWN' => $language->get('admin', 'navbar_colour_brown'),
    'NAVBAR_COLOUR_GREY' => $language->get('admin', 'navbar_colour_grey'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/templates/DefaultRevamp/template_settings/settings.tpl'
]);
