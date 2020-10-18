<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  Validate user hook handler class
 */

class ValidateHook {
    public static function validatePromote($params = array()){
        $db = DB::getInstance();

        if(!defined('VALIDATED_DEFAULT'))
            define('VALIDATED_DEFAULT', 1);

        $db->createQuery("UPDATE nl2_users SET group_id = ? WHERE id = ?", array(VALIDATED_DEFAULT, $params['user_id']));

        $queries = new Queries;
        $user_query = $queries->getWhere('users', array('id', '=', $params['user_id']));

        // Discord integration is enabled
        $setting = $queries->getWhere('settings', array('name', '=', 'discord_integration'));
        if ($setting[0]->value == '1') {
            // They have a valid discord Id
            if ($user_query->discord_id != null && $user_query->discord_id != 010) {
                $group_discord_id = $queries->getWhere('groups', array('id', '=', VALIDATED_DEFAULT));
                $group_discord_id = $group_discord_id[0]->discord_role_id;

                if ($group_discord_id != null) {
                    $api_key = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
                    $api_key = $api_key[0]->value;
                    $api_url = rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean($api_key), '', 'non-friendly'), '/');
                    $guild_id = $queries->getWhere('settings', array('name', '=', 'discord'));
                    $url = '/roleChange?id=' . $user_query->discord_id . '&guild_id=' . $guild_id[0]->value . '&role=' . $group_discord_id . '&api_url=' . $api_url;
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