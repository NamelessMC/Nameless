<?php

$namelessmc_modules = [];
$namelessmc_fe_templates = [];
$namelessmc_panel_templates = [];

// Get all modules
$modules = $queries->getWhere('modules', ['id', '<>', 0]);
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

$templates_query = $queries->getWhere('templates', ['id', '<>', 0]);
foreach ($templates_query as $fe_template) {
    $template_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($fe_template->name), 'template.php']);

    if (file_exists($template_path)) {
        require_once($template_path);
    }

    $namelessmc_fe_templates[$fe_template->name] = [
        'name' => $fe_template->name,
        'enabled' => (bool) $fe_template->enabled,
        'is_default' => (bool) $fe_template->is_default,
        'author' => $template->getAuthor(),
        'template_version' => $template->getVersion(),
        'namelessmc_version' => $template->getNamelessVersion(),
    ];
}

$panel_templates_query = $queries->getWhere('panel_templates', ['id', '<>', 0]);
foreach ($panel_templates_query as $panel_template) {

    $template_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'panel_templates', htmlspecialchars($panel_template->name), 'template.php']);

    if (file_exists($template_path)) {
        require_once($template_path);
    }

    $namelessmc_panel_templates[$panel_template->name] = [
        'name' => $panel_template->name,
        'enabled' => (bool) $panel_template->enabled,
        'is_default' => (bool) $panel_template->is_default,
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
            $rules[$column] = (int) $value;
        } else {
            $rules[$column] = $value;
        }
    }

    $group_sync['rules'][(int) $rule->id] = $rules;
}

$groups = [];
foreach ($queries->getWhere('groups', ['id', '<>', 0]) as $group) {
    $groups[(int) $group->id] = [
        'id' => (int) $group->id,
        'name' => $group->name,
        'group_html' => $group->group_html,
        'group_html_lg' => $group->group_html_lg,
        'admin_cp' => (bool) $group->admin_cp,
        'staff' => (bool) $group->staff,
        'permissions' => json_decode($group->permissions, true) ?? [],
        'default_group' => (bool) $group->default_group,
        'order' => (int) $group->order,
        'force_tfa' => (bool) $group->force_tfa,
        'deleted' => (bool) $group->deleted,
    ];
}


$data = [
    'debug_version' => 1,
    'generated_at' => time(),
    'generated_by_name' => $user->data()->username,
    'generated_by_uuid' => $user->data()->uuid ?? '',
    'namelessmc' => [
        'version' => Util::getSetting(DB::getInstance(), 'nameless_version'),
        'update_available' => Util::getSetting(DB::getInstance(), 'version_update') == 'false' ? false : true,
        'update_checked' => (int) Util::getSetting(DB::getInstance(), 'version_checked'),
        'settings' => [
            'phpmailer' => (bool) Util::getSetting(DB::getInstance(), 'phpmailer'),
            'api_enabled' => (bool) Util::getSetting(DB::getInstance(), 'use_api'),
            'email_verification' => (bool) Util::getSetting(DB::getInstance(), 'email_verification'),
            'api_verification' => (bool) Util::getSetting(DB::getInstance(), 'api_verification'),
            'login_method' => Util::getSetting(DB::getInstance(), 'login_method'),
            'captcha_type' => Util::getSetting(DB::getInstance(), 'recaptcha_type'),
            'captcha_login' => (bool) Util::getSetting(DB::getInstance(), 'recaptcha_login'),
            'captcha_contact' => (bool) Util::getSetting(DB::getInstance(), 'recaptcha'),
            'group_sync' => $group_sync,
        ],
        'groups' => $groups,
        'config' => [
            'core' => array_filter($GLOBALS['config']['core'], static fn (string $key) => $key != 'hostname', ARRAY_FILTER_USE_KEY),
            'allowedProxies' => $GLOBALS['config']['allowedProxies']
        ],
        'modules' => $namelessmc_modules,
        'templates' => [
            'front_end' => $namelessmc_fe_templates,
            'panel' => $namelessmc_panel_templates,
        ],
    ],
    'environment' => [
        'php_version' => phpversion(),
        'php_modules' => get_loaded_extensions(),
        'host_os' => php_uname('s'),
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

$result = HttpClient::post('https://bytebin.rkslot.nl/post', json_encode($data, JSON_PRETTY_PRINT));

die('https://debug.namelessmc.com/' . json_decode($result, true)['key']);
