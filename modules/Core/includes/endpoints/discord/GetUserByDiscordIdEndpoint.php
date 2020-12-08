<?php

/**
 * @param int $discord_id The Discord User ID to find
 * 
 * @return string JSON Array
 */
class GetUserByDiscordIdEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'getUserByDiscordId';
        $this->_module = 'Core';
        $this->_description = 'Returns the NamelessMC user ID assosiated with the Discord User ID provided.';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['discord_id'])) {
                if (!Util::getSetting($api->getDb(), 'discord_integration')) $api->throwError(34, $api->getLanguage()->get('api', 'discord_integration_disabled'));

                $discord_id = $_POST['discord_id'];

                $user = $api->getUser('discord_user_id', $discord_id);

                $api->returnArray(array('message' => $user->data()->id));
            }
        }
    }
}