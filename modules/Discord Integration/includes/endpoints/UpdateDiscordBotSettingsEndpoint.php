<?php

/**
 * @param string $url New Discord bot URL
 * @param string $id New Discord Guild/server ID
 *
 * @return string JSON Array
 */
class UpdateDiscordBotSettingsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/update-bot-settings';
        $this->_module = 'Discord Integration';
        $this->_description = 'Updates the Discord Bot URL and/or Guild ID setting';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        if (isset($_POST['url'])) {
            if ($_POST['url'] == null) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_BOT_URL, "\$_POST['url'] is null.");
            }

            try {
                $api->getDb()->query('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', [$_POST['url'], 'discord_bot_url']);
            } catch (Exception $e) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_BOT_URL, $e->getMessage(), 500);
            }
        }

        if (isset($_POST['guild_id'])) {
            try {
                $api->getDb()->query('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', [$_POST['guild_id'], 'discord']);
            } catch (Exception $e) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_GUILD_ID, $e->getMessage(), 500);
            }
        }

        if (isset($_POST['bot_username'])) {
            try {
                $api->getDb()->query('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', [$_POST['bot_username'], 'discord_bot_username']);
            } catch (Exception $e) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_BOT_USERNAME, $e->getMessage(), 500);
            }
        }

        // If bot url and username is empty then its setup for the first time
        if (empty(BOT_URL) && empty(BOT_USERNAME)) {
            $api->getDb()->query("UPDATE nl2_settings SET `value` = 1 WHERE `name` = 'discord_integration'");
        }

        if (isset($_POST['bot_user_id'])) {
            // TODO
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
    }
}
