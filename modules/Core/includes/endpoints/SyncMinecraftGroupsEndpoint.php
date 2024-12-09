<?php

class SyncMinecraftGroupsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'minecraft/{user}/sync-groups';
        $this->_module = 'Core';
        $this->_description = 'Update a users groups based on added or removed groups from the Minecraft server';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api, User $user): void {
        $api->validateParams($_POST, ['server_id']);

        $server_id = $_POST['server_id'];
        $integration = Integrations::getInstance()->getIntegration('Minecraft');

        if (!$integration || $server_id != Settings::get('group_sync_mc_server')) {
            $api->returnArray(['message' => $api->getLanguage()->get('api', 'groups_updates_ignored')]);
        }

        $log = GroupSyncManager::getInstance()->broadcastGroupChange(
            $user,
            MinecraftGroupSyncInjector::class,
            $_POST['add'] ?? [],
            $_POST['remove'] ?? [],
        );

        Log::getInstance()->log(Log::Action('mc_group_sync/role_set'), json_encode($log), $user->data()->id);

        $api->returnArray([
            'message' => $api->getLanguage()->get('api', 'groups_updates_successfully'),
            'log' => $log,
        ]);
    }
}
