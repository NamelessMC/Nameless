<?php

/**
 * @param int $user The NamelessMC user ID to edit
 * @param string $roles An array of Discord Role ID to give to the user
 * 
 * @return string JSON Array
 */
class SetDiscordRolesEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'setDiscordRoles';
        $this->_module = 'Core';
        $this->_description = 'Set a NamelessMC user\'s according to the supplied Discord Role ID list';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['user'])) {
                if (!Util::getSetting($api->getDb(), 'discord_integration')) $api->throwError(34, $api->getLanguage()->get('api', 'discord_integration_disabled'));

                $user_id = $_POST['user'];

                $user = $api->getUser('id', $user_id);

                if ($_POST['roles'] != null) {

                    $roles = $_POST['roles'];

                    $user->removeGroups();

                    $message = '';

                    foreach ($roles as $role) {
                        $group = Discord::getWebsiteGroup($api->getDb(), $role);
                        if ($group != null) {
                            try {
                                $user->addGroup($group->id);
                                $message .= $group->name . ', ';
                            } catch (Exception $e) {
                                $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                            }
                        }
                    }

                    if ($message != '') {
                        Log::getInstance()->log(Log::Action('discord/role_add'), 'Roles changed to: ' . rtrim($message, ', '), $user->data()->id);
                    }

                } else {

                    foreach ($user->getAllGroupIds() as $group_id) {
                        $user->removeGroup($group_id);
                    }

                }

                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}