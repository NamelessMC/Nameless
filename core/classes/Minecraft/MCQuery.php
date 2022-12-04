<?php

use xPaw\MinecraftPing;
use xPaw\MinecraftQuery;

/**
 * Abstraction over xPaw\MinecraftQuery & xPaw\MinecraftPing to make them fit with our needs.
 *
 * @package NamelessMC\Minecraft
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class MCQuery {

    private const COLOUR_CHAR = '§';

    private const COLOURS = [
        'AA0000' => '4',
        'FF5555' => 'c',
        'FFAA00' => '6',
        'FFFF55' => 'e',
        '00AA00' => '2',
        '55FF55' => 'a',
        '55FFFF' => 'b',
        '00AAAA' => '3',
        '0000AA' => '1',
        '5555FF' => '9',
        'FF55FF' => 'd',
        'AA00AA' => '5',
        'FFFFFF' => 'f',
        'AAAAAA' => '7',
        '555555' => '8',
        '000000' => '0',
    ];

    /**
     * Query a single server
     *
     * @param array $ip Array ['ip' => string, 'pre' => int] - 'ip' contains ip:port, 'pre' 1 for pre-Minecraft 1.7 otherwise 0
     * @param string $type Type of query to use (`internal` or `external`).
     * @param bool $bedrock Whether this is a Bedrock server or not.
     * @param Language $language Query language object.
     * @return array Array containing query result.
     */
    public static function singleQuery(array $ip, string $type, bool $bedrock, Language $language): array {
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
                            'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query['players']['online'])]),
                            'motd' => self::getMotd(
                                is_string($text = $query['description']) ? $text : $text['text'],
                                $query['description']['extra'] ?? [],
                            ),
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
                        'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query['Players'])]),
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

            if ($query !== false && !$query->error && isset($query->response)) {
                $player_list = $query->response->players->list ?? [];

                return [
                    'status_value' => 1,
                    'status' => $language->get('general', 'online'),
                    'player_count' => Output::getClean($query->response->players->online),
                    'player_count_max' => Output::getClean($query->response->players->max),
                    'player_list' => $player_list,
                    'format_player_list' => self::formatPlayerList($player_list),
                    'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query->response->players->online)]),
                    // TODO: external query does not return bedrock MOTD at all
                    'motd' => self::getMotd(
                        json_decode(json_encode($query->response->description->text), true) ?? '',
                        json_decode(json_encode($query->response->description->extra), true) ?? []
                    ),
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

            DB::getInstance()->insert('query_errors', [
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

        $integration = Integrations::getInstance()->getIntegration('Minecraft');
        foreach ($player_list as $player) {
            $player = (array)$player;

            $integration_user = new IntegrationUser($integration, str_replace('-', '', $player['id']), 'identifier');
            if ($integration_user->exists()) {
                $user = $integration_user->getUser();
                if ($user->exists()) {
                    $avatar = $user->getAvatar();
                    $profile = $user->getProfileURL();
                } else {
                    $avatar = AvatarSource::getAvatarFromUUID($player['id']);
                    $profile = '#';
                }
            } else {
                $avatar = AvatarSource::getAvatarFromUUID($player['id']);
                $profile = '#';
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
     *
     * @param array $servers Servers
     * @param string $type Type of query to use (internal or external)
     * @param Language $language Query language object
     * @param bool $accumulate Whether to return as one accumulated result or not
     * @return array Array containing query result
     */
    public static function multiQuery(array $servers, string $type, Language $language, bool $accumulate): array {
        $to_return = [];
        $total_count = 0;
        $status = 0;

        if ($type === 'internal') {
            foreach ($servers as $server) {
                $query_ip = explode(':', $server['ip']);
                if (count($query_ip) > 2) {
                    continue;
                }

                try {
                    if ($server['bedrock']) {
                        $ping = new MinecraftQuery();
                        $ping->ConnectBedrock($query_ip[0], ($query_ip[1] ?? 19132), 5);
                        $query = $ping->GetInfo();
                    } else {
                        $ping = new MinecraftPing($query_ip[0], ($query_ip[1] ?? 25565), 5);

                        if ($server['pre'] == 1) {
                            $query = $ping->QueryOldPre17();
                        } else {
                            $query = $ping->Query();
                        }
                    }
                } catch (Exception $e) {
                    $query = [];

                    DB::getInstance()->insert('query_errors', [
                        'date' => date('U'),
                        'error' => $e->getMessage(),
                        'ip' => $query_ip[0],
                        'port' => ($query_ip[1] ?? ($server['bedrock'] ? 19132 : 25565))
                    ]);
                }

                // bedrock
                if ($server['bedrock']) {
                    if ($accumulate === false) {
                        $to_return[] = [
                            'name' => Output::getClean($server['name']),
                            'status_value' => 1,
                            'status' => $language->get('general', 'online'),
                            'player_count' => Output::getClean($query['Players']),
                            'player_count_max' => Output::getClean($query['MaxPlayers']),
                            'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query['Players'])]),
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
                            'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query['players']['online'])]),
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

            if (isset($ping) && $ping instanceof MinecraftPing) {
                $ping->close();
            }

        } else {
            // External query
            foreach ($servers as $server) {
                $query_ip = explode(':', $server['ip']);
                if (count($query_ip) > 2) {
                    continue;
                }

                $is_bedrock = isset($server['bedrock']) && $server['bedrock'] === true;

                $query = ExternalMCQuery::query($query_ip[0], ($query_ip[1] ?? ($is_bedrock ? 19132 : 25565)), $is_bedrock);

                if ($query !== false && !$query->error && isset($query->response)) {
                    if ($accumulate === false) {
                        $to_return[] = [
                            'name' => Output::getClean($server['name']),
                            'status_value' => 1,
                            'status' => $language->get('general', 'online'),
                            'player_count' => Output::getClean($query->response->players->online),
                            'player_count_max' => Output::getClean($query->response->players->max),
                            'x_players_online' => $language->get('general', 'currently_x_players_online', ['count' => Output::getClean($query->response->players->online)]),
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

    /**
     * Convert a Minecraft MOTD to its legacy colour codes
     *
     * @param string $text Legacy MOTD single-line text
     * @param array $modern_format Array of modern MOTD format strings
     * @return string MOTD as legacy MC colours
     */
    private static function getMotd(string $text, array $modern_format): string {
        if ($text !== '') {
            return $text;
        }

        // some servers (originrealms) return a weird MOTD
        if (count($modern_format) === 1 && is_array($modern_format[0])) {
            $modern_format = $modern_format[0]['extra'] ?? [];
        }
        // and sometimes it's doubly nested...
        if (count($modern_format) === 1 && is_array($modern_format[0])) {
            $modern_format = $modern_format[0]['extra'] ?? [];
        }

        $motd = '';
        foreach ($modern_format as $word) {
            $motd .= self::COLOUR_CHAR . 'r';
            if (isset($word['color'])) {
                $motd .= self::getColor($word['color']);
            }

            if (isset($word['bold']) && $word['bold'] === true) {
                $motd .= self::COLOUR_CHAR . 'l';
            }

            $motd .= trim($word['text'], ' ');
        }

        return trim($motd);
    }

    /**
     * Find the closest MC colour to a given hex colour
     *
     * @param string $rgb RGB colour code
     * @return string The closest Minecraft colour code to the given RGB value
     */
    private static function getColor(string $rgb): string {
        if (!str_contains($rgb, '#')) {
            $rgb = substr($rgb, 1);
        }

        $smallestDiff = null;
        $closestColor = "";
        foreach (self::COLOURS as $hex => $char) {
            $diff = self::colorDiff($hex, $rgb);
            if ($smallestDiff === null || $diff < $smallestDiff) {
                $smallestDiff = $diff;
                $closestColor = $char;
            }
        }

        return self::COLOUR_CHAR . $closestColor;
    }

    /**
     * Find the numerical difference between two RGB colours
     *
     * @param mixed $rgb1 RGB colour code
     * @param mixed $rgb2 RGB colour code
     * @return int The difference between two RGB colours
     */
    private static function colorDiff($rgb1, $rgb2): int {
        $red1 = hexdec(substr($rgb1, 0, 2));
        $green1 = hexdec(substr($rgb1, 2, 2));
        $blue1 = hexdec(substr($rgb1, 4, 2));

        $red2_substr = substr($rgb2, 0, 2);
        $green2_substr = substr($rgb2, 2, 2);
        $blue2_substr = substr($rgb2, 4, 2);

        $red2 = 0;
        if (ctype_xdigit($red2_substr)) {
            $red2 = hexdec($red2_substr);
        }

        $green2 = 0;
        if (ctype_xdigit($green2_substr)) {
            $green2 = hexdec($green2_substr);
        }

        $blue2 = 0;
        if (ctype_xdigit($blue2_substr)) {
            $blue2 = hexdec($blue2_substr);
        }

        return abs($red1 - $red2) + abs($green1 - $green2) + abs($blue1 - $blue2);
    }
}
