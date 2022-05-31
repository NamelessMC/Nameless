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
$cache->setCache('social_media');

if (Input::exists()) {
    if (Token::check()) {
        if (isset($_POST['theme'])) {
            $cache->store('discord_widget_theme', $_POST['theme']);
        }

        $cache->store('discord', $guild_id);

        $success = $language->get('admin', 'widget_updated');

    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

if ($cache->isCached('discord_widget_theme')) {
    $discord_theme = $cache->retrieve('discord_widget_theme');
} else {
    $discord_theme = 'dark';
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
    ]);
}

$smarty->assign([
    'DISCORD_THEME' => Discord::getLanguageTerm('discord_widget_theme'),
    'DISCORD_THEME_VALUE' => $discord_theme,
    'SETTINGS_TEMPLATE' => 'discord_integration/widgets/discord.tpl',
    'DARK' => $language->get('admin', 'dark'),
    'LIGHT' => $language->get('admin', 'light')
]);
