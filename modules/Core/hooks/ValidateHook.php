<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  Validate user hook handler class
 */

class ValidateHook {

    public static function validatePromote($params = array()) {
        if (!defined('VALIDATED_DEFAULT'))
            define('VALIDATED_DEFAULT', 1);

        $validate_user = new User($params['user_id']);
        if (!count($validate_user->data())) {
            return false;
        }

        $validate_user->setGroup(VALIDATED_DEFAULT);
        $queries = new Queries;

        // Discord integration is enabled
        $setting = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
        if ($setting[0]->value == '1') {
            // They have a valid discord Id
            if ($validate_user->data()->discord_id != null && $validate_user->data()->discord_id != 010) {
                $group_discord_id = $queries->getWhere('groups', array('id', '=', VALIDATED_DEFAULT));
                $group_discord_id = $group_discord_id[0]->discord_role_id;

                if ($group_discord_id != null) {
                    $api_key = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
                    $api_key = $api_key[0]->value;
                    $api_url = rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($api_key), '', 'non-friendly'), '/');
                    $guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
                    $url = '/roleChange?id=' . $validate_user->data()->discord_id . '&guild_id=' . $guild_id[0]->value . '&role=' . $group_discord_id . '&api_url=' . $api_url;
                    $result = Discord::discordBotRequest($url);
                    // Purposely ignored checking for errors, but rather add a log instead
                    if ($result != 'success') {
                        Log::getInstance()->log(Log::action('discord/upon_validation_error'), 'Request error: ' . $result, $params['user_id']);
                    }
                }
            }
        }
    }
}
