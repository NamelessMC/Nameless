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

				$user = new User($discord_user_id, 'discord_id');
                if (!count($user->data())) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));

                $group = Discord::getWebsiteGroup($api->getDb(), $discord_role_id);
                if ($group == null) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));

                try {
                    $user->addGroup($group['group']->id);
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }

                Log::getInstance()->log(Log::Action('discord/role_add'), 'Role changed to: ' . $group['group']->name, $user->id);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}