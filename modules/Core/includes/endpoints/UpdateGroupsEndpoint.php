<?php

class UpdateGroupsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'minecraft/update-groups';
        $this->_module = 'Core';
        $this->_description = 'Update a users groups based on their groups from the Minecraft server';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['server_id', 'player_groups']);

        $serverId = $_POST['server_id'];
        $group_sync_log = [];

        if ($serverId == Util::getSetting('group_sync_mc_server')) {
            try {
                $integration = Integrations::getInstance()->getIntegration('Minecraft');

                foreach ($_POST['player_groups'] as $uuid => $groups) {
                    $integrationUser = new IntegrationUser($integration, str_replace('-', '', $uuid), 'identifier');
                    if ($integrationUser->exists()) {
                        $log = $this->updateGroups($integrationUser, $groups['groups']);
                        if (count($log)) {
                            $group_sync_log[] = $log;
                        }
                    }
                }
            } catch (Exception $e) {
                $api->throwError(CoreApiErrors::ERROR_UNABLE_TO_UPDATE_GROUPS, $e->getMessage(), 500);
            }

            $api->returnArray(array_merge(['message' => $api->getLanguage()->get('api', 'server_info_updated')], ['log' => $group_sync_log]));
        }

        $api->throwError(CoreApiErrors::ERROR_INVALID_SERVER_ID, $serverId);
    }

    private function updateGroups(IntegrationUser $integrationUser, array $groups): array {
        if (!$integrationUser->isVerified()) {
            return [];
        }

        $user = $integrationUser->getUser();
        if (!$user->exists()) {
            return [];
        }

        if (!$user->isValidated()) {
            return [];
        }

        $log = GroupSyncManager::getInstance()->broadcastChange(
            $user,
            MinecraftGroupSyncInjector::class,
            $groups,
        );

        if (count($log)) {
            Log::getInstance()->log(Log::Action('mc_group_sync/role_set'), json_encode($log), $user->data()->id);
        }

        return $log;
    }
}
