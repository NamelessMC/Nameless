<?php

/**
 * @param int $discord_user_id The Discord ID of the NamelessMC user to edit
 * @param int $discord_id The Discord ID fo the NamelessMC group to apply as their primary group
 * 
 * @return string JSON Array
 */
class SetGroupFromDiscordIdEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'setGroupFromDiscordId';
        $this->_module = 'Core';
        $this->_description = 'Set a NamelessMC user\'s primary group from a provided Discord role ID';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['discord_user_id', 'discord_role_id'])) {
                if (!Util::getSetting($api->getDb(), 'discord_integration')) $api->throwError(33, $api->getLanguage()->get('api', 'discord_integration_disabled'));

                $discord_user_id = $_POST['discord_user_id'];
                $discord_role_id = $_POST['discord_role_id'];

                $user = $api->getDb()->get('users', array('discord_id', '=', $discord_user_id));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $user = $user->first();

                $group = Discord::getWebsiteGroup($api->getDb(), $discord_role_id);
                if ($group == null) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));

                $fields = array();

                $new_secondary_groups = array();

                if ($group['primary']) {
                    // If the group is supposed to be a primary group, set as their group_id and remove from secondary groups
                    $fields['group_id'] = $group['group']->id;
                    foreach ($user->secondary_groups as $secondary_group) {
                        if ($group['group']->id != $secondary_group) {
                            $new_secondary_groups[] = $secondary_group;
                        }
                    }
                } else {
                    // If its a secondary group, dont change their group_id, just add it to their secondary groups.
                    $new_secondary_groups[] = $group['group']->id;
                    foreach ($user->secondary_groups as $secondary_group) {
                        $new_secondary_groups[] = $secondary_group;
                    }
                }

                $fields['secondary_groups'] = json_encode($new_secondary_groups);

                try {
                    $api->getDb()->update('users', $user->id, $fields);
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }

                Log::getInstance()->log(Log::Action('discord/role_add'), 'Role changed to: ' . $group['group']->name, $user->id);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}