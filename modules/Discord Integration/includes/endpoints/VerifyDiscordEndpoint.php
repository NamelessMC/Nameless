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
        $this->_module = 'Discord Integration';
        $this->_description = 'Verify and link a NamelessMC user\'s Discord account using their validation token';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['token', 'discord_id', 'discord_username']);

        $token = Output::getClean($_POST['token']);
        $discord_id = Output::getClean($_POST['discord_id']);
        $discord_username = Output::getClean($_POST['discord_username']);

        // Find the user's NamelessMC id
        $verification = $api->getDb()->get('discord_verifications', ['token', '=', $token]);
        if (!$verification->count()) {
            $api->throwError(28, Discord::getLanguageTerm('no_pending_verification_for_token'));
        }
        $id = $verification->first()->user_id;

        // Ensure the user exists
        $api->getUser('id', $id);

        try {
            $api->getDb()->update('users', $id, ['discord_id' => $discord_id]);
            $api->getDb()->update('users', $id, ['discord_username' => $discord_username]);
            $api->getDb()->delete('discord_verifications', ['user_id', '=', $id]);
        } catch (Exception $e) {
            $api->throwError(29, Discord::getLanguageTerm('unable_to_set_discord_id'), $e->getMessage());
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_id_set')]);
    }
}
