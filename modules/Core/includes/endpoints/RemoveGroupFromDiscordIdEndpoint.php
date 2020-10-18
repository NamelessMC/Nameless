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
        $this->_description = 'Remove a user\'s role from NamelessMC when it was removed from Discord. If the group is a primary group, it sets their group to post validation group';
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
                $user = $user->first();

                $group = Discord::getWebsiteGroup(DB::getInstance(), $discord_role_id);
                if ($group == null) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));

                $fields = array();

                if ($group['primary']) {
                    // If role was primary, set as default post validated
                    $fields['group_id'] = VALIDATED_DEFAULT;
                } else {
                    // if role was secondary, only remove from secondary groups
                    $new_secondary_groups = array();
                    foreach ($user->secondary_groups as $secondary_group) {
                        if ($group['group']->id != $secondary_group) {
                            $new_secondary_groups[] = $secondary_group;
                        }
                    }
                    $fields['secondary_groups'] = json_encode($new_secondary_groups);
                }

                try {
                    $api->getDb()->update('users', $user->id, $fields);
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }

                Log::getInstance()->log(Log::Action('discord/role_remove'), 'Role removed: ' . $group['group']->name, $user->id);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}