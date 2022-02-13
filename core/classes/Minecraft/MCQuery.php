<?php

use xPaw\MinecraftPing;
use xPaw\MinecraftQuery;

/**
 * Minecraft server query class
 *
 * @package NamelessMC\Minecraft
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class MCQuery {

    /**
     * Query a single server
     *
     * @param array $ip Array ['ip' => string, 'pre' => int] - 'ip' contains ip:port, 'pre' 1 for pre-Minecraft 1.7 otherwise 0
     * @param string $type Type of query to use (`internal` or `external`).
     * @param bool $bedrock Whether this is a Bedrock server or not.
     * @param Language $language Query language object.
     * @param Queries $queries Queries instance to pass through for error logging.
     * @return array Array containing query result.
     */
    public static function singleQuery(array $ip, string $type, bool $bedrock, Language $language, Queries $queries): array {
        if ($ip == null) {
            throw new InvalidArgumentException("\$ip is null");
        }

        try {
            $query_ip = explode(':', $ip['ip']);
            if ($type == 'internal') {
                // Internal query
                if (count($query_ip) == 1 || (strlen($query_ip[1]) == 2 && empty($query_ip[1]))) {
                    $query_ip[1] = 25565;
                }

                if (count($query_ip) != 2) {
                    return [
                        'error' => true,
                        'value' => 'split IP by : must contain exactly two components'
                    ];
                }

                if (!$bedrock) {
                    $ping = new MinecraftPing($query_ip[0], $query_ip[1], 5);

                    if ($ip['pre'] == 1) {
                        $query = $ping->QueryOldPre17();
                    } else {
                        $query = $ping->Query();
                    }

                    $ping->close();

                    if (isset($query['players'])) {
                        $player_list = $query['players']['sample'] ?? [];

                        return [
                            'status_value' => 1,
                            'status' => $language->get('general', 'online'),
                            'player_count' => Output::getClean($query['players']['online']),
                            'player_count_max' => Output::getClean($query['players']['max']),
                            'player_list' => $player_list,
                            'format_player_list' => self::formatPlayerList($player_list),
                            'x_players_online' => str_replace('{x}', Output::getClean($query['players']['online']), $language->get('general', 'currently_x_players_online')),
                            'motd' => $query['description']['text'] ?? '',
                            'version' => $query['version']['name']
                        ];
                    }
                } else {
                    $querier = new MinecraftQuery();
                    $querier->ConnectBedrock($query_ip[0], $query_ip[1], 5);
                    $query = $querier->GetInfo();
                    return [
                        'status_value' => 1,
                        'status' => $language->get('general', 'online'),
                        'player_count' => Output::getClean($query['Players']),
                        'player_count_max' => Output::getClean($query['MaxPlayers']),
                        'x_players_online' => str_replace('{x}', Output::getClean($query['Players']), $language->get('general', 'currently_x_players_online')),
                        'motd' => $query['HostName'],
                        'version' => $query['Version']
                    ];
                }

                return [
                    'status_value' => 0,
                    'status' => $language->get('general', 'offline'),
                    'server_offline' => $language->get('general', 'server_offline')
                ];
            }

            // External query
            if (count($query_ip) > 2) {
                return [
                    'error' => true,
                    'value' => 'split IP by : contains more than two components'
                ];
            }

            $query = ExternalMCQuery::query($query_ip[0], ($query_ip[1] ?? ($bedrock ? 19132 : 25565)), $bedrock);

            if (!$query->error && isset($query->response)) {
                $player_list = $query->response->players->list ?? [];

                return [
                    'status_value' => 1,
                    'status' => $language->get('general', 'online'),
                    'player_count' => Output::getClean($query->response->players->online),
                    'player_count_max' => Output::getClean($query->response->players->max),
                    'player_list' => $player_list,
                    'format_player_list' => self::formatPlayerList($player_list),
                    'x_players_online' => str_replace('{x}', Output::getClean($query->response->players->online), $language->get('general', 'currently_x_players_online')),
                    'motd' => $query->response->description->text
                ];
            }

            return [
                'status_value' => 0,
                'status' => $language->get('general', 'offline'),
                'server_offline' => $language->get('general', 'server_offline')
            ];
        } catch (Exception $e) {
            $error = $e->getMessage();

            $query_ip = explode(':', $ip['ip']);

            $queries->create('query_errors', [
                    'date' => date('U'),
                    'error' => $error,
                    'ip' => $query_ip[0],
                    'port' => $query_ip[1] ?? 25565
            ]);

            return [
                'error' => true,
                'value' => $error
            ];
        }
    }

    /**
     * Formats a list of players into something useful for the frontend.
     *
     * @param array $player_list Unformatted array of players in format 'id' => string (UUID), 'name' => string (username)
     * @return array Array of formatted players
     **/
    private static function formatPlayerList(array $player_list): array {
        $formatted = [];

        foreach ($player_list as $player) {
            $player = (array)$player;
            $user = new User($player['id'], 'uuid');
            if (!$user->data()) {
                $user = new User($player['name'], 'username');
            }

            if (!$user->data()) {
                $avatar = Util::getAvatarFromUUID($player['id']);
                $profile = '#';
            } else {
                $avatar = $user->getAvatar();
                $profile = $user->getProfileURL();
            }

            $formatted[] = [
                'username' => Output::getClean($player['name']),
                'uuid' => Output::getClean($player['id']),
                'avatar' => $avatar,
                'profile' => $profile
            ];
        }

        return $formatted;
    }

    /**
     * Query multiple servers
     * @param array $servers Servers
     * @param string $type Type of query to use (internal or external)
     * @param Language $language Query language object
     * @param bool $accumulate Whether to return as one accumulated result or not
     * @param Queries $queries Queries instance to pass through for error logging
     *
     * @return array Array containing query result
     * @throws Exception if not able to query the server
     *
     */
    public static function multiQuery(array $servers, string $type, Language $language, bool $accumulate, Queries $queries): array {
        if (count($servers)) {
            $to_return = [];
            $total_count = 0;
            $status = 0;
            if ($type == 'internal') {
                // Internal query

                foreach ($servers as $server) {
                    $query_ip = explode(':', $server['ip']);
                    if (count($query_ip) <= 2) {
                        try {
                            if (!$server['bedrock']) {
                                $ping = new MinecraftPing($query_ip[0], ($query_ip[1] ?? 25565), 5);

                                if ($server['pre'] == 1) {
                                    $query = $ping->QueryOldPre17();
                                } else {
                                    $query = $ping->Query();
                                }
                            } else {
                                $ping = new MinecraftQuery();
                                $ping->ConnectBedrock($query_ip[0], ($query_ip[1] ?? 19132), 5);
                                $query = $ping->GetInfo();
                            }
                        } catch (Exception $e) {
                            $error = $e->getMessage();

                            $query = [];

                            $queries->create('query_errors', [
                                'date' => date('U'),
                                'error' => $error,
                                'ip' => $query_ip[0],
                                'port' => ($query_ip[1] ?? ($server['bedrock'] ? 19132 : 25565))
                            ]);
                        }

                        // bedrock
                        if (isset($query['HostName'])) {
                            if ($accumulate === false) {
                                $to_return[] = [
                                    'name' => Output::getClean($server['name']),
                                    'status_value' => 1,
                                    'status' => $language->get('general', 'online'),
                                    'player_count' => Output::getClean($query['Players']),
                                    'player_count_max' => Output::getClean($query['MaxPlayers']),
                                    'x_players_online' => str_replace('{x}', Output::getClean($query['Players']), $language->get('general', 'currently_x_players_online')),
                                ];
                            } else {
                                if ($status == 0) {
                                    $status = 1;
                                }
                                $total_count += $query['Players'];
                            }
                        } else if (isset($query['players'])) {
                            if ($accumulate === false) {
                                $to_return[] = [
                                    'name' => Output::getClean($server['name']),
                                    'status_value' => 1,
                                    'status' => $language->get('general', 'online'),
                                    'player_count' => Output::getClean($query['players']['online']),
                                    'player_count_max' => Output::getClean($query['players']['max']),
                                    'x_players_online' => str_replace('{x}', Output::getClean($query['players']['online']), $language->get('general', 'currently_x_players_online'))
                                ];
                            } else {
                                if ($status == 0) {
                                    $status = 1;
                                }
                                $total_count += $query['players']['online'];
                            }
                        } else if ($accumulate === true) {
                            $to_return[] = [
                                'name' => Output::getClean($server['name']),
                                'status_value' => 0,
                                'status' => $language->get('general', 'offline'),
                                'server_offline' => $language->get('general', 'server_offline')
                            ];
                        }
                    }
                }

                if (isset($ping) && $ping instanceof MinecraftPing) {
                    $ping->close();
                }

            } else {
                // External query
                foreach ($servers as $server) {
                    $query_ip = explode(':', $server['ip']);

                    if (count($query_ip) <= 2) {
                        $query = ExternalMCQuery::query($query_ip[0], ($query_ip[1] ?? ($server['bedrock'] ? 19132 : 25565)), $server['bedrock']);

                        if (!$query->error && isset($query->response)) {
                            if ($accumulate === false) {
                                $to_return[] = [
                                    'name' => Output::getClean($server['name']),
                                    'status_value' => 1,
                                    'status' => $language->get('general', 'online'),
                                    'player_count' => Output::getClean($query->response->players->online),
                                    'player_count_max' => Output::getClean($query->response->players->max),
                                    'x_players_online' => str_replace('{x}', Output::getClean($query->response->players->online), $language->get('general', 'currently_x_players_online'))
                                ];
                            } else {
                                if ($status == 0) {
                                    $status = 1;
                                }
                                $total_count += $query->response->players->online;
                            }
                        } else if ($accumulate === true) {
                            $to_return[] = [
                                'name' => Output::getClean($server['name']),
                                'status_value' => 0,
                                'status' => $language->get('general', 'offline'),
                                'server_offline' => $language->get('general', 'server_offline')
                            ];
                        }
                    }
                }
            }

            if ($accumulate === true) {
                $to_return = [
                    'status_value' => $status,
                    'status' => (($status == 1) ? $language->get('general', 'online') : $language->get('general', 'offline')),
                    'status_full' => (($status == 1) ? str_replace('{x}', $total_count, $language->get('general', 'currently_x_players_online')) : $language->get('general', 'server_offline')),
                    'total_players' => $total_count,
                    'player_count' => $total_count
                ];
            }
            return $to_return;
        }

        return [];
    }
}
