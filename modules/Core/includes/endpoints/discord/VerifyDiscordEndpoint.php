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
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['token', 'discord_id', 'discord_username']);

        $token = Output::getClean($_POST['token']);
        $discord_id = Output::getClean($_POST['discord_id']);
        $discord_username = Output::getClean($_POST['discord_username']);

        // Find the user's NamelessMC id
        $verification = $api->getDb()->get('discord_verifications', array('token', '=', $token));
        if (!$verification->count()) {
            $api->throwError(28, $api->getLanguage()->get('api', 'no_pending_verification_for_token'));
        }
        $id = $verification->first()->user_id;

        // Ensure the user exists
        $api->getUser('id', $id);

        try {
            $api->getDb()->update('users', $id, array('discord_id' => $discord_id));
            $api->getDb()->update('users', $id, array('discord_username' => $discord_username));
            $api->getDb()->delete('discord_verifications', array('user_id', '=', $id));
        } catch (Exception $e) {
            $api->throwError(29, $api->getLanguage()->get('api', 'unable_to_set_discord_id'), $e->getMessage());
        }
        
        $api->validateParams($_POST, ['user', 'groups']);

        // Ensure user exists
        $user = new User($_POST['user']);

        $groups = $_POST['groups'];
        if ($groups == null || !count($groups)) {
            $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'), 'No groups provided');
        }

        foreach ($groups as $group) {
            $group_query = $api->getDb()->get('groups', array('id', '=', $group));
            if (!$group_query->count()) {
                continue;
            }

            // Attempt to update their discord role as well, but ignore any output/errors
            Discord::addDiscordRole($user, $group, $api->getLanguage(), false);
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_id_set')));
    }
}
