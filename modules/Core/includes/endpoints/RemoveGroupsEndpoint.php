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
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['user', 'groups']);

        // Ensure user exists
        $user = $api->getUser('id', $_POST['user']);

        $groups = $_POST['groups'];
        if (!count($groups)) {
            $api->throwError(6, $api->getLanguage()->get('api', 'invalid_post_contents'));
        }

        foreach ($groups as $group) {
            $user->removeGroup($group);

            // Attempt to update their discord role as well, but ignore any output/errors
            Discord::updateDiscordRoles($user, [], [$group], $api->getLanguage(), false);
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
    }
}
