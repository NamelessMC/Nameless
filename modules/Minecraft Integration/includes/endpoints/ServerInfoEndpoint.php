<?php

class ServerInfoEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'minecraft/server-info';
        $this->_module = 'Core';
        $this->_description = 'Update the Minecraft server information NamelessMC tracks';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['server-id', 'max-memory', 'free-memory', 'allocated-memory']);
        if (!isset($_POST['players'])) {
            $api->throwError(Nameless2API::ERROR_INVALID_POST_CONTENTS, 'players');
        }

        $serverId = $_POST['server-id'];
        // Ensure server exists
        $server_query = $api->getDb()->get('mc_servers', ['id', $serverId]);

        if (!$server_query->count() || $server_query->first()->bedrock) {
            $api->throwError(CoreApiErrors::ERROR_INVALID_SERVER_ID, $serverId);
        }

        if (isset($_POST['verify_command'])) {
            Util::setSetting('minecraft_verify_command', $_POST['verify_command']);
        }

        try {
            $api->getDb()->insert('query_results', [
                'server_id' => $_POST['server-id'],
                'queried_at' => date('U'),
                'players_online' => count($_POST['players']),
                'groups' => isset($_POST['groups']) ? json_encode($_POST['groups']) : '[]'
            ]);

            if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache')) {
                $query_cache = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache');
                $query_cache = json_decode($query_cache);
                if (isset($query_cache->query_interval)) {
                    $query_interval = unserialize($query_cache->query_interval->data);
                } else {
                    $query_interval = 10;
                }

                $to_cache = [
                    'query_interval' => [
                        'time' => date('U'),
                        'expire' => 0,
                        'data' => serialize($query_interval)
                    ],
                    'last_query' => [
                        'time' => date('U'),
                        'expire' => 0,
                        'data' => serialize(date('U'))
                    ]
                ];

                // Store in cache file
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache', json_encode($to_cache));
            }
        } catch (Exception $e) {
            $api->throwError(CoreApiErrors::ERROR_UNABLE_TO_UPDATE_SERVER_INFO, $e->getMessage(), 500);
        }

        $group_sync_log = [];
        $should_group_sync = $serverId == Util::getSetting('group_sync_mc_server');

        try {
            $integration = Integrations::getInstance()->getIntegration('Minecraft');

            foreach ($_POST['players'] as $uuid => $player) {
                $integrationUser = new IntegrationUser($integration, $uuid, 'identifier');
                if ($integrationUser->exists()) {
                    $this->updateUsername($integrationUser, $player);

                    if ($should_group_sync) {
                        $log = $this->updateGroups($integrationUser, $player);
                        if (count($log)) {
                            $group_sync_log[] = $log;
                        }
                    }

                    if (isset($player['placeholders']) && count($player['placeholders'])) {
                        $this->updatePlaceholders($integrationUser->getUser(), $player);
                    }
                }
            }
        } catch (Exception $e) {
            $api->throwError(CoreApiErrors::ERROR_UNABLE_TO_UPDATE_SERVER_INFO, $e->getMessage(), 500);
        }

        $api->returnArray(array_merge(['message' => $api->getLanguage()->get('api', 'server_info_updated')], ['log' => $group_sync_log]));
    }

    private function updateUsername(IntegrationUser $integrationUser, array $player): void {
        if ($player['name'] != $integrationUser->data()->username) {
            $integrationUser->update([
                'username' => Output::getClean($player['name'])
            ]);
        }

        if (Util::getSetting('username_sync')) {
            $user = $integrationUser->getUser();
            if (!$user->exists() || $player['name'] == $user->data()->username) {
                return;
            }

            // Update username
            if (Util::getSetting('displaynames') === '1') {
                $user->update([
                    'username' => $player['name']
                ]);
            } else {
                $user->update([
                    'username' => $player['name'],
                    'nickname' => $player['name']
                ]);
            }
        }
    }

    private function updateGroups(IntegrationUser $integrationUser, array $player): array {
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
            $player['groups'] ?? []
        );

        if (count($log)) {
            Log::getInstance()->log(Log::Action('mc_group_sync/role_set'), json_encode($log), $user->data()->id);
        }

        return $log;
    }

    private function updatePlaceholders(User $user, $player): void {
        if ($user->exists()) {
            $user->savePlaceholders($_POST['server-id'], $player['placeholders']);
        }
    }

}
