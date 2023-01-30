<?php

class PluginQuery {

    /**
     * Query a server by its server id
     *
     * @param int $server_id The Nameless server id to get the data for.
     * @param Language $language Query language object.
     * @return array Array containing query result.
     */
    public static function singleQuery(int $server_id, Language $language): array {
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
        return [
            'status_value' => 1,
            'status' => $language->get('general', 'online'),
            'player_count' => $data['player_count'],
            'player_count_max' => $data['player_count_max'],
            'player_list' => $data['player_list'],
            'format_player_list' => MCQuery::formatPlayerList($data['player_list']),
            'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query['players']['online'])]),
            'motd' => '',
            'version' => ''
        ];
    }
}