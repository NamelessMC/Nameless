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

        $user_id = $_POST['user'];

        $user = $api->getUser('id', $user_id);

        $should_log = false;
        $log_array = array();

        if ($_POST['roles'] != null) {

            $roles = $_POST['roles'];

            $original_group_ids = $user->getAllGroupIds();
            $added_groups_ids = array();

            $user->removeGroups();

            foreach ($roles as $role) {
                $group = Discord::getWebsiteGroup($api->getDb(), $role);
                if ($group == null) {
                    continue;
                }

                if ($user->addGroup($group->id)) {
                    // Don't log if we are just giving back a group
                    if (in_array($group->id, $original_group_ids)) {
                        continue;
                    }

                    $should_log = true;
                    $log_array['added'][] = Util::getGroupNameFromId($group->name);
                    $added_groups_ids[] = $group->id;
                }
            }

            foreach ($original_group_ids as $group_id) {
                // If this original group was added back, ignore it
                if (in_array($group_id, $added_groups_ids)) {
                    continue;
                }

                $log_array['removed'][] = Util::getGroupNameFromId($group_id);
            }

        } else {

            $original_group_ids = $user->getAllGroupIds();
            $added_group_id = 0;

            if ($user->isValidated()) {
                $user->setGroup(VALIDATED_DEFAULT);
                $added_group_id = VALIDATED_DEFAULT;
            } else {
                $user->setGroup(PRE_VALIDATED_DEFAULT);
                $added_group_id = PRE_VALIDATED_DEFAULT;
            }

            // If the new group they got was not in their original groups, log it
            if (!in_array($added_group_id, $original_group_ids)) {
                $should_log = true;
                $log_array['added'][] = Util::getGroupNameFromId($added_group_id);
            }

            // Log all removed groups, but dont count the added group as removed
            foreach ($original_group_ids as $group_id) {
                if ($group_id == $added_group_id) {
                    continue;
                }

                $should_log = true;
                $log_array['removed'][] = Util::getGroupNameFromId($group_id);
            }

        }

        if ($should_log) {
            Log::getInstance()->log(Log::Action('discord/role_set'), json_encode($log_array), $user->data()->id);
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated'), 'meta' => json_encode($log_array)));
    }
}
