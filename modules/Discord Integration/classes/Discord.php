<?php
/**
 * Discord utility class
 *
 * @package Modules\DiscordIntegration
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
     * @var Language Instance of Language class for translations
     */
    private static Language $_discord_integration_language;

    /**
     * Update a user's roles in the Discord guild.
     *
     * @param User $user The user whose roles to update
     * @param array $added Array of Discord role IDs to add
     * @param array $removed Array of Discord role IDs to remove
     * @return false|array Roles added and removed with their status. False if error
     */
    public static function updateDiscordRoles(User $user, array $added, array $removed) {
        if (!self::isBotSetup()) {
            return false;
        }

        $integrationUser = $user->getIntegration('Discord');
        if ($integrationUser === null || !$integrationUser->isVerified()) {
            return false;
        }

        // Filter out any `null` values that snuck into $added or $removed
        $added = array_filter($added);
        $removed = array_filter($removed);

        $user_discord_id = $integrationUser->data()->identifier;
        $role_changes = [];
        foreach ($added as $role) {
            $role_changes[] = [
                'user_id' => $user_discord_id,
                'role_id' => $role,
                'action' => 'add'
            ];
        }
        foreach ($removed as $role) {
            $role_changes[] = [
                'user_id' => $user_discord_id,
                'role_id' => $role,
                'action' => 'remove'
            ];
        }

        $result = self::botRequest('/applyRoleChanges', json_encode([
            'guild_id' => self::getGuildId(),
            'api_key' => Util::getSetting('mc_api_key'),
            'role_changes' => $role_changes
        ]));

        // No point to log the HTTP request failure here, `botRequest` already does it
        if ($result === false) {
            return false;
        }

        $result = json_decode($result, true);

        $status = $result['status'];
        if ($status !== 'success') {
            $meta = $result['meta'] ?? '';

            switch ($status) {
                case 'bad_request':
                case 'not_linked':
                case 'unauthorized':
                case 'invalid_guild':
                    Log::getInstance()->log(Log::Action('discord/role_set'), "Status: $status, meta: $meta", $user->data()->id);
                    break;
                default:
                    Log::getInstance()->log(Log::Action('discord/role_set'), "Invalid 'status' response from bot $status", $user->data()->id);
            }

            return false;
        }

        $role_changes = $result['role_changes'];
        return array_map(static fn (array $role_change) => [
            'group_id' => $role_change['role_id'],
            'status' => $role_change['status']
        ], $role_changes);
    }

    /**
     * @return bool Whether the Discord bot is set up properly
     */
    public static function isBotSetup(): bool {
        return self::$_is_bot_setup ??= Util::getSetting('discord_integration');
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
     * @return string|null Discord guild ID for this site
     */
    public static function getGuildId(): ?string {
        return Util::getSetting('discord');
    }

    /**
     * Make a request to the Discord bot.
     *
     * @param string $url URL of the Discord bot instance
     * @param string|null $body Body of the request
     * @return false|string Response from the Discord bot or false if the request failed
     */
    public static function botRequest(string $url, ?string $body = null) {
        $client = HttpClient::post(BOT_URL . $url, $body);

        if ($client->hasError()) {
            Log::getInstance()->log(Log::Action('discord/bot_request_failed'), $client->getError());
            return false;
        }

        return $client->contents();
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
