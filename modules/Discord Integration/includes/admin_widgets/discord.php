<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Discord widget settings
 *
 * @var Cache $cache
 * @var Language $language
 * @var Smarty $smarty
 * @var string $guild_id
 */

// Check input
$cache->setCacheName('social_media');

if (Input::exists()) {
    try {
        if (Token::check()) {
            if (isset($_POST['theme'])) {
                $cache->store('discord_widget_theme', $_POST['theme']);
            }

            $cache->store('discord', $guild_id);

            $success = $language->get('admin', 'widget_updated');

        } else {
            $errors = [$language->get('general', 'invalid_token')];
        }
    } catch (Exception $ignored) {
    }
}

if ($cache->hasCashedData('discord_widget_theme')) {
    $discord_theme = $cache->retrieve('discord_widget_theme');
} else {
    $discord_theme = 'dark';
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
    ]);
}

try {
    $smarty->assign([
        'DISCORD_THEME' => Discord::getLanguageTerm('discord_widget_theme'),
        'DISCORD_THEME_VALUE' => $discord_theme,
        'SETTINGS_TEMPLATE' => 'discord_integration/widgets/discord.tpl',
        'DARK' => $language->get('admin', 'dark'),
        'LIGHT' => $language->get('admin', 'light')
    ]);
} catch (Exception $ignored) {
}
