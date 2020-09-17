<?php

/**
 * @param string $token The token of the user to update
 * @param int $discord_id The user's Discord user ID to set
 * 
 * @return string JSON Array
 */
class SetDiscordIdEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'setDiscordId';
        $this->_module = 'Core';
        $this->_description = 'Set a NamelessMC user\'s Discord ID using their validation token';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['token', 'discord_id'])) {

                $token = Output::getClean($_POST['token']);
                $discord_id = Output::getClean($_POST['discord_id']);

                // Find the user's NamelessMC id
                $id = $api->getDb()->get('discord_verifications', array('token', '=', $token));
                if (!$id->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $id = $id->first()->user_id;

                // Ensure the user exists
                $user = $api->getDb()->get('users', array('id', '=', $id));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));

                try {
                    $api->getDb()->update('users', $id, array('discord_id' => $discord_id));
                    $api->getDb()->delete('discord_verifications', array('user_id', '=', $id));
                } catch (Exception $e) {
                    $api->throwError(23, $api->getLanguage()->get('api', 'unable_to_set_discord_id'));
                }

                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_id_set')));
            }
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}