<?php
/**
 * Discord utility class
 *
 * @package Modules\Discord Integration
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Discord {

    /**
     * @var bool Whether the Discord bot is set up properly
     */
    private static bool $_is_bot_setup;

    /**
     * @var int|null The ID of this website's Discord server
     */
    private static ?int $_guild_id;

    /**
     * @var Language Instance of Language class for translations
     */
    private static Language $_discord_integration_language;

    /**
     * @var array|string[] Valid responses from the Discord bot
     */
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

    /**
     * Update a user's roles in the Discord guild.
     *
     * @param User $user The user whose roles to update
     * @param array $added Array of Discord role IDs to add
     * @param array $removed Array of Discord role IDs to remove
     * @return bool Whether the request was successful or not
     */
    public static function updateDiscordRoles(User $user, array $added, array $removed): bool {
        if (!self::isBotSetup()) {
            return false;
        }

        $integrationUser = $user->getIntegration('Discord');
        if ($integrationUser == null || !$integrationUser->isVerified()) {
            return false;
        }

        $changed_arr = array_merge(self::assembleGroupArray($added, 'add'), self::assembleGroupArray($removed, 'remove'));

        if (!count($changed_arr)) {
            return false;
        }

        $json = self::assembleJson($integrationUser->data()->identifier, $changed_arr);

        $result = self::discordBotRequest('/roleChange', $json);

        if ($result == 'fullsuccess') {
            return true;
        }

        if ($result == 'partsuccess') {
            Log::getInstance()->log(Log::Action('discord/role_set'), self::getLanguageTerm('discord_bot_error_partsuccess'));
            return true;
        }

        $errors = self::parseErrors($result);

        foreach ($errors as $error) {
            Log::getInstance()->log(Log::Action('discord/role_set'), $error, $user->data()->id, Util::getRemoteAddress());
        }

        return false;
    }

    /**
     * @return bool Whether the Discord bot is set up properly
     */
    public static function isBotSetup(): bool {
        return self::$_is_bot_setup ??= Util::getSetting('discord_integration');
    }

    /**
     * Create a JSON object to send to the Discord bot.
     *
     * @param array $role_ids Array of Discord role IDs to add or remove
     * @param string $action Whether to 'add' or 'remove' the groups
     * @return array Assembled array of Discord role IDs and their action
     */
    private static function assembleGroupArray(array $role_ids, string $action): array {
        $return = [];

        foreach ($role_ids as $role_id) {
            $return[] = [
                'id' => $role_id,
                'action' => $action
            ];
        }

        return $return;
    }

    /**
     * Get the associated NamelessMC group ID for a Discord role.
     *
     * @param DB $db Instance of DB class
     * @param int $nameless_group_id The ID of the NamelessMC group
     * @return null|int The Discord role ID for the NamelessMC group
     */
    public static function getDiscordRoleId(DB $db, int $nameless_group_id): ?int {
        $nameless_injector = GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class);

        $discord_role_id = $db->get('group_sync', [$nameless_injector->getColumnName(), $nameless_group_id]);
        if ($discord_role_id->count()) {
            return $discord_role_id->first()->discord_role_id;
        }

        return null;
    }

    /**
     * Create a JSON objec to send to the Discord bot.
     *
     * @param int $user_id Discord user ID to affect
     * @param array $change_arr Array of Discord role IDs to add or remove (compiled with `assembleGroupArray`)
     * @return string JSON object to send to the Discord bot
     */
    private static function assembleJson(int $user_id, array $change_arr): string {
        // TODO cache or define() website api key and discord guild id
        return json_encode([
            'guild_id' => trim(self::getGuildId()),
            'user_id' => $user_id,
            'api_key' => trim(Util::getSetting('mc_api_key')),
            'roles' => $change_arr,
        ]);
    }

    /**
     * @return int|null Discord guild ID for this site
     */
    public static function getGuildId(): ?int {
        if (!isset(self::$_guild_id)) {
            self::$_guild_id = (int) Util::getSetting('discord');
        }

        return self::$_guild_id;
    }

    /**
     * Make a request to the Discord bot.
     *
     * @param string $url URL of the Discord bot instance
     * @param string|null $body Body of the request
     * @return false|string Response from the Discord bot or false if the request failed
     */
    private static function discordBotRequest(string $url = '/status', ?string $body = null) {
        $client = HttpClient::post(BOT_URL . $url, $body);

        if ($client->hasError()) {
            Log::getInstance()->log(Log::Action('discord/role_set'), $client->getError());
            return false;
        }

        $response = $client->contents();

        if (in_array($response, self::$_valid_responses)) {
            return $response;
        }

        // Log unknown error from bot
        Log::getInstance()->log(Log::Action('discord/role_set'), $response);
        return false;
    }

    /**
     * Get a language term for the Discord Integration module.
     *
     * @param string $term Term to search for
     * @param array $variables Variables to replace in the term
     * @return string Language term from the language file
     */
    public static function getLanguageTerm(string $term, array $variables = []): string {
        if (!isset(self::$_discord_integration_language)) {
            self::$_discord_integration_language = new Language(ROOT_PATH . '/modules/Discord Integration/language');
        }

        return self::$_discord_integration_language->get('discord_integration', $term, $variables);
    }

    /**
     * Parse errors from a request to the Discord bot.
     *
     * @param mixed $result Result of the Discord bot request
     * @return array Array of errors during a request to the Discord bot
     */
    private static function parseErrors($result): array {
        if ($result === false) {
            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc)
            // OR they have `allow_url_fopen` disabled in php.ini OR the bot returned a new error (they should always check logs)
            return [
                self::getLanguageTerm('discord_communication_error'),
                self::getLanguageTerm('discord_bot_check_logs'),
            ];
        }

        if (in_array($result, self::$_valid_responses)) {
            return [self::getLanguageTerm('discord_bot_error_' . $result)];
        }

        // This should never happen
        return [self::getLanguageTerm('discord_unknown_error')];
    }

    /**
     * Cache Discord roles.
     *
     * @param mixed $roles Discord roles to cache
     */
    public static function saveRoles($roles): void {
        $roles = [json_encode($roles)];
        file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache', $roles);
    }

    /**
     * Get cached Discord roles.
     *
     * @return array Cached Discord roles
     */
    public static function getRoles(): array {
        if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache')) {
            return json_decode(file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache'), true);
        }

        return [];
    }
}
