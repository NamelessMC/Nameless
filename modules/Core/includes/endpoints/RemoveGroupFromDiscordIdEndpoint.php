<?php

/** 
 * @param int $discord_user_id The NamelessMC user's Discord user ID to edit
 * @param int $discord_role_id The Discord role ID to verify exists on the site and to remove
 * 
 * @return string JSON Array
 */
class RemoveGroupFromDiscordIdEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'removeGroupFromDiscordId';
        $this->_module = 'Core';
        $this->_description = 'Remove a user\'s role from NamelessMC when it was removed from Discord. Sets group to post validation group';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['discord_user_id', 'discord_role_id'])) {

                if (!Util::getSetting($api->getDb(), 'discord_integration')) $api->throwError(33, $api->getLanguage()->get('api', 'discord_integration_disabled'));

                $discord_user_id = Output::getClean($_POST['discord_user_id']);
                $discord_role_id = Output::getClean($_POST['discord_role_id']);

                // Get the user's NamelessMC id
                $user = $api->getDb()->get('users', array('discord_id', '=', $discord_user_id));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $user = $user->first()->id;

                // Ensure the group exists
                $group = $api->getDb()->get('groups', array('discord_role_id', '=', $discord_role_id));
                if (!$group->count()) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));

                try {
                    $api->getDb()->update('users', $user, array('group_id' => VALIDATED_DEFAULT));
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }

                Log::getInstance()->log(Log::Action('discord/role_remove'), 'Role removed: ' . $group->first()->name, $user);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}