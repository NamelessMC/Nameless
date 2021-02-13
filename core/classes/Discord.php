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

    private static $_valid_responses = array('success', 'badparameter', 'error', 'invguild', 'invuser', 'notlinked', 'unauthorized', 'invrole');

    public static function discordBotRequest($url = '/status', $body = null) {
        $response = Util::curlGetContents(BOT_URL . $url, $body);
        if (in_array($response, self::$_valid_responses)) return $response;
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

                $role_id = self::getDiscordRoleId(DB::getInstance(), $group);

                if ($role_id != null) {
                    $json = self::assembleJson($user_query->data()->discord_id, 'remove_role_id', $role_id);

                    $result = self::discordBotRequest('/roleChange', $json);
                    if ($result != 'success') {

                        if ($result != 'hierarchy') {

                            Session::flash('edit_user_errors', self::parseErrors($result, $language));
                            Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->data()->id)));
                            die();
                        } else {
                            Session::flash('edit_user_warnings', array($language->get('admin', 'discord_bot_error_hierarchy')));
                        }
                    }
                }
            }
        }
    }

    public static function addDiscordRole($user_query, $group, Language $language, $redirect = true) {
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            if ($user_query->data()->discord_id != null && $user_query->data()->discord_id != 010) {

                $role_id = self::getDiscordRoleId(DB::getInstance(), $group);

                if ($role_id != null) {
                    $json = self::assembleJson($user_query->data()->discord_id, 'add_role_id', $role_id);

                    $result = self::discordBotRequest('/roleChange', $json);
                    if ($result != 'success') {

                        if ($result != 'hierarchy') {

                            $errors = self::parseErrors($result, $language);

                            if ($redirect) {
                                Session::flash('edit_user_errors', $errors);
                                Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->data()->id)));
                                die();
                            } else return $errors;
                        } else {
                            if ($redirect) {
                                Session::flash('edit_user_warnings', array($language->get('admin', 'discord_bot_error_hierarchy')));
                            }
                        }
                    }
                }
            }
        }
    }

    public static function saveRoles($roles) {
        $roles = array(json_encode($roles));
        file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache', $roles);
    }

    public static function getRoles() {
        if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache')) {
            return json_decode(file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache'), true);
        }
        return array();
    }

    private static function parseErrors($result, Language $language) {
        $errors = array();

        if ($result === false) {
            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc) OR they have `allow_url_fopen` disabled in php.ini
            $errors[] = $language->get('user', 'discord_communication_error');
        } else {
            if (in_array($result, self::$_valid_responses)) {
                $errors[] = $language->get('admin', 'discord_bot_error_' . $result);
            } else {
                // This should never happen
                $errors[] = $language->get('user', 'discord_unknown_error');
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
