<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.1
 *
 *  License: MIT
 *
 *  Initialisation file
 */

// Nameless error handling
set_exception_handler([ErrorHandler::class, 'catchException']);
// catchError() used for throw_error or any exceptions which may be missed by catchException()
set_error_handler([ErrorHandler::class, 'catchError']);
register_shutdown_function([ErrorHandler::class, 'catchShutdownError']);

session_start();

// Page variable must be set
if (!isset($page)) {
    die('$page variable is unset. Cannot continue.');
}

Debugging::setCanViewDetailedError(defined('DEBUGGING') && DEBUGGING);
Debugging::setCanGenerateDebugLink(defined('DEBUGGING') && DEBUGGING);

// All paths should be writable, but recursively checking everything would take too much time.
// Only check the most important paths.
$writable_check_paths = [
    ROOT_PATH,
    ROOT_PATH . '/cache',
    ROOT_PATH . '/cache/logs',
    ROOT_PATH . '/cache/sitemaps',
    ROOT_PATH . '/cache/templates_c',
    ROOT_PATH . '/uploads',
    ROOT_PATH . '/core/config.php'
];

foreach ($writable_check_paths as $path) {
    if (is_dir($path) && !is_writable($path)) {
        die('<p>Your website directory or a subdirectory is not writable. Please ensure all files and directories are owned by
        the correct user.</p><p><strong>Example</strong> command to change owner recursively: <code>sudo chown -R www-data: ' . Output::getClean(ROOT_PATH) . '</code></p>');
    }
}

if (!file_exists(ROOT_PATH . '/cache/templates_c')) {
    try {
        mkdir(ROOT_PATH . '/cache/templates_c', 0777, true);
    } catch (Exception $e) {
        die('Unable to create <strong>/cache</strong> directories, please check your file permissions.');
    }
}

if (!Config::exists()) {
    $page = 'install';
}

// If we're accessing the upgrade script don't initialise further
if (isset($_GET['route']) && rtrim($_GET['route'], '/') == '/panel/upgrade') {
    $pages = new Pages();
    $pages->add('Core', '/panel/upgrade', 'pages/panel/upgrade.php');
    return;
}

if ($page != 'install') {
    /*
     * Initialise
     */

    $container = new \DI\Container();
    $container->set('Cache', \DI\create()->constructor(
        [
            'name' => 'nameless',
            'extension' => '.cache',
            'path' => ROOT_PATH . '/cache/'
        ]
    ));

    /** @var Cache $cache */
    $cache = $container->get('Cache');

    // Friendly URLs?
    define('FRIENDLY_URLS', Config::get('core.friendly') == 'true');

    // Force https/www?
    if (Config::get('core.force_https')) {
        define('FORCE_SSL', true);
    }
    if (Config::get('core.force_www')) {
        define('FORCE_WWW', true);
    }

    $host = HttpUtils::getHeader('Host');
    // Only check force HTTPS and force www. when Host header is set
    // These options don't make sense when making requests to IP addresses anyway
    if ($host !== null) {
        if (defined('FORCE_SSL') && HttpUtils::getProtocol() === 'http') {
            if (defined('FORCE_WWW') && !str_contains($host, 'www.')) {
                Redirect::to('https://www.' . $host . $_SERVER['REQUEST_URI']);
            } else {
                Redirect::to('https://' . $host . $_SERVER['REQUEST_URI']);
            }
        } else if (defined('FORCE_WWW') && !str_contains($host, 'www.')) {
            Redirect::to(HttpUtils::getProtocol() . '://www.' . $host . $_SERVER['REQUEST_URI']);
        }
    }

    // Ensure database is up-to-date
    PhinxAdapter::ensureUpToDate();

    // Error reporting
    if (!defined('DEBUGGING')) {
        if (Util::getSetting('error_reporting') === '1') {
            ini_set('display_startup_errors', 1);
            ini_set('display_errors', 1);
            error_reporting(-1);
            define('DEBUGGING', 1);
        } else {
            // Disable by default
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }

    /** @var Smarty $smarty */
    $smarty = $container->get('Smarty');

    if ((defined('DEBUGGING') && DEBUGGING) && class_exists('DebugBar\DebugBar')) {
        define('PHPDEBUGBAR', true);
        DebugBarHelper::getInstance()->enable($smarty);
    }

    // Get the Nameless version
    define('NAMELESS_VERSION', Util::getSetting('nameless_version'));

    // Set the date format
    define('DATE_FORMAT', Config::get('core.date_format') ?: 'd M Y, H:i');

    // User initialisation
    $user = new User();
    // Do they need logging in (checked remember me)?
    if (Cookie::exists(Config::get('remember.cookie_name')) && !Session::exists(Config::get('session.session_name'))) {
        $hash = Cookie::get(Config::get('remember.cookie_name'));
        $hashCheck = DB::getInstance()->get('users_session', [['hash', $hash], ['active', true]]);

        if ($hashCheck->count()) {
            $user = new User($hashCheck->first()->user_id);
            $user->login(null, $hash, true, 'hash');
        }
    }

    // Check if we're in a subdirectory
    if (isset($directories)) {
        if (empty($directories[0])) {
            unset($directories[0]);
        }

        $directories = array_values($directories);

        $config_path = Config::get('core.path');

        if (!empty($config_path)) {
            $config_path = explode('/', Config::get('core.path'));

            for ($i = 0, $iMax = count($config_path); $i < $iMax; $i++) {
                unset($directories[$i]);
            }

            define('CONFIG_PATH', '/' . Config::get('core.path'));

            $directories = array_values($directories);
        }

        $directory = implode('/', $directories);

        $directory = '/' . $directory;

        // Remove the trailing /
        if (strlen($directory) > 1) {
            $directory = rtrim($directory, '/');
        }
    }

    // Set timezone
    define('TIMEZONE', $user->isLoggedIn() ? $user->data()->timezone : Util::getSetting('timezone', 'Europe/London'));
    date_default_timezone_set(TIMEZONE);

    // Language
    $cache->setCache('languagecache');
    if ($cache->isCached('language')) {
        $default_language = $cache->retrieve('language');
    } else {
        $default_language = DB::getInstance()->get('languages', ['is_default', true])->results();
        if (count($default_language)) {
            $default_language = $default_language[0]->short_code;
            $cache->store('language', $default_language);
        } else {
            $default_language = 'en_UK';
        }
    }

    define('DEFAULT_LANGUAGE', $default_language);

    if (!$user->isLoggedIn() || !($user->data()->language_id)) {
        if (Util::getSetting('auto_language_detection') && (!Cookie::exists('auto_language') || Cookie::get('auto_language') === 'true')) {
            // Attempt to get the requested language from the browser if it exists
            $automatic_locale = Language::acceptFromHttp(HttpUtils::getHeader('Accept-Language') ?? '');
            if ($automatic_locale !== false) {
                $smarty->assign('AUTO_LANGUAGE_VALUE', $automatic_locale[1]);
                $default_language = $automatic_locale[0];
            }
        }

        // Default language for guests
        define('LANGUAGE', $default_language);
    } else {
        // User selected language
        $language = DB::getInstance()->get('languages', ['id', $user->data()->language_id])->results();
        if (!count($language)) {
            // Get default language
            define('LANGUAGE', $default_language);
        } else {
            define('LANGUAGE', $language[0]->short_code);
        }
    }
    $container->set('Language', \DI\create()->constructor('core', LANGUAGE));

    /** @var Language $language */
    $language = $container->get('Language');

    // Site name
    $sitename = Util::getSetting('sitename');
    if ($sitename === null) {
        die('No sitename in settings table');
    }
    define('SITE_NAME', $sitename);

    // Template
    if (!$user->isLoggedIn() || !($user->data()->theme_id)) {
        // Default template for guests
        $cache->setCache('templatecache');
        $template = $cache->retrieve('default');

        if (!$template) {
            define('TEMPLATE', 'DefaultRevamp');
        } else {
            define('TEMPLATE', $template);
        }
    } else {
        // User selected template
        $template = DB::getInstance()->get('templates', ['id', $user->data()->theme_id])->results();
        if (!count($template)) {
            // Get default template
            $cache->setCache('templatecache');
            $template = $cache->retrieve('default');

            if (!$template) {
                define('TEMPLATE', 'DefaultRevamp');
            } else {
                define('TEMPLATE', $template);
            }
        } else {
            // Check permissions
            $template = $template[0];
            $hasPermission = false;

            if ($template->enabled) {
                $user_templates = $user->getUserTemplates();

                foreach ($user_templates as $user_template) {
                    if ($user_template->id === $template->id) {
                        $hasPermission = true;
                        define('TEMPLATE', $template->name);
                        break;
                    }
                }
            }

            if (!$hasPermission) {
                // Get default template
                $cache->setCache('templatecache');
                $template = $cache->retrieve('default');

                if (!$template) {
                    define('TEMPLATE', 'DefaultRevamp');
                } else {
                    define('TEMPLATE', $template);
                }
            }
        }
    }

    // Panel template
    $cache->setCache('templatecache');
    $template = $cache->retrieve('panel_default');

    if (!$template) {
        define('PANEL_TEMPLATE', 'Default');
    } else {
        define('PANEL_TEMPLATE', $template);
    }

    // Smarty
    $securityPolicy = new Smarty_Security($smarty);
    $securityPolicy->php_modifiers = [
        'escape',
        'count',
        'key',
        'round',
        'ucfirst',
        'defined',
        'date',
        'explode',
        'implode',
        'strtolower',
        'strtoupper'
    ];
    $securityPolicy->php_functions = [
        'isset',
        'empty',
        'count',
        'sizeof',
        'in_array',
        'is_array',
        'time',
        'nl2br',
        'is_numeric',
        'file_exists',
        'array_key_exists'
    ];
    $securityPolicy->secure_dir = [ROOT_PATH . '/custom/templates', ROOT_PATH . '/custom/panel_templates'];
    $smarty->enableSecurity($securityPolicy);

    // Basic Smarty variables
    $smarty->assign([
        'CONFIG_PATH' => defined('CONFIG_PATH') ? CONFIG_PATH . '/' : '/',
        'OG_URL' => Output::getClean(rtrim(URL::getSelfURL(), '/') . $_SERVER['REQUEST_URI']),
        'SITE_NAME' => Output::getClean(SITE_NAME),
        'SITE_HOME' => URL::build('/'),
        'USER_INFO_URL' => URL::build('/queries/user/', 'id='),
        'GUEST' => $language->get('user', 'guest')
    ]);
    $cache->setCache('backgroundcache');
    if ($cache->isCached('og_image')) {
        // Assign the image value now, some pages may override it (via Page Metadata config)
        $smarty->assign('OG_IMAGE', rtrim(URL::getSelfURL(), '/') . $cache->retrieve('og_image'));
    }

    // Avatars
    $cache->setCache('avatar_settings_cache');
    if ($cache->isCached('custom_avatars') && $cache->retrieve('custom_avatars') == 1) {
        define('CUSTOM_AVATARS', true);
    }

    if ($cache->isCached('default_avatar_type')) {
        define('DEFAULT_AVATAR_TYPE', $cache->retrieve('default_avatar_type'));
        if (DEFAULT_AVATAR_TYPE == 'custom' && $cache->isCached('default_avatar_image')) {
            define('DEFAULT_AVATAR_IMAGE', $cache->retrieve('default_avatar_image'));
        } else {
            define('DEFAULT_AVATAR_IMAGE', '');
        }
    } else {
        define('DEFAULT_AVATAR_TYPE', 'minecraft');
    }

    if ($cache->isCached('avatar_source')) {
        define('DEFAULT_AVATAR_SOURCE', $cache->retrieve('avatar_source'));
    } else {
        define('DEFAULT_AVATAR_SOURCE', 'cravatar');
    }

    if ($cache->isCached('avatar_perspective')) {
        define('DEFAULT_AVATAR_PERSPECTIVE', $cache->retrieve('avatar_perspective'));
    } else {
        define('DEFAULT_AVATAR_PERSPECTIVE', 'face');
    }

    /** @var Widgets $widgets */
    $widgets = $container->get('Widgets');

    // Navbar links
    $navigation = new Navigation();
    $cc_nav = new Navigation();
    $staffcp_nav = new Navigation(true); // $staffcp_nav = panel nav

    // Add links to cc_nav
    $cc_nav->add('cc_overview', $language->get('user', 'overview'), URL::build('/user'));
    $cc_nav->add('cc_alerts', $language->get('user', 'alerts'), URL::build('/user/alerts'));
    $cc_nav->add('cc_messaging', $language->get('user', 'messaging'), URL::build('/user/messaging'));
    $cc_nav->add('cc_connections', $language->get('user', 'connections'), URL::build('/user/connections'));
    $cc_nav->add('cc_settings', $language->get('user', 'profile_settings'), URL::build('/user/settings'));
    $cc_nav->add('cc_oauth', $language->get('admin', 'oauth'), URL::build('/user/oauth'));

    // Placeholders enabled?
    if (Util::getSetting('placeholders') === '1') {
        $cc_nav->add('cc_placeholders', $language->get('user', 'placeholders'), URL::build('/user/placeholders'));
    }

    // Add homepage to navbar
    // Check navbar order + icon in cache
    $cache->setCache('navbar_order');
    if (!$cache->isCached('index_order')) {
        // Create cache entry now
        $home_order = 1;
        $cache->store('index_order', 1);
    } else {
        $home_order = $cache->retrieve('index_order');
    }

    $cache->setCache('navbar_icons');
    if ($cache->isCached('index_icon')) {
        $home_icon = $cache->retrieve('index_icon');
    } else {
        $home_icon = '';
    }

    $navigation->add('index', $language->get('general', 'home'), URL::build('/'), 'top', null, $home_order, $home_icon);

    /** @var Endpoints $endpoints */
    $endpoints = $container->get('Endpoints');

    /** @var Announcements $announcements */
    $announcements = $container->get('Announcements');

    // Modules
    $cache->setCache('modulescache');
    if (!$cache->isCached('enabled_modules')) {
        $cache->store('enabled_modules', [
            ['name' => 'Core', 'priority' => 1]
        ]);
        $cache->store('module_core', true);
    }
    $enabled_modules = $cache->retrieve('enabled_modules');

    foreach ($enabled_modules as $module) {
        if ($module['name'] == 'Core') {
            $core_exists = true;
            break;
        }
    }

    if (!isset($core_exists)) {
        $enabled_modules[] = [
            'name' => 'Core',
            'priority' => 1
        ];
    }

    $pages = $container->get('Pages');

    // Sort by priority
    usort($enabled_modules, static function ($a, $b) {
        return $a['priority'] - $b['priority'];
    });

    // Load module dependencies
    foreach ($enabled_modules as $module) {
        if (file_exists(ROOT_PATH . '/modules/' . $module['name'] . '/autoload.php')) {
            require_once ROOT_PATH . '/modules/' . $module['name'] . '/autoload.php';
        }
    }

    // Load modules
    foreach ($enabled_modules as $module) {
        if (file_exists(ROOT_PATH . '/modules/' . $module['name'] . '/init.php')) {
            require_once ROOT_PATH . '/modules/' . $module['name'] . '/init.php';
        }
    }

    // Maintenance mode?
    if (Util::getSetting('maintenance') === '1') {
        // Enabled
        // Admins only beyond this point
        if (!$user->isLoggedIn() || !$user->canViewStaffCP()) {
            // Maintenance mode
            if (isset($_GET['route']) && (
                    rtrim($_GET['route'], '/') === '/login'
                    || rtrim($_GET['route'], '/') === '/forgot_password'
                    || str_contains($_GET['route'], '/api/')
                    || str_contains($_GET['route'], 'queries')
                    || str_contains($_GET['route'], 'oauth/')
                )) {
                // Can continue as normal
            } else {
                require(ROOT_PATH . '/core/includes/maintenance.php');
                die();
            }
        } else {
            // Display notice to admin stating maintenance mode is enabled
            $smarty->assign('MAINTENANCE_ENABLED', $language->get('admin', 'maintenance_enabled'));
        }
    }

    // Webhooks
    $hook_array = [];
    if (Util::isModuleEnabled('Discord Integration')) {
        $cache->setCache('hooks');
        if ($cache->isCached('hooks')) {
            $hook_array = $cache->retrieve('hooks');
        } else {
            $hooks = DB::getInstance()->get('hooks', ['id', '<>', 0])->results();
            if (count($hooks)) {
                foreach ($hooks as $hook) {
                    if ($hook->action != 1 && $hook->action != 2) {
                        continue;
                    }

                    // TODO: more extendable webhook system, #2676
                    if ($hook->action == 2 && !class_exists(DiscordHook::class)) {
                        continue;
                    }

                    $hook_array[] = [
                        'id' => $hook->id,
                        'url' => Output::getClean($hook->url),
                        'action' => $hook->action == 1
                            ? [WebHook::class, 'execute']
                            : [DiscordHook::class, 'execute'],
                        'events' => json_decode($hook->events, true)
                    ];
                }
                $cache->store('hooks', $hook_array);
            }
        }
    }
    EventHandler::registerWebhooks($hook_array);

    // Get IP
    $ip = HttpUtils::getRemoteAddress();

    // Perform tasks if the user is logged in
    if ($user->isLoggedIn()) {
        Debugging::setCanViewDetailedError($user->hasPermission('admincp.errors'));
        Debugging::setCanGenerateDebugLink($user->hasPermission('admincp.core.debugging'));

        // Ensure a user is not banned
        if ($user->data()->isbanned == 1) {
            $user->logout();
            Session::flash('home_error', $language->get('user', 'you_have_been_banned'));
            Redirect::to(URL::build('/'));
        }

        // Is the IP address banned?
        $ip_bans = DB::getInstance()->get('ip_bans', ['ip', $ip])->results();
        if (count($ip_bans)) {
            $user->logout();
            Session::flash('home_error', $language->get('user', 'you_have_been_banned'));
            Redirect::to(URL::build('/'));
        }

        // Update user last IP and last online
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $user->update([
                'last_online' => date('U'),
                'lastip' => $ip
            ]);
        } else {
            $user->update([
                'last_online' => date('U')
            ]);
        }

        // Insert it into the logs
        $user_ip_logged = DB::getInstance()->get('users_ips', ['ip', $ip])->results();
        if (!count($user_ip_logged)) {
            // Create the entry now
            DB::getInstance()->insert('users_ips', [
                'user_id' => $user->data()->id,
                'ip' => $ip
            ]);
        } else {
            if (count($user_ip_logged) > 1) {
                foreach ($user_ip_logged as $user_ip) {
                    // Check to see if it's been logged by the current user
                    if ($user_ip->user_id == $user->data()->id) {
                        // Already logged for this user
                        $already_logged = true;
                        break;
                    }
                }

                if (!isset($already_logged)) {
                    // Not yet logged, do so now
                    DB::getInstance()->insert('users_ips', [
                        'user_id' => $user->data()->id,
                        'ip' => $ip
                    ]);
                }
            } else {
                // Does the entry already belong to the current user?
                if ($user_ip_logged[0]->user_id != $user->data()->id) {
                    DB::getInstance()->insert('users_ips', [
                        'user_id' => $user->data()->id,
                        'ip' => $ip
                    ]);
                }
            }
        }

        // Does their group have TFA forced?
        foreach ($user->getGroups() as $group) {
            if ($group->force_tfa) {
                $forced = true;
                break;
            }
        }

        if (isset($forced) && $forced) {
            // Do they have TFA configured?
            if (!$user->data()->tfa_enabled && rtrim($_GET['route'], '/') != '/logout') {
                if (!str_contains($_SERVER['REQUEST_URI'], 'do=enable_tfa') && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    Session::put('force_tfa_alert', $language->get('admin', 'force_tfa_alert'));
                    Redirect::to(URL::build('/user/settings', 'do=enable_tfa'));
                }
            }
        }

        $user_integrations = [];
        foreach ($user->getIntegrations() as $integrationUser) {
            $user_integrations[$integrationUser->getIntegration()->getName()] = [
                'username' => Output::getClean($integrationUser->data()->username),
                'identifier' => Output::getClean($integrationUser->data()->identifier)
            ];
        }

        // Basic user variables
        $smarty->assign('LOGGED_IN_USER', [
            'username' => $user->getDisplayname(true),
            'nickname' => $user->getDisplayname(),
            'profile' => $user->getProfileURL(),
            'panel_profile' => URL::build('/panel/user/' . urlencode($user->data()->id) . '-' . urlencode($user->data()->username)),
            'username_style' => $user->getGroupStyle(),
            'user_title' => Output::getClean($user->data()->user_title),
            'avatar' => $user->getAvatar(),
            'integrations' => $user_integrations
        ]);

        // Panel access?
        if ($user->canViewStaffCP()) {
            $smarty->assign([
                'PANEL_LINK' => URL::build('/panel'),
                'PANEL' => $language->get('moderator', 'staff_cp')
            ]);
        }
    } else {
        // Perform tasks for guests
        if (!$_SESSION['checked'] || (isset($_SESSION['checked']) && $_SESSION['checked'] <= strtotime('-5 minutes'))) {
            $already_online = DB::getInstance()->get('online_guests', ['ip', $ip])->results();

            $date = date('U');

            if (count($already_online)) {
                DB::getInstance()->update('online_guests', $already_online[0]->id, ['last_seen' => $date]);
            } else {
                DB::getInstance()->insert('online_guests', ['ip' => $ip, 'last_seen' => $date]);
            }

            $_SESSION['checked'] = $date;
        }

        // Auto language enabled?
        if (Util::getSetting('auto_language_detection')) {
            $smarty->assign('AUTO_LANGUAGE', true);
        }
    }

    // Dark mode
    $cache->setCache('template_settings');
    $darkMode = $cache->isCached('darkMode') ? $cache->retrieve('darkMode') : '0';
    if ($user->isLoggedIn()) {
        $darkMode = $user->data()->night_mode !== null ? $user->data()->night_mode : $darkMode;
    } else {
        if (Cookie::exists('night_mode')) {
            $darkMode = Cookie::get('night_mode');
        }
    }

    define('DARK_MODE', $darkMode);
}
