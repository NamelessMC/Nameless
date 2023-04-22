<?php
/**
 * Queries Minecraft servers using the NamelessMC plugin.
 *
 * @package NamelessMC\Minecraft
 * @author Supercrafter100
 * @version 2.0.1
 * @license MIT
 */
class PluginQuery {

    /**
     * Query a server by its server id
     *
     * @param int $server_id The Nameless server id to get the data for.
     * @param Language $language Query language object.
     * @return array Array containing query result.
     */
    public static function singleQuery(int $server_id, Language $language): array {

        $player_list_limit = Util::getSetting('player_list_limit', 20);

        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('latest_query');

        if (!$cache->isCached($server_id)) {
            return [
                'status_value' => 0,
                'status' => $language->get('general', 'offline'),
                'server_offline' => $language->get('general', 'server_offline')
            ];
        }

        $data = $cache->retrieve($server_id);
        $player_list = array_slice($data['player_list'], 0, intval($player_list_limit));

        return [
            'status_value' => 1,
            'status' => $language->get('general', 'online'),
            'player_count' => $data['player_count'],
            'player_count_max' => $data['player_count_max'],
            'player_list' => $player_list,
            'format_player_list' => MCQuery::formatPlayerList($player_list),
            'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => $data['player_count']]),
            'motd' => $data['motd'] ?? '',
            'version' => ''
        ];
    }

    /**
     * Query multiple servers
     *
     * @param array $servers Servers
     * @param Language $language Query language object
     * @param bool $accumulate Whether to return as one accumulated result or not
     * @return array Array containing query result
     */
    public static function multiQuery(array $servers, Language $language, bool $accumulate) : array {
        $to_return = [];
        $total_count = 0;
        $status = 0;
        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('latest_query');

        foreach ($servers as $server) {
            $server_id = $server->id;
            $data = $cache->retrieve($server_id);
            if (!$cache->isCached($server_id) && $accumulate === true) {
                $to_return[] = [
                    'name' => Output::getClean($data['name']),
                    'status_value' => 0,
                    'status' => $language->get('general', 'offline'),
                    'server_offline' => $language->get('general', 'server_offline')
                ];
            } else {
                // Server is online
                if ($accumulate === false) {
                    $to_return[] = [
                        'name' => Output::getClean($server['name']),
                        'status_value' => 1,
                        'status' => $language->get('general', 'online'),
                        'player_count' => $data['player_count'],
                        'player_count_max' => $data['player_count_max'],
                        'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => $data['player_count']]),
                    ];
                } else {
                    if ($status == 0) {
                        $status = 1;
                    }
                    $total_count += $data['player_count'];
                }
            }
        }

        if ($accumulate === true) {
            $to_return = [
                'status_value' => $status,
                'status' => $status == 1
                    ? $language->get('general', 'online')
                    : $language->get('general', 'offline'),
                'status_full' => $status == 1
                    ? $language->get('general', 'currently_x_players_online', ['count' => $total_count])
                    : $language->get('general', 'server_offline'),
                'player_count' => $total_count,
            ];
        }

        return $to_return;
    }
}