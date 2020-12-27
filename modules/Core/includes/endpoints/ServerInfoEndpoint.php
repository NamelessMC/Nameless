<?php

class ServerInfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'serverInfo';
        $this->_module = 'Core';
        $this->_description = 'Update the Minecraft server information NamelessMC tracks';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['server-id', 'max-memory', 'free-memory', 'allocated-memory', 'tps', 'groups']);
        if (!isset($_POST['players'])) {
            $this->throwError(6, $this->_language->get('api', 'invalid_post_contents'), 'players');
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
                    'extra' => $_POST,
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
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'));
        }

        // Update usernames
        try {
            if (Util::getSetting($api->getDb(), 'username_sync')) {
                if (count($_POST['players'])) {
                    foreach ($_POST['players'] as $uuid => $player) {
                        $user = new User($uuid, 'uuid');
                        if (count($user->data())) {
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
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'));
        }

        // Group sync
        try {
            $group_sync = $api->getDb()->get('group_sync', array('id', '<>', 0));

            if ($group_sync->count()) {
                $group_sync = $group_sync->results();
                $group_sync_updates = array();
                foreach ($group_sync as $item) {
                    if ($item->ingame_rank_name == '') {
                        continue;
                    }

                    $group_sync_updates[strtolower($item->ingame_rank_name)] = array(
                        'website' => $item->website_group_id
                    );
                }

                if (count($_POST['players'])) {
                    foreach ($_POST['players'] as $uuid => $player) {
                        $user = new User($uuid, 'uuid');
                        if (count($user->data())) {
                            // Any synced groups to remove?
                            foreach ($user->getGroups() as $group) {
                                $group_name = strtolower($group->name);
                                if (array_key_exists($group_name, $group_sync_updates) && in_array($group_name, $player['groups'])) {
                                    $user->removeGroup($group->id);
                                }
                            }

                            // Any synced groups to add?
                            foreach ($player['groups'] as $group) {
                                $group_name = strtolower($group);
                                if (array_key_exists($group_name, $group_sync_updates)) {
                                    $group_info = $group_sync_updates[$group_name];

                                    $user->addGroup($group_sync_updates[$group_info]['website']);
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $api->throwError(25, $api->getLanguage()->get('api', 'unable_to_update_server_info'));
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'server_info_updated')));
    }
}
