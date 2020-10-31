<?php
/**
 * @param string $token The token of the user to update
 * @param int $discord_id The user's Discord user ID to set
 * 
 * @return string JSON Array
 */
class VerifyDiscordEndpoint extends EndpointBase {
    public function __construct() {
        $this->_route = 'verifyDiscord';
        $this->_module = 'Core';
        $this->_description = 'Verify and link a NamelessMC user\'s Discord account using their validation token';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['token', 'discord_id'])) {
                $token = Output::getClean($_POST['token']);
                $discord_id = Output::getClean($_POST['discord_id']);

                // Find the user's NamelessMC id
                $verification = $api->getDb()->get('discord_verifications', array('token', '=', $token));
                if (!$verification->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $id = $verification->first()->user_id;

                // Make sure the user who sent the bot command matches the discord user id in namelessmc pending verifications
                $discord_user_id = $verification->first()->discord_user_id;
                if ($discord_id != $discord_user_id) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));

                // Ensure the user exists
                $api->getUser('id', $id);

                try {
                    $api->getDb()->update('users', $id, array('discord_id' => $discord_id));
                    $api->getDb()->delete('discord_verifications', array('user_id', '=', $id));
                } catch (Exception $e) {
                    $api->throwError(29, $api->getLanguage()->get('api', 'unable_to_set_discord_id'));
                }

                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_id_set')));
            }
        }
    }
}