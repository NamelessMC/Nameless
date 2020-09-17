<?php

/**
 * Set a NamelessMC user's primary group from a provided Discord role ID
 * 
 * @param int $discord_user_id The Discord ID of the NamelessMC user to edit
 * @param int $discord_id The Discord ID fo the NamelessMC group to apply as their primary group
 * 
 * @return string JSON Array
 */
class SetGroupFromDiscordEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'setGroupFromDiscord';
        $this->_module = 'Core';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['discord_user_id', 'discord_role_id'])) {
                if (!$api->getDb()->get('settings', array('name', '=', 'discord_integration'))->first()->value) {
                    $api->throwError(33, $api->getLanguage()->get('api', 'discord_integration_disabled'));
                }

                $discord_user_id = $_POST['discord_user_id'];
                $discord_role_id = $_POST['discord_role_id'];

                $user = $api->getDb()->get('users', array('discord_id', '=', $discord_user_id));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $user = $user->first();

                $group = $api->getDb()->get('groups', array('discord_role_id', '=', $discord_role_id));
                if (!$group->count()) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));
                $group = $group->first();

                // Set their secondary groups to all of their old secondary groups, except the new group - just incase
                $new_secondary_groups = array();
                foreach ($user->secondary_groups as $secondary_group) {
                    if ($group != $secondary_group) {
                        $new_secondary_groups[] = $secondary_group;
                    }
                }

                try {
                    $api->getDb()->update('users', $user->id, array(
                        'group_id' => $group->id
                    ));
                    $api->getDb()->update('users', $user->id, array(
                        'secondary_groups' => json_encode($new_secondary_groups)
                    ));
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }
                Log::getInstance()->log(Log::Action('discord/role_add'), 'Role changed to: ' . $group->name, $user->id);
                // Success
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}