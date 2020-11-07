<?php
/**
 * @param string $url New Discord bot URL
 *
 * @return string JSON Array
 */
class SetDiscordBotURLEndpoint extends EndpointBase {
	public function __construct() {
		$this->_route = 'setDiscordBotUrl';
		$this->_module = 'Core';
		$this->_description = 'Updates the Discord bot URL setting';
	}

	public function execute(Nameless2API $api) {
		if ($api->isValidated()) {
			if ($api->validateParams($_POST, ['url'])) {
				try {
					$api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = ?', array($_POST['url'], 'discord_bot_url'));
				} catch (Exception $e) {
					$api->throwError(30, $api->getLanguage()->get('api', 'unable_to_set_discord_bot_url'));
				}

				$api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_bot_url_updated')));
			}
		}
	}
}