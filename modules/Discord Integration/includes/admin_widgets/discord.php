<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Discord widget settings
 */

// Check input
if (Input::exists()) {
    if (Token::check()) {
        if (isset($_POST['theme'])) {
            Settings::set('discord_widget_theme', $_POST['theme'], 'Discord Integration');
        }

        $success = $language->get('admin', 'widget_updated');

    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$discord_theme = Settings::get('discord_widget_theme', 'dark', 'Discord Integration');

if (isset($errors) && count($errors)) {
    $template->getEngine()->addVariable('ERRORS', $errors);
}

$template->getEngine()->addVariables([
    'DISCORD_THEME' => Discord::getLanguageTerm('discord_widget_theme'),
    'DISCORD_THEME_VALUE' => $discord_theme,
    'SETTINGS_TEMPLATE' => 'discord_integration/widgets/discord.tpl',
    'DARK' => $language->get('admin', 'dark'),
    'LIGHT' => $language->get('admin', 'light')
]);
