<?php

/**
 * @param int $user NamelessMC ID of user to add groups to
 * @param array $groups ID of group ids
 *
 * @return string JSON Array
 */
class AddGroupsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/groups/add';
        $this->_module = 'Core';
        $this->_description = 'Add groups to user';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api, User $user): void {
        $groups = $_POST['groups'];
        if ($groups === null || !count($groups)) {
            $api->throwError(CoreApiErrors::ERROR_UNABLE_TO_FIND_GROUP, 'No groups provided');
        }

        $log_array = [];
        foreach ($groups as $group_id) {
            $group_query = $api->getDb()->get('groups', ['id', $group_id]);
            if (!$group_query->count()) {
                continue;
            }
            $group_query = $group_query->first();

            if ($user->addGroup($group_id)) {
                $log_array['added'][] = $group_query->name;
            }
        }

        GroupSyncManager::getInstance()->broadcastChange(
            $user,
            NamelessMCGroupSyncInjector::class,
            $user->getAllGroupIds(),
        );

        $api->returnArray(array_merge(['message' => $api->getLanguage()->get('api', 'group_updated')], $log_array));
    }
}
