<?php

/** 
 * @param int $user The NamelessMC user ID to edit
 * @param string $roles An array of Discord Role ID to remove from the user
 * 
 * @return string JSON Array
 */
class RemoveDiscordRolesEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'removeDiscordRoles';
        $this->_module = 'Core';
        $this->_description = 'Removes the specified groups according to their Discord Role ID from the user if they have it.';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['user', 'roles'])) {
                if (!Util::getSetting($api->getDb(), 'discord_integration')) $api->throwError(34, $api->getLanguage()->get('api', 'discord_integration_disabled'));

                $user_id = $_POST['user'];
                $roles = $_POST['roles'];

                $user = $api->getUser('id', $user_id);
                
                $roles_array = json_decode($roles);

                $message = '';

                foreach ($roles_array as $role_id) {
                    $group = Discord::getWebsiteGroup(DB::getInstance(), $role_id);
                    if ($group != null) {
                        if (in_array($group['group']->id, $user->data()->_groups)) {
                            try {
                                $user->removeGroup($group['group']->id);
                                $message .= $group['group']->name . ', ';
                            } catch (Exception $e) {
                                $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                            }
                        }
                    }
                }

                Log::getInstance()->log(Log::Action('discord/role_remove'), 'Roles removed: ' . rtrim($message, ', '), $user->data()->id);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}