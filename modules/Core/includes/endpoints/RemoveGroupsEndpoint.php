<?php

use GuzzleHttp\Exception\GuzzleException;

/**
 * TODO: Add description
 *
 * @package Modules\Core\Endpoints
 * @author UNKNOWN
 * @version UNKNOWN
 * @license MIT
 */
class RemoveGroupsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/groups/remove';
        $this->_module = 'Core';
        $this->_description = 'Remove groups from user';
        $this->_method = 'POST';
    }

    /**
     * @param Nameless2API $api
     * @param User $user
     *
     * @return void
     * @throws GuzzleException
     */
    public function execute(Nameless2API $api, User $user): void {
        $api->validateParams($_POST, ['groups']);

        $groups = $_POST['groups'];
        if (!count($groups)) {
            $api->throwError(Nameless2API::ERROR_INVALID_POST_CONTENTS);
        }

        foreach ($groups as $group) {
            $user->removeGroup($group);
        }

        GroupSyncManager::getInstance()->broadcastChange(
            $user,
            NamelessMCGroupSyncInjector::class,
            $user->getAllGroupIds(),
        );

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'group_updated')]);
    }
}
