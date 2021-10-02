<?php

class ServerInfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'serverInfo';
        $this->_module = 'Core';
        $this->_description = 'Update the Minecraft server information NamelessMC tracks';
        $this->_method = 'POST';
    }

    private $user_cache = [];

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['server-id', 'max-memory', 'free-memory', 'allocated-memory', 'tps']);
        if (!isset($_POST['players'])) {
            $api->throwError(6, $this->_language->get('api', 'invalid_post_contents'), 'players');
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

        // Update usernames
        try {
            if (Util::getSetting($api->getDb(), 'username_sync')) {
                if (count($_POST['players'])) {
                    foreach ($_POST['players'] as $uuid => $player) {
                        $user = $this->getUser($uuid);
                        if ($user->data()) {
                            if ($player['name'] != $user->data()->username) {
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
                    }
                }
            }
        } catch (Exception $e) {
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'), $e->getMessage());
        }

        // Group sync
        $log = [];
        try {
            foreach ($_POST['players'] as $uuid => $player) {
                $user = $this->getUser($uuid);
                if ($user->exists()) {

                    $log = GroupSyncManager::getInstance()->broadcastChange(
                        $user, 
                        MinecraftGroupSyncInjector::class,
                        isset($player['groups']) ? array_map('strtolower', $player['groups']) : []
                    );

                    if (count($log)) {
                        Log::getInstance()->log(Log::Action('mc_group_sync/role_set'), json_encode($log), $user->data()->id);
                    }
                }
            }
        } catch (Exception $e) {
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'), $e->getMessage());
        }

        // Placeholder api
        try {
            foreach ($_POST['players'] as $uuid => $player) {
                $user = $this->getUser($uuid);
                if ($user->data()) {
                    $user->savePlaceholders($_POST['server-id'], $player['placeholders']);
                }
            }
        } catch (Exception $e) {
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'), $e->getMessage());
        }

        $api->returnArray(array_merge(array('message' => $api->getLanguage()->get('api', 'server_info_updated')), ['log' => $log]));
    }

    /**
     * Get a user from cache (or create if not exist).
     * 
     * @param string $uuid Their uuid.
     * @return User Their user instance.
     */
    private function getUser($uuid) {
        if (isset($this->user_cache[$uuid])) {
            return $this->user_cache[$uuid];
        }

        $user = new User($uuid, 'uuid');

        $this->user_cache[$uuid] = $user;

        return $user;
    }
}
