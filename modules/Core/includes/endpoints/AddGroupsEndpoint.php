<?php

/**
 * @param int $user NamelessMC ID of user to view
 * @param string array $groups ID of group ids
 *
 * @return string JSON Array
 */
class AddGroupsEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'addGroups';
        $this->_module = 'Core';
        $this->_description = 'Add groups to user';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['user', 'groups']);

        // Ensure user exists
        $user = $api->getUser('id', $_POST['user']);

        $groups = json_decode($_POST['groups'], true);
        if ($groups == null || !count($groups)) {
            $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));
        }

        foreach ($groups as $group) {
            $group_query = $api->getDb()->get('groups', array('id', '=', $group));
            if (!$group_query->count()) {
                continue;
            }

            $user->addGroup($group);

            // Attempt to update their discord role as well, but ignore any output/errors
            Discord::addDiscordRole($user, $group, $api->getLanguage(), false);
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
    }
}
