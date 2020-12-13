<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Discord class
 */
class Discord {

    public static function discordBotRequest($url = '/', $body = null) {
        $bot_url_attempt = Util::curlGetContents(BOT_URL . $url, $body);
        $valid_responses = array('success', 'failure-cannot-interact', 'failure-invalid-api-url');
        if (in_array($bot_url_attempt, $valid_responses)) return $bot_url_attempt;
        else return false;
    }

    public static function getDiscordRoleId(DB $db, $group_id) {
        $discord_role_id = $db->get('group_sync', array('website_group_id', '=', $group_id));
        if ($discord_role_id->count()) return $discord_role_id->first()->discord_role_id;
        else return null;
    }

    public static function getWebsiteGroup(DB $db, $discord_role_id) {
        $website_group_id = $db->get('group_sync', array('discord_role_id', '=', $discord_role_id));
        if ($website_group_id->count()) {
            $group = $db->get('groups', array('id', '=', $website_group_id->first()->website_group_id));
            if ($group->count()) return $group->first();
        }
        return null;
    }

    public static function removeDiscordRole($user_query, $group, Language $language) {
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            if ($user_query->data()->discord_id != null && $user_query->data()->discord_id != 010) {

                $json = self::assembleJson($user_query->data()->discord_id, 'remove_role_id', self::getDiscordRoleId(DB::getInstance(), $group));

                $result = self::discordBotRequest('/roleChange', $json);
                if ($result != 'success') {
                    
                    $errors = self::parseErrors($result, $language);

                    Session::flash('edit_user_errors', $errors);
                    Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->data()->id)));
                    die();
                }
            }
        }
    }

    public static function addDiscordRole($user_query, $group, Language $language, $redirect = true) {
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            if ($user_query->data()->discord_id != null && $user_query->data()->discord_id != 010) {

                $json = self::assembleJson($user_query->data()->discord_id, 'add_role_id', self::getDiscordRoleId(DB::getInstance(), $group));

                $result = self::discordBotRequest('/roleChange', $json);
                if ($result != 'success') {

                    $errors = self::parseErrors($result, $language);

                    if ($redirect) {
                        Session::flash('edit_user_errors', $errors);
                        Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->data()->id)));
                        die();
                    } else return $errors;
                }
            }
        }
    }

    private static function parseErrors($result, Language $language) {
        $errors = array();

        if ($result === false) {
            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc) OR they have `allow_url_fopen` disabled in php.ini
            $errors[] = $language->get('user', 'discord_communication_error');
        } else {
            switch ($result) {

                case 'badparameter':
                case 'error':
                case 'invguild':
                case 'invuser':
                case 'notlinked':
                case 'unauthorized':
                case 'invrole':
                    $errors[] = $language->get('admin', 'discord_bot_error_' . $result);
                    break;
                default:
                    // This should never happen
                    $errors[] = $language->get('user', 'discord_unknown_error');
                    break;
            }
        }

        return $errors;
    }
    
    private static function assembleJson($user_id, $action, $role_id) {
        // TODO cache or define() website api key and discord guild id
        $return = array();
        $return['guild_id'] = trim(Output::getClean(Util::getSetting(DB::getInstance(), 'discord')));
        $return['user_id'] = $user_id;
        $return['api_key'] = trim(Output::getClean(Util::getSetting(DB::getInstance(), 'mc_api_key')));
        $return[$action] = $role_id;
        return json_encode($return);
    }
}
