<?php

class ServerInfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'serverInfo';
        $this->_module = 'Core';
        $this->_description = 'Update the Minecraft server information NamelessMC tracks';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['server-id', 'max-memory', 'free-memory', 'allocated-memory', 'tps']);
        if (!isset($_POST['players'])) {
            $api->throwError(6, $api->getLanguage()->get('api', 'invalid_post_contents'), 'players');
        }

        $serverId = $_POST['server-id'];
        // Ensure server exists
        $server_query = $api->getDb()->get('mc_servers', array('id', '=', $serverId));

        if (!$server_query->count()) {
            $api->throwError(27, $api->getLanguage()->get('api', 'invalid_server_id') . ' - ' . $serverId);
        }

        try {
            $api->getDb()->insert(
                'query_results',
                array(
                    'server_id' => $_POST['server-id'],
                    'queried_at' => date('U'),
                    'players_online' => count($_POST['players']),
                    'groups' => isset($_POST['groups']) ? json_encode($_POST['groups']) : '[]'
                )
            );

            if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache')) {
                $query_cache = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache');
                $query_cache = json_decode($query_cache);
                if (isset($query_cache->query_interval))
                    $query_interval = unserialize($query_cache->query_interval->data);
                else
                    $query_interval = 10;

                $to_cache = array(
                    'query_interval' => array(
                        'time' => date('U'),
                        'expire' => 0,
                        'data' => serialize($query_interval)
                    ),
                    'last_query' => array(
                        'time' => date('U'),
                        'expire' => 0,
                        'data' => serialize(date('U'))
                    )
                );

                // Store in cache file
                file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('server_query_cache') . '.cache', json_encode($to_cache));
            }
        } catch (Exception $e) {
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'), $e->getMessage());
        }

        $group_sync_log = [];

        try {
            foreach ($_POST['players'] as $uuid => $player) {
                $user = new User($uuid, 'uuid');
                updateUsername($user, $player, $api);
                $log = updateGroups($user, $player);
                if ($log != null) {
                    $group_sync_log[] = $log;
                }
                updatePlaceholders($user, $player);
            }
        } catch (Exception $e) {
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'), $e->getMessage());
        }

        $api->returnArray(array_merge(array('message' => $api->getLanguage()->get('api', 'server_info_updated')), ['log' => $group_sync_log]));
    }

    private function updateUsername(User $user, $player, Nameless2API $api) {
        if (Util::getSetting($api->getDb(), 'username_sync')) {
            if (!$user->data() ||
                    $player['name'] == $user->data()->username) {
                return;
            }

            // Update username
            if (!Util::getSetting($api->getDb(), 'displaynames', false)) {
                $user->update(
                    array(
                        'username' => Output::getClean($player['name']),
                        'nickname' => Output::getClean($player['name'])
                    ),
                    $user->data()->id
                );
            } else {
                $user->update(
                    array(
                        'username' => Output::getClean($player['name'])
                    ),
                    $user->data()->id
                );
            }
        }
    }

    private function updateGroups(User $user, $player): ?string {
        if (!$user->exists()) {
            return null;
        }

        $log = GroupSyncManager::getInstance()->broadcastChange(
            $user,
            MinecraftGroupSyncInjector::class,
            isset($player['groups']) ? array_map('strtolower', $player['groups']) : []
        );

        if (count($log)) {
            Log::getInstance()->log(Log::Action('mc_group_sync/role_set'), json_encode($log), $user->data()->id);
        }

        return $log;
    }

    private function updatePlaceholders(User $user, $player) {
        if ($user->data()) {
            $user->savePlaceholders($_POST['server-id'], $player['placeholders']);
        }
    }

}
