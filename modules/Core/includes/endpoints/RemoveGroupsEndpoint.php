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
        $user = new User($_POST['user']);
        if (!count($user->data())) {
            $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
        }

        $groups = $_POST['groups'];
        if (!count($groups)) {
            $api->throwError(6, $api->getLanguage()->get('api', 'invalid_post_contents'));
        }

        foreach ($groups as $group) {
            $user->removeGroup($group);

            // Attempt to update their discord role as well, but ignore any output/errors
            Discord::removeDiscordRole($user, $group, $api->getLanguage());
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
    }
}
