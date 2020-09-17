<?php

/**
 * Set a NamelessMC user's Discord ID using their validation token
 * 
 * @param string $token The token of the user to update
 * @param int $discord_id The user's Discord user ID to set
 * 
 * @return string JSON Array
 */
class SetDiscordIdEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'setDiscordId';
        $this->_module = 'Core';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['token', 'discord_id'])) {

                $token = Output::getClean($_POST['token']);
                $discord_id = $_POST['discord_id'];

                // Find their id 
                $id = $api->getDb()->get('discord_verifications', array('token', '=', $token));
                if (!$id->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $id = $id->first()->user_id;

                // Find the user with the id
                $user = $api->getDb()->get('users', array('id', '=', $id));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $user = $user->first()->id;

                try {
                    $api->getDb()->update('users', $user, array(
                        'discord_id' => $discord_id
                    ));
                    $api->getDb()->delete('discord_verifications', array('user_id', '=', $user));
                } catch (Exception $e) {
                    $api->throwError(23, $api->getLanguage()->get('api', 'unable_to_set_discord_id'));
                }
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_id_set')));
            }
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}