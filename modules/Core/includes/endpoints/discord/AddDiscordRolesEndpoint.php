<?php

/** 
 * @param int $user The NamelessMC user ID to edit
 * @param string $roles An array of Discord Role ID to add to the user
 * 
 * @return string JSON Array
 */
class AddDiscordRolesEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'addDiscordRoles';
        $this->_module = 'Core';
        $this->_description = 'Adds the specified groups according to their Discord Role ID from the user if they don\'t have it already.';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['user', 'roles'])) {
                if (!Util::getSetting($api->getDb(), 'discord_integration')) $api->throwError(34, $api->getLanguage()->get('api', 'discord_integration_disabled'));

                $user_id = $_POST['user'];
                $roles = $_POST['roles'];

                $user = $api->getUser('id', $user_id);
                
                $message = '';

                foreach ($roles as $role_id) {
                    $group = Discord::getWebsiteGroup(DB::getInstance(), $role_id);
                    if ($group != null) {
                        if (!in_array($group['group']->id, $user->data()->_groups)) {
                            try {
                                $user->addGroup($group['group']->id);
                                $message .= $group['group']->name . ', ';
                            } catch (Exception $e) {
                                $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                            }
                        }
                    }
                }

                Log::getInstance()->log(Log::Action('discord/role_remove'), 'Roles added: ' . rtrim($message, ', '), $user->data()->id);
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}