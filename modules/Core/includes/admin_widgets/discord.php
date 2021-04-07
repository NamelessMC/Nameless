<?php
/*
 *	Made by Samerton
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
        if (isset($_POST['theme']))
            $cache->store('discord_widget_theme', $_POST['theme']);

        $discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
        $discord_id = $discord_id[0]->id;

        if (isset($_POST['discord_guild_id'])) {

            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'discord_guild_id' => [
                    Validate::MIN => 18,
                    Validate::MAX => 18,
                    Validate::NUMERIC => true
                ]
            ])->messages([
                'discord_guild_id' => [
                    Validate::MIN => $language->get('admin', 'discord_id_length'),
                    Validate::MAX => $language->get('admin', 'discord_id_length'),
                    Validate::NUMERIC => $language->get('admin', 'discord_id_numeric')
                ]
            ]);

            if ($validation->passed()) {

                $guild_id = $_POST['discord_guild_id'];
                
            } else {

                $errors = $validation->errors();

            }
        } else {
            $guild_id = '';
        }
        if (count($errors))
            $smarty->assign('ERRORS', $errors);
        else {
            $queries->update('settings', $discord_id, array(
                'value' => $guild_id
            ));

            $cache->store('discord', $guild_id);

            $success = $language->get('admin', 'widget_updated');
        }
    } else {
        $errors = array($language->get('general', 'invalid_token'));
    }
}

$guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
$guild_id = $guild_id[0]->value;

if ($cache->isCached('discord_widget_theme'))
    $discord_theme = $cache->retrieve('discord_widget_theme');
else
    $discord_theme = 'dark';

$smarty->assign(array(
    'DISCORD_ID' => $language->get('admin', 'discord_id'),
    'DISCORD_ID_VALUE' => $guild_id,
    'INFO' => $language->get('general', 'info'),
    'ID_INFO' => $language->get('user', 'discord_id_help'),
    'DISCORD_THEME' => $language->get('admin', 'discord_widget_theme'),
    'DISCORD_THEME_VALUE' => $discord_theme,
    'SETTINGS_TEMPLATE' => 'core/widgets/discord.tpl',
    'DARK' => $language->get('admin', 'dark'),
    'LIGHT' => $language->get('admin', 'light')
));
