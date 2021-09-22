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
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['user']);

        if (!Util::getSetting($api->getDb(), 'discord_integration')) {
            $api->throwError(34, $api->getLanguage()->get('api', 'discord_integration_disabled'));
        }

        $user = $api->getUser('id', $_POST['user']);

        $log_array = array();
        $roles = isset($_POST['roles']) ? $_POST['roles'] : array();

        $groups = DB::getInstance()->query('SELECT nl2_group_sync.*, nl2_groups.name FROM nl2_group_sync INNER JOIN nl2_groups ON website_group_id = nl2_groups.id WHERE discord_role_id IS NOT NULL')->results();
        foreach ($groups as $group) {
            if (in_array($group->discord_role_id, $roles)) {
                // Add group if user don't have it
                if ($user->addGroup($group->website_group_id, 0, array(true))) {
                    $log_array['added'][] = $group->name;
                }
            } else {
                // Check if user have another group synced to this NamelessMC group
                foreach ($groups as $item) {
                    if (in_array($item->discord_role_id, $roles)) {
                        if ($item->website_group_id == $group->website_group_id) {
                            continue 2;
                        }
                    }
                }

                // Remove group if user have it
                if ($user->removeGroup($group->website_group_id)) {
                    $log_array['removed'][] = $group->name;
                }
            }
        }

        if (count($log_array)) {
            Log::getInstance()->log(Log::Action('discord/role_set'), json_encode($log_array), $user->data()->id);
        }

        $api->returnArray(array_merge(array('message' => $api->getLanguage()->get('api', 'group_updated')), $log_array));
    }
}
