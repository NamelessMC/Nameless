<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Discord Integration module helper class
 */
class Discord {

    private static bool $_is_bot_setup;
    private static ?int $_guild_id;
    private static Language $_discord_integration_language;

    private static array $_valid_responses = [
        'fullsuccess',
        'badparameter',
        'error',
        'invguild',
        'invuser',
        'notlinked',
        'unauthorized',
        'invrole',
    ];

    public static function discordBotRequest(string $url = '/status', ?string $body = null) {
        $response = HttpClient::post(BOT_URL . $url, $body);

        if (in_array($response, self::$_valid_responses)) {
            return $response;
        }

        // Log unknown error from bot
        Log::getInstance()->log(Log::Action('discord/role_set'), $response);
        return false;
    }

    public static function getDiscordRoleId(DB $db, int $nameless_group_id) {
        $nameless_injector = GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class);

        $discord_role_id = $db->get('group_sync', [$nameless_injector->getColumnName(), '=', $nameless_group_id]);
        if ($discord_role_id->count()) {
            return $discord_role_id->first()->discord_role_id;
        }

        return null;
    }

    public static function updateDiscordRoles(User $user_query, array $added, array $removed): bool {
        if (!self::isBotSetup()) {
            return false;
        }

        if ($user_query->data()->discord_id == null || $user_query->data()->discord_id == 010) {
            return false;
        }

        $added_arr = self::assembleGroupArray($added, 'add');
        $removed_arr = self::assembleGroupArray($removed, 'remove');

        if (!count($added_arr) && !count($removed_arr)) {
            return false;
        }

        $json = self::assembleJson($user_query->data()->discord_id, $added_arr, $removed_arr);

        $result = self::discordBotRequest('/roleChange', $json);

        if ($result == 'fullsuccess') {
            return true;
        }

        if ($result == 'partsuccess') {
            Log::getInstance()->log(Log::Action('discord/role_set'), Discord::getLanguageTerm('discord_bot_error_partsuccess'));
            return true;
        }

        $errors = self::parseErrors($result);

        foreach ($errors as $error) {
            Log::getInstance()->log(Log::Action('discord/role_set'), $error, $user_query->data()->id, $user_query->getIP());
        }

        return false;
    }

    public static function saveRoles($roles): void {
        $roles = [json_encode($roles)];
        file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache', $roles);
    }

    public static function getRoles(): array {
        if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache')) {
            return json_decode(file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache'), true);
        }
        
        return [];
    }

    private static function parseErrors($result): array {
        if ($result === false) {
            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc)
            // OR they have `allow_url_fopen` disabled in php.ini OR the bot returned a new error (they should always check logs)
            return [
                Discord::getLanguageTerm('discord_communication_error'),
                Discord::getLanguageTerm('discord_bot_check_logs'),
            ];
        }

        if (in_array($result, self::$_valid_responses)) {
            return [Discord::getLanguageTerm('discord_bot_error_' . $result)];
        }

        // This should never happen
        return [Discord::getLanguageTerm('discord_unknown_error')];
    }

    private static function assembleGroupArray(array $groups, string $action): array {
        $return = [];

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
    
    private static function assembleJson(int $user_id, array $added_arr, array $removed_arr): string {
        // TODO cache or define() website api key and discord guild id
        return json_encode([
            'guild_id' => trim(self::getGuildId()),
            'user_id' => $user_id,
            'api_key' => trim(Output::getClean(Util::getSetting(DB::getInstance(), 'mc_api_key'))),
            'roles' => array_merge($added_arr, $removed_arr),
        ]);
    }

    public static function isBotSetup(): bool {
        if (!isset(self::$_is_bot_setup)) {
            self::$_is_bot_setup = Util::getSetting(DB::getInstance(), 'discord_integration');
        }

        return self::$_is_bot_setup;
    }

    public static function getGuildId(): ?int {
        if (!isset(self::$_guild_id)) {
            self::$_guild_id = Util::getSetting(DB::getInstance(), 'discord');
        }

        return self::$_guild_id;
    }

    public static function getLanguageTerm(string $term): string {
        if (!isset(self::$_discord_integration_language)) {
            self::$_discord_integration_language = new Language(ROOT_PATH . '/modules/Discord Integration/language', LANGUAGE);
        }

        return self::$_discord_integration_language->get('discord_integration', $term);
    }
}
