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
    'VALUE' => $navbarColour,
    'DEFAULT' => $language->get('general', 'default'),
    'RED' => $language->get('general', 'red'),
    'ORANGE' => $language->get('general', 'orange'),
    'YELLOW' => $language->get('general', 'yellow'),
    'OLIVE' => $language->get('general', 'olive'),
    'GREEN' => $language->get('general', 'green'),
    'TEAL' => $language->get('general', 'teal'),
    'BLUE' => $language->get('general', 'blue'),
    'VIOLET' => $language->get('general', 'violet'),
    'PURPLE' => $language->get('general', 'purple'),
    'PINK' => $language->get('general', 'pink'),
    'BROWN' => $language->get('general', 'brown'),
    'GREY' => $language->get('general', 'grey'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/templates/DefaultRevamp/template_settings/settings.tpl'
]);
