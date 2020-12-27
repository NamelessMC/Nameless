<?php

/**
 * @param int $user NamelessMC ID of user to view
 * @param json array $groups ID of group ids
 *
 * @return string JSON Array
 */
class RemoveGroupsEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'removeGroups';
        $this->_module = 'Core';
        $this->_description = 'Remove groups from user';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['user', 'groups']);

        // Ensure user exists
        $user = new User(htmlspecialchars($_POST['user']));
        if (!count($user->data())) {
            $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
        }

        $groups = json_decode($_POST['groups'], true);
        if ($groups == null || !count($groups)) {
            $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));
        }

        foreach ($groups as $group) {
            $user->removeGroup($group);

            // Attempt to update their discord role as well, but ignore any output/errors
            Discord::removeDiscordRole($user, $group, $api->getLanguage());
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
    }
}
