<?php

// Can user generate the debug link?
if (!defined('DEBUGGING') && !$user->hasPermission('admincp.core.debugging')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

$namelessmc_modules = [];
$namelessmc_fe_templates = [];
$namelessmc_panel_templates = [];

// Get all modules
$modules = DB::getInstance()->get('modules', ['id', '<>', 0])->results();
$enabled_modules = Module::getModules();

foreach ($modules as $item) {
    $exists = false;
    foreach ($enabled_modules as $enabled_item) {
        if ($enabled_item->getName() == $item->name) {
            $exists = true;
            $module = $enabled_item;
            break;
        }
    }

    if (!$exists) {
        if (!file_exists(ROOT_PATH . '/modules/' . $item->name . '/init.php')) {
            continue;
        }

        require_once(ROOT_PATH . '/modules/' . $item->name . '/init.php');
    }

    $namelessmc_modules[$module->getName()] = [
        'name' => $module->getName(),
        'enabled' => Util::isModuleEnabled($module->getName()),
        'author' => $module->getAuthor(),
        'module_version' => $module->getVersion(),
        'namelessmc_version' => $module->getNamelessVersion(),
        'debug_info' => $module->getDebugInfo(),
    ];
}

$templates_query = DB::getInstance()->get('templates', ['id', '<>', 0])->results();
foreach ($templates_query as $fe_template) {
    $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($fe_template->name), 'template.php']);

    if (file_exists($template_path)) {
        require_once($template_path);
    }

    $namelessmc_fe_templates[$fe_template->name] = [
        'name' => $fe_template->name,
        'enabled' => (bool)$fe_template->enabled,
        'is_default' => (bool)$fe_template->is_default,
        'author' => $template->getAuthor(),
        'template_version' => $template->getVersion(),
        'namelessmc_version' => $template->getNamelessVersion(),
    ];
}

$panel_templates_query = DB::getInstance()->get('panel_templates', ['id', '<>', 0])->results();
foreach ($panel_templates_query as $panel_template) {

    $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'panel_templates', Output::getClean($panel_template->name), 'template.php']);

    if (file_exists($template_path)) {
        require_once($template_path);
    }

    $namelessmc_panel_templates[$panel_template->name] = [
        'name' => $panel_template->name,
        'enabled' => (bool)$panel_template->enabled,
        'is_default' => (bool)$panel_template->is_default,
        'author' => $template->getAuthor(),
        'template_version' => $template->getVersion(),
        'namelessmc_version' => $template->getNamelessVersion(),
    ];
}

$group_sync = [];
foreach (GroupSyncManager::getInstance()->getInjectors() as $injector) {
    $group_sync['injectors'][] = [
        'name' => $injector->getName(),
        'enabled' => $injector->shouldEnable(),
        'module' => $injector->getModule(),
        'column_name' => $injector->getColumnName(),
    ];
}

$group_sync['rules'] = [];
foreach (DB::getInstance()->get('group_sync', ['id', '<>', 0])->results() as $rule) {
    $rules = [];
    foreach (get_object_vars($rule) as $column => $value) {
        if ($column == 'id') {
            $rules[$column] = (int)$value;
        } else {
            $rules[$column] = $value;
        }
    }

    $group_sync['rules'][(int)$rule->id] = $rules;
}

$webhooks = [];
foreach (DB::getInstance()->query('SELECT `id`, `name`, `action`, `events` FROM nl2_hooks')->results() as $webhook) {
    $webhooks[$webhook->id] = [
        'id' => (int)$webhook->id,
        'name' => $webhook->name,
        'action' => (int)$webhook->action,
        'events' => json_decode($webhook->events),
    ];
}

$forum_hooks = [];
foreach (DB::getInstance()->query('SELECT `id`, `forum_title`, `hooks` FROM nl2_forums WHERE `hooks` IS NOT NULL')->results() as $forum) {
    $forum_hooks[] = [
        'forum_id' => (int)$forum->id,
        'title' => $forum->forum_title,
        'hooks' => array_map(static fn($hook) => (int)$hook, json_decode($forum->hooks)),
    ];
}

$groups = [];
foreach (Group::all() as $group) {
    $groups[(int)$group->id] = [
        'id' => (int)$group->id,
        'name' => $group->name,
        'group_html' => $group->group_html,
        'admin_cp' => (bool)$group->admin_cp,
        'staff' => (bool)$group->staff,
        'permissions' => json_decode($group->permissions, true) ?? [],
        'default_group' => (bool)$group->default_group,
        'order' => (int)$group->order,
        'force_tfa' => (bool)$group->force_tfa,
        'deleted' => (bool)$group->deleted,
    ];
}

$integrations = [];
foreach (Integrations::getInstance()->getAll() as $integration) {
    $integrations[$integration->getName()] = [
        'id' => (int) $integration->data()->id,
        'name' => $integration->data()->name,
        'enabled' => (bool) $integration->data()->enabled,
        'can_unlink' => (bool) $integration->data()->can_unlink,
        'required' => (bool) $integration->data()->required,
        'order' => (int) $integration->data()->order
    ];
}

