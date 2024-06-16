<?php
use Symfony\Component\HttpFoundation\Response;

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
            try {
                Settings::set('discord_bot_url', $_POST['url']);
            } catch (Exception $e) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_BOT_URL, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        if (isset($_POST['guild_id'])) {
            try {
                Settings::set('discord', $_POST['guild_id']);
            } catch (Exception $e) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_GUILD_ID, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        if (isset($_POST['bot_username'])) {
            try {
                Settings::set('discord_bot_username', $_POST['bot_username']);
            } catch (Exception $e) {
                $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_BOT_USERNAME, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // If bot url and username is empty then its setup for the first time
        if (empty(BOT_URL) && empty(BOT_USERNAME)) {
            Settings::set('discord_integration', 1);
        }

        if (isset($_POST['bot_user_id'])) {
            Settings::set('discord_bot_user_id', $_POST['bot_user_id']);
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
    }
}
