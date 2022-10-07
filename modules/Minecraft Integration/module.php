<?php

class Minecraft_Module extends Module {

    private Language $_language;
    private Language $_minecraft_language;

    public function __construct(Language $language, Language $minecraft_language, Pages $pages, Endpoints $endpoints) {
        $this->_language = $language;
        $this->_minecraft_language = $minecraft_language;

        $name = 'Minecraft Integration';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '2.0.2';
        $nameless_version = '2.0.2';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        $pages->add('Core', '/banner', 'pages/minecraft/banner.php');
        $pages->add('Core', '/queries/server', 'queries/server.php');
        $pages->add('Core', '/queries/servers', 'queries/servers.php');
        $pages->add('Core', '/status', 'pages/status.php', 'status');
        $pages->add('Core', '/leaderboards', 'pages/leaderboards.php', 'leaderboards');

        $pages->add('Core', '/user/placeholders', 'pages/user/placeholders.php');

        // Panel
        $pages->add('Core', '/panel/minecraft/placeholders', 'pages/panel/placeholders.php');
        $pages->add('Core', '/panel/minecraft', 'pages/panel/minecraft.php');
        $pages->add('Core', '/panel/minecraft/authme', 'pages/panel/minecraft_authme.php');
        $pages->add('Core', '/panel/minecraft/account_verification', 'pages/panel/minecraft_account_verification.php');
        $pages->add('Core', '/panel/minecraft/servers', 'pages/panel/minecraft_servers.php');
        $pages->add('Core', '/panel/minecraft/query_errors', 'pages/panel/minecraft_query_errors.php');
        $pages->add('Core', '/panel/minecraft/banners', 'pages/panel/minecraft_server_banners.php');

        // Ajax GET requests
        $pages->addAjaxScript(URL::build('/queries/servers'));

        // Avatar sources
        AvatarSource::registerSource(new CrafatarAvatarSource());
        AvatarSource::registerSource(new CraftheadAvatarSource());
        AvatarSource::registerSource(new CravatarAvatarSource());
        AvatarSource::registerSource(new MCHeadsAvatarSource());
        AvatarSource::registerSource(new MinotarAvatarSource());
        AvatarSource::registerSource(new VisageAvatarSource());

        // Autoload API Endpoints
        $endpoints->loadEndpoints(ROOT_PATH . '/modules/Minecraft Integration/includes/endpoints');

        GroupSyncManager::getInstance()->registerInjector(new MinecraftGroupSyncInjector);

        Integrations::getInstance()->registerIntegration(new MinecraftIntegration($language));
    }

    public function onInstall() {
    }

    public function onUninstall() {
    }

    public function onDisable() {
    }

