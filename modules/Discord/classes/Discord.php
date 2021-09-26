<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Discord class
 */
class Discord {

    private static $_valid_responses = [
        'fullsuccess',
        'badparameter',
        'error',
        'invguild',
        'invuser',
        'notlinked',
        'unauthorized',
        'invrole'
    ];

    public static function discordBotRequest($url = '/status', $body = null) {
        $response = Util::curlGetContents(BOT_URL . $url, $body);

        if (in_array($response, self::$_valid_responses)) {
            return $response;
        }

        // Log unknown error from bot
        Log::getInstance()->log(Log::Action('discord/role_set'), $response);
        return false;
    }

    public static function getDiscordRoleId(DB $db, $group_id) {
        $discord_role_id = $db->get('group_sync', array('website_group_id', '=', $group_id));
        if ($discord_role_id->count()) {
            return $discord_role_id->first()->discord_role_id;
        }

        return null;
    }

    public static function getWebsiteGroup(DB $db, $discord_role_id) {
        $website_group_id = $db->get('group_sync', array('discord_role_id', '=', $discord_role_id));
        if ($website_group_id->count()) {
            $group = $db->get('groups', array('id', '=', $website_group_id->first()->website_group_id));
            if ($group->count()) {
                return $group->first();
            }
        }

        return null;
    }

    public static function updateDiscordRoles(User $user_query, $added, $removed, Language $language, $redirect = true) {

        if (!Util::getSetting(DB::getInstance(), 'discord_integration')) {
            return;
        }

        if ($user_query->data()->discord_id == null || $user_query->data()->discord_id == 010) {
            return;
        }

        $added_arr = self::assembleGroupArray($added, 'add');
        $removed_arr = self::assembleGroupArray($removed, 'remove');

        if (!count($added_arr) && !count($removed_arr)) {
            return;
        }

        $json = self::assembleJson($user_query->data()->discord_id, $added_arr, $removed_arr);

        $result = self::discordBotRequest('/roleChange', $json);

        if ($result == 'fullsuccess') {
            return;
        }

        // TODO: Add logging of this, as most people will want to be aware if this is an issue
        if ($result == 'partsuccess') {
            if ($redirect) {
                Session::flash('edit_user_warnings', array($language->get('admin', 'discord_bot_error_hierarchy')));
            }

            return;
        }

        $errors = self::parseErrors($result, $language);

        if ($redirect) {
            Session::flash('edit_user_errors', $errors);
            Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->data()->id)));
            die();
        }

        return $errors;
    }

    public static function saveRoles($roles) {
        $roles = array(json_encode($roles));
        file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache', $roles);
    }

    public static function getRoles() {
        if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache')) {
            return json_decode(file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache'), true);
        }
        
        return [];
    }

    private static function parseErrors($result, Language $language) {
        if ($result === false) {
            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc) OR they have `allow_url_fopen` disabled in php.ini OR the bot returned a new error (they should always check logs)
            return [
                $language->get('user', 'discord_communication_error'),
                $language->get('admin', 'discord_bot_check_logs'),
            ];
        }

        if (in_array($result, self::$_valid_responses)) {
            return [$language->get('admin', 'discord_bot_error_' . $result)];
        }

        // This should never happen
        return [$language->get('user', 'discord_unknown_error')];
    }

    private static function assembleGroupArray($groups, $action) {
        $return = array();

        foreach ($groups as $group) {
            $discord_id = self::getDiscordRoleId(DB::getInstance(), $group);

            if ($discord_id == null) {
                continue;
            }

            $return[] = [
                'id' => $discord_id,
                'action' => $action
            ];
        }

        return $return;
    }
    
    private static function assembleJson($user_id, $added_arr, $removed_arr) {
        // TODO cache or define() website api key and discord guild id
        return json_encode([
            'guild_id' => trim(Output::getClean(Util::getSetting(DB::getInstance(), 'discord'))),
            'user_id' => $user_id,
            'api_key' => trim(Output::getClean(Util::getSetting(DB::getInstance(), 'mc_api_key'))),
            'roles' => array_merge($added_arr, $removed_arr),
        ]);
    }
}
