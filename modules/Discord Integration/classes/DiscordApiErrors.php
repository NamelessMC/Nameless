<?php
/**
 * Contains namespaced API error messages for the Discord Integration module.
 * These have no versioning, and are not meant to be used by any other modules.
 *
 * @package Modules\DiscordIntegration
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class DiscordApiErrors {

    public const ERROR_DISCORD_INTEGRATION_DISABLED = 'discord_integration:discord_integration_disabled';

    public const ERROR_UNABLE_TO_UPDATE_DISCORD_ROLES = 'discord_integration:unable_to_update_discord_roles';

    public const ERROR_UNABLE_TO_SET_DISCORD_BOT_URL = 'discord_integration:unable_to_set_discord_bot_url';
    public const ERROR_UNABLE_TO_SET_DISCORD_GUILD_ID = 'discord_integration:unable_to_set_discord_guild_id';
    public const ERROR_UNABLE_TO_SET_DISCORD_BOT_USERNAME = 'discord_integration:unable_to_set_discord_bot_username';
}