    public function onEnable() {
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, ?TemplateBase $template) {
        PermissionHandler::registerPermissions($language->get('moderator', 'staff_cp'), [
            'admincp.core.placeholders' => $language->get('admin', 'core') . ' &raquo; ' . $language->get('admin', 'placeholders'),
            'admincp.minecraft' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft'),
            'admincp.minecraft.authme' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'authme_integration'),
            'admincp.minecraft.verification' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'account_verification'),
            'admincp.minecraft.servers' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'minecraft_servers'),
            'admincp.minecraft.query_errors' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'query_errors'),
            'admincp.minecraft.banners' => $language->get('admin', 'integrations') . ' &raquo; ' . $language->get('admin', 'minecraft') . ' &raquo; ' . $language->get('admin', 'server_banners'),
        ]);

        // Widgets - only load if on a widget staffcp page or the frontend
        if (defined('FRONT_END') || (defined('PANEL_PAGE') && str_contains(PANEL_PAGE, 'widget'))) {
            // Server status
            $widgets->add(new ServerStatusWidget($smarty, $language, $cache));
        }

        // Status page?
        $cache->setCache('status_page');
        if ($cache->isCached('enabled')) {
            $status_enabled = $cache->retrieve('enabled');

        } else {
            $status_enabled = Util::getSetting('status_page') === '1' ? 1 : 0;
            $cache->store('enabled', $status_enabled);

        }

        if ($status_enabled == 1) {
            // Add status link to navbar
            $cache->setCache('navbar_order');
            if (!$cache->isCached('status_order')) {
                $status_order = 3;
                $cache->store('status_order', 3);
            } else {
                $status_order = $cache->retrieve('status_order');
            }

            $cache->setCache('navbar_icons');
            if (!$cache->isCached('status_icon')) {
                $icon = '';
            } else {
                $icon = $cache->retrieve('status_icon');
            }

            $navs[0]->add('status', $language->get('general', 'status'), URL::build('/status'), 'top', null, $status_order, $icon);
        }

        $leaderboard_placeholders = Placeholders::getInstance()->getLeaderboardPlaceholders();

        // Only add leaderboard link if there is at least one enabled placeholder
        if (Util::getSetting('placeholders') === '1' && count($leaderboard_placeholders)) {
            $cache->setCache('navbar_order');
            if (!$cache->isCached('leaderboards_order')) {
                $leaderboards_order = 4;
                $cache->store('leaderboards_order', 4);
            } else {
                $leaderboards_order = $cache->retrieve('leaderboards_order');
            }

            $cache->setCache('navbar_icons');
            if (!$cache->isCached('leaderboards_icon')) {
                $leaderboards_icon = '';
            } else {
                $leaderboards_icon = $cache->retrieve('leaderboards_icon');
            }

            $navs[0]->add('leaderboards', $language->get('general', 'leaderboards'), URL::build('/leaderboards'), 'top', null, $leaderboards_order, $leaderboards_icon);
        }

        if (defined('FRONT_END')) {
            // Query main server
            $cache->setCache('mc_default_server');

            // Already cached?
            if ($cache->isCached('default_query')) {
                $result = $cache->retrieve('default_query');
                $default = $cache->retrieve('default');
            } else {
                if ($cache->isCached('default')) {
                    $default = $cache->retrieve('default');
                    $sub_servers = $cache->retrieve('default_sub');
                } else {
                    // Get default server from database
                    $default = DB::getInstance()->get('mc_servers', ['is_default', true])->results();
                    if (count($default)) {
                        // Get sub-servers of default server
                        $sub_servers = DB::getInstance()->get('mc_servers', ['parent_server', $default[0]->id])->results();
                        if (count($sub_servers)) {
                            $cache->store('default_sub', $sub_servers);
                        } else {
                            $cache->store('default_sub', []);
                        }

                        $default = $default[0];

                        $cache->store('default', $default, 60);
                    } else {
                        $cache->store('default', null, 60);
                    }
                }

                if (!is_null($default) && isset($default->ip)) {
                    $full_ip = ['ip' => $default->ip . (is_null($default->port) ? '' : ':' . $default->port), 'pre' => $default->pre, 'name' => $default->name];

                    // Get query type
                    $query_type = Util::getSetting('external_query') === '1' ? 'external' : 'internal';

                    if (isset($sub_servers) && count($sub_servers)) {
                        $servers = [$full_ip];

                        foreach ($sub_servers as $server) {
                            $servers[] = [
                                'ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port),
                                'pre' => $server->pre,
                                'name' => $server->name,
                                'bedrock' => $server->bedrock
                            ];
                        }

                        $result = MCQuery::multiQuery($servers, $query_type, $language, true);

                        if (isset($result['status_value']) && $result['status_value'] == 1) {
                            $result['status'] = $language->get('general', 'online');

                            if ($result['player_count'] == 1) {
                                $result['status_full'] = $language->get('general', 'currently_1_player_online');
                                $result['x_players_online'] = $language->get('general', 'currently_1_player_online');
                            } else {
                                $result['status_full'] = $language->get('general', 'currently_x_players_online', ['count' => $result['player_count']]);
                                $result['x_players_online'] = $language->get('general', 'currently_x_players_online', ['count' => $result['player_count']]);
                            }

                        } else {
                            $result['status'] = $language->get('general', 'offline');
                            $result['status_full'] = $language->get('general', 'server_offline');
                            $result['server_offline'] = $language->get('general', 'server_offline');
                        }

                    } else {
                        $result = MCQuery::singleQuery($full_ip, $query_type, $default->bedrock, $language);

                        if (isset($result['status_value']) && $result['status_value'] == 1) {
                            $result['status'] = $language->get('general', 'online');

                            if ($result['player_count'] == 1) {
                                $result['status_full'] = $language->get('general', 'currently_1_player_online');
                                $result['x_players_online'] = $language->get('general', 'currently_1_player_online');
                            } else {
                                $result['status_full'] = $language->get('general', 'currently_x_players_online', ['count' => $result['player_count']]);
                                $result['x_players_online'] = $language->get('general', 'currently_x_players_online', ['count' => $result['player_count']]);
                            }

                        } else {
                            $result['status'] = $language->get('general', 'offline');
                            $result['status_full'] = $language->get('general', 'server_offline');
                            $result['server_offline'] = $language->get('general', 'server_offline');

                        }

                    }

                    // Cache for 1 minute
                    $cache->store('default_query', $result, 60);
                }
            }

            $smarty->assign('MINECRAFT', true);

            if (isset($result)) {
                $smarty->assign('SERVER_QUERY', $result);
            }

            if (!is_null($default) && isset($default->ip)) {
                $smarty->assign('CONNECT_WITH', $language->get('general', 'connect_with_ip_x', [
                    'address' => '<span id="ip">' . Output::getClean($default->ip . ($default->port && $default->port != 25565 ? ':' . $default->port : '')) . '</span>',
                ]));
                $smarty->assign('DEFAULT_IP', Output::getClean($default->ip . ($default->port != 25565 ? ':' . $default->port : '')));
                $smarty->assign('CLICK_TO_COPY_TOOLTIP', $language->get('general', 'click_to_copy_tooltip'));
                $smarty->assign('COPIED', $language->get('general', 'copied'));
            } else {
                $smarty->assign('CONNECT_WITH', '');
                $smarty->assign('DEFAULT_IP', '');
            }

            $smarty->assign('SERVER_OFFLINE', $language->get('general', 'server_offline'));            
        } else {
            if ($user->hasPermission('admincp.minecraft')) {
                if (!$cache->isCached('minecraft_icon')) {
                    $icon = '<i class="nav-icon fas fa-cubes"></i>';
                    $cache->store('minecraft_icon', $icon);
                } else {
                    $icon = $cache->retrieve('minecraft_icon');
                }

                $navs[2]->addItemToDropdown('integrations', 'minecraft', $language->get('admin', 'minecraft'), URL::build('/panel/minecraft'), 'top', null, $icon, $order);
            }
        }
    }

    public function getDebugInfo(): array {
        $servers = [];
        $group_sync_server_id = Util::getSetting('group_sync_mc_server');
        foreach (DB::getInstance()->get('mc_servers', ['id', '<>', 0])->results() as $server) {
            $servers[(int)$server->id] = [
                'id' => (int)$server->id,
                'name' => $server->name,
                'ip' => $server->ip,
                'query_ip' => $server->query_ip,
                'port' => $server->port,
                'query_port' => $server->query_port,
                'bedrock' => (bool)$server->bedrock,
                'group_sync_server' => $server->id == $group_sync_server_id,
            ];
        }

        return [
            'minecraft' => [
                'mc_integration' => (bool)Util::getSetting('mc_integration'),
                'uuid_linking' => (bool)Util::getSetting('uuid_linking'),
                'username_sync' => (bool)Util::getSetting('username_sync'),
                'external_query' => (bool)Util::getSetting('external_query'),
                'servers' => $servers,
            ]
        ];
    }
}
