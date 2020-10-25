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

    public static function discordBotRequest($url = '/') {

        $bot_url_attempt = Util::curlGetContents(BOT_URL . $url);
        $valid_responses = array('success', 'failure-cannot-interact', 'failure-invalid-api-url');
        if (in_array($bot_url_attempt, $valid_responses)) return $bot_url_attempt;
        else {
            $backup_bot_url_attempt = Util::curlGetContents(BOT_URL_BACKUP . $url);
            if (in_array($backup_bot_url_attempt, $valid_responses)) return $backup_bot_url_attempt;
            else return false;
        }
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
            if ($group->count()) {
                $group_array = array();
                $group_array['group'] = $group->first();
                $group_array['primary'] = $db->query('SELECT `primary` FROM nl2_group_sync WHERE discord_role_id = ?', array($discord_role_id))->first()->primary;
                return $group_array;
            }
        }
        return null;
    }

    public static function removeDiscordRole($user_query, $group, Language $language) {
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            // They have a valid discord Id
            if ($user_query->discord_id != null && $user_query->discord_id != 010) {

                $group_discord_id = self::getDiscordRoleId(DB::getInstance(), $group);

                $api_url = rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean(Util::getSetting(DB::getInstance(), 'mc_api_key')), '', 'non-friendly'), '/');

                // TODO: Probably a nicer way to do this
                $url = '/roleChange?id=' . $user_query->discord_id . '&guild_id=' . Util::getSetting(DB::getInstance(), 'discord');

                if ($group_discord_id == null) return;

                $url .= '&role=null&oldRole=' . $group_discord_id;

                if ($url != null) {
                    $result = self::discordBotRequest($url . '&api_url=' . $api_url . '/');
                    if ($result != 'success') {
                        if ($result === false) {
                            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc) OR they have `allow_url_fopen` disabled in php.ini
                            $errors[] = $language->get('user', 'discord_communication_error');
                        } else {
                            switch ($result) {
                                case 'failure-cannot-interact':
                                    $errors[] = $language->get('admin', 'discord_cannot_interact');
                                    break;
                                case 'failure-invalid-api-url':
                                    $errors[] = $language->get('admin', 'discord_invalid_api_url');
                                    break;
                                default:
                                    // This should never happen 
                                    $errors[] = $language->get('user', 'discord_unknown_error');
                                    break;
                            }
                        }
                        Session::flash('edit_user_errors', $errors);
                        Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id)));
                        die();
                    }
                }
            }
        }
    }

    public static function addDiscordRole($user_query, $group, Language $language, $redirect = true) {
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            // They have a valid discord Id
            if ($user_query->discord_id != null && $user_query->discord_id != 010) {

                $group_discord_id = self::getDiscordRoleId(DB::getInstance(), $group);

                $old_group_discord_id = self::getDiscordRoleId(DB::getInstance(), $user_query->group_id);

                $api_url = rtrim(Util::getSelfURL(), '/') . rtrim(URL::build('/api/v2/' . Output::getClean(Util::getSetting(DB::getInstance(), 'mc_api_key')), '', 'non-friendly'), '/');

                // The bot can handle null roles, but it is better to deal with it here
                // TODO: Probably a nicer way to do this
                $url = '/roleChange?id=' . $user_query->discord_id . '&guild_id=' . Util::getSetting(DB::getInstance(), 'discord');

                if ($group_discord_id == $old_group_discord_id) {
                    $url .= '&role=' . $group_discord_id . '&oldRole=null';
                } else {
                    if ($group_discord_id == null && $old_group_discord_id != null) {
                        $url .= '&role=null' . '&oldRole=' . $old_group_discord_id;
                    } else if ($group_discord_id != null && $old_group_discord_id == null) {
                        $url .= '&role=' . $group_discord_id . '&oldRole=null';
                    } else if ($group_discord_id != null && $old_group_discord_id != null) {
                        $url .= '&role=' . $group_discord_id . '&oldRole=' . $old_group_discord_id;
                    } else $url = null;
                }

                if ($url != null) {
                    $result = self::discordBotRequest($url . '&api_url=' . $api_url . '/');
                    if ($result != 'success') {
                        if ($result === false) {
                            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc) OR they have `allow_url_fopen` disabled in php.ini
                            $errors[] = $language->get('user', 'discord_communication_error');
                        } else {
                            switch ($result) {
                                case 'failure-cannot-interact':
                                    $errors[] = $language->get('admin', 'discord_cannot_interact');
                                    break;
                                case 'failure-invalid-api-url':
                                    $errors[] = $language->get('admin', 'discord_invalid_api_url');
                                    break;
                                default:
                                    // This should never happen 
                                    $errors[] = $language->get('user', 'discord_unknown_error');
                                    break;
                            }
                        }
                        if ($redirect) {
                            Session::flash('edit_user_errors', $errors);
                            Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->id)));
                            die();
                        } else return $errors;
                    }
                }
            }
        }
    }
}