<?php
/**
 * @param string $url New Discord bot URL
 * @param string $id New Discord Guild/server ID
 *
 * @return string JSON Array
 */
class UpdateDiscordBotSettingsEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'updateDiscordBotSettings';
        $this->_module = 'Discord Integration';
        $this->_description = 'Updates the Discord Bot URL and/or Guild ID setting';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        if (isset($_POST['url'])) {
            if ($_POST['url'] == null) {
                $api->throwError(30, Discord::getLanguageTerm('unable_to_set_discord_bot_url'), '$_POST[\'url\'] is null.');
            }

            try {
                $api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', [$_POST['url'], 'discord_bot_url']);
            } catch (Exception $e) {
                $api->throwError(30, Discord::getLanguageTerm('unable_to_set_discord_bot_url'), $e->getMessage());
            }
        }

        if (isset($_POST['guild_id'])) {
            try {
                $api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', [$_POST['guild_id'], 'discord']);
            } catch (Exception $e) {
                $api->throwError(33, Discord::getLanguageTerm('unable_to_set_discord_id'),  $e->getMessage());
            }
        }

        if (isset($_POST['bot_username'])) {
            try {
                $api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', [$_POST['bot_username'], 'discord_bot_username']);
            } catch (Exception $e) {
                $api->throwError(33, Discord::getLanguageTerm('unable_to_set_discord_bot_username'), $e->getMessage());
            }
        }

        if (isset($_POST['bot_user_id'])) {
            // TODO
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
    }
}