$oauth_providers = [];
$providers_available = array_keys(NamelessOAuth::getInstance()->getProvidersAvailable());
foreach (NamelessOAuth::getInstance()->getProviders() as $provider_name => $data) {
    $oauth_providers[$provider_name] = [
        'provider_name' => $provider_name,
        'module' => $data['module'],
        'class' => $data['class'],
        'user_id_name' => $data['user_id_name'],
        'scope_id_name' => $data['scope_id_name'],
        'enabled' => in_array($provider_name, $providers_available),
        'client_id' => NamelessOAuth::getInstance()->getCredentials($provider_name)[0],
    ];
}

$namelessmc_version = Util::getSetting('nameless_version');

$uuid = DB::getInstance()->query('SELECT identifier FROM nl2_users_integrations INNER JOIN nl2_integrations on integration_id=nl2_integrations.id WHERE name = \'Minecraft\' AND user_id = ?;', [$user->data()->id]);
if ($uuid->count()) {
    $uuid = $uuid->first()->identifier;
} else {
    $uuid = '';
}

$logs = [];
foreach (['fatal', 'warning', 'notice', 'other', 'custom'] as $type) {
    $file_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'cache', 'logs', $type . '-log.log']);
    $logs[$type] = file_exists($file_path) ? Util::readFileEnd($file_path, $max_bytes = 10_000) : '';
}

$user_data = [];
if ($user->isLoggedIn()) {
    $user_integrations = [];
    foreach ($user->getIntegrations() as $integrationUser) {
        $user_integrations[$integrationUser->getIntegration()->getName()] = [
            'username' => $integrationUser->data()->username,
            'identifier' => $integrationUser->data()->identifier,
            'verified' => $integrationUser->data()->verified
        ];
    }

    $user_data = [
        'id' => (int) $user->data()->id,
        'username' => $user->data()->username,
        'nickname' => $user->getDisplayname(),
        'groups' => array_values($user->getAllGroupIds()),
        'integrations' => $user_integrations
    ];
}

$data = [
    'generated_at' => time(),
    'generated_by_name' => $user->data()->username,
    'generated_by_uuid' => $uuid,
    'user' => $user_data,
    'namelessmc' => [
        'version' => $namelessmc_version,
        'update_available' => Util::getSetting('version_update') === 'urgent' || Util::getSetting('version_update') === 'true',
        'update_checked' => (int) Util::getSetting('version_checked'),
        'settings' => [
            'phpmailer' => Util::getSetting('phpmailer') === '1',
            'api_enabled' => Util::getSetting('use_api') === '1',
            'email_verification' => Util::getSetting('email_verification') === '1',
            'login_method' => Util::getSetting('login_method'),
            'captcha_type' => Util::getSetting('recaptcha_type'),
            'captcha_login' => Util::getSetting('recaptcha_login') === 'false' ? false : true, // dont ask
            'group_sync' => $group_sync,
            'webhooks' => [
                'actions' => [
                    2 => 'Discord',
                ],
                'hooks' => $webhooks,
                'forum_hooks' => $forum_hooks,
            ],
            'trusted_proxies' => HttpUtils::getTrustedProxies(),
        ],
        'groups' => $groups,
        'config' => [
            'core' => array_filter(
                Config::get('core'),
                static fn(string $key) => $key !== 'hostname' && $key !== 'trustedProxies',
                ARRAY_FILTER_USE_KEY
            ),
        ],
        'modules' => $namelessmc_modules,
        'templates' => [
            'front_end' => $namelessmc_fe_templates,
            'panel' => $namelessmc_panel_templates,
        ],
        'integrations' => $integrations,
        'oauth_providers' => $oauth_providers,
    ],
    'logs' => [
        'fatal' => $logs['fatal'],
        'warning' => $logs['warning'],
        'notice' => $logs['notice'],
        'other' => $logs['other'],
        'custom' => $logs['custom'],
    ],
    'environment' => [
        'php_version' => PHP_VERSION,
        'php_modules' => get_loaded_extensions(),
        'host_os' => PHP_OS,
        'host_kernel_version' => php_uname('r'),
        'official_docker_image' => getenv('NAMELESSMC_METRICS_DOCKER') == true,
        'disk_total_space' => disk_total_space('./'),
        'disk_free_space' => disk_free_space('./'),
        'memory_total_space' => ini_get('memory_limit'),
        'memory_used_space' => memory_get_usage(),
        'config_writable' => is_writable(ROOT_PATH . '/core/config.php'),
        'cache_writable' => is_writable(ROOT_PATH . '/cache'),
    ],
];

$result = HttpClient::post('https://bytebin.rkslot.nl/post', json_encode($data, JSON_PRETTY_PRINT), [
    'headers' => [
        'Content-Type' => 'application/json',
        'User-Agent' => 'NamelessMC/' . $namelessmc_version,
    ],
])->json(true);

die('https://debug.namelessmc.com/' . $result['key']);
