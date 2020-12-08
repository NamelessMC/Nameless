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
		$this->_module = 'Core';
		$this->_description = 'Updates the Discord Bot URL and/or Guild ID setting';
	}

	public function execute(Nameless2API $api) {
		if ($api->isValidated()) {
			
			if (!isset($_POST['url']) && !isset($_POST['guild_id'])) {
				$api->throwError(31, $api->getLanguage()->get('api', 'provide_one_discord_settings'));
			}

			if (isset($_POST['url'])) {
				try {
					$api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', array($_POST['url'], 'discord_bot_url'));
				} catch (Exception $e) {
					$api->throwError(30, $api->getLanguage()->get('api', 'unable_to_set_discord_bot_url'));
				}
			}

			if (isset($_POST['guild_id'])) {
				try {
					$api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', array($_POST['guild_id'], 'discord'));
				} catch (Exception $e) {
					$api->throwError(33, $api->getLanguage()->get('api', 'unable_to_set_discord_id'));
				}
			}

			$api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_settings_updated')));
		}
	}
}