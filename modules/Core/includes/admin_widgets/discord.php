<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Discord widget settings
 */

// Check input
$cache->setCache('social_media');

if(Input::exists()){
    if(Token::check(Input::get('token'))){
        if(isset($_POST['theme']))
            $cache->store('discord_widget_theme', $_POST['theme']);

        $discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
        $discord_id = $discord_id[0]->id;

        if(isset($_POST['discord_api_key'])){

            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'discord_api_key' => array(
                    'min' => 18,
                    'max' => 18,
                    'numeric' => true
                )
            ));
            
            if ($validation->passed()) {
                $discord_api_key = $_POST['discord_api_key'];
            } else {
                // Validation errors
                foreach ($validation->errors() as $validation_error) {
                    if (strpos($validation_error, 'minimum') !== false) {
                        // x must be a minimum of y characters long
                        switch ($validation_error) {
                            case (strpos($validation_error, 'discord_api_key') !== false):
                                $errors[] = str_replace('discord_api_key', 'Discord Server ID', $validation_error);
                                break;
                        }
                    } else if (strpos($validation_error, 'maximum') !== false) {
                        // x must be a maximum of y characters long
                        switch ($validation_error) {
                            case (strpos($validation_error, 'discord_api_key') !== false):
                                $errors[] = str_replace('discord_api_key', 'Discord Server ID', $validation_error);
                                break;
                        }
                    }
                    else if (strpos($validation_error, 'numeric') !== false) {
                        // x must be a maximum of y characters long
                        switch ($validation_error) {
                            case (strpos($validation_error, 'discord_api_key') !== false):
                                $errors[] = str_replace('discord_api_key', 'Discord Server ID', $validation_error);
                                break;
                        }
                    }
                }
            }
        } else {
            $discord_api_key = '';
        }
        if (count($errors))
            $smarty->assign('ERRORS', $errors);
        else {
            $queries->update('settings', $discord_id, array(
                'value' => Output::getClean($discord_api_key)
            ));

            $cache->store('discord', Output::getClean($discord_api_key));

            $success = $language->get('admin', 'widget_updated');
        }
    } else {
        $errors = array($language->get('general', 'invalid_token'));
    }
}

if($cache->isCached('discord'))
    $discord_api = $cache->retrieve('discord');
else
    $discord_api = '';

if($cache->isCached('discord_widget_theme'))
    $discord_theme = $cache->retrieve('discord_widget_theme');
else
    $discord_theme = 'dark';

$smarty->assign(array(
	'DISCORD_ID' => $language->get('admin', 'discord_id'),
	'DISCORD_ID_VALUE' => Output::getClean($discord_api),
	'DISCORD_THEME' => $language->get('admin', 'discord_widget_theme'),
	'DISCORD_THEME_VALUE' => $discord_theme,
	'SETTINGS_TEMPLATE' => 'core/widgets/discord.tpl',
	'DARK' => $language->get('admin', 'dark'),
	'LIGHT' => $language->get('admin', 'light')
));