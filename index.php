<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Main index file
 */

// Uncomment to enable debugging
// define('DEBUGGING', 1);

header('X-Frame-Options: SAMEORIGIN');

if ((!defined('DEBUGGING') || !DEBUGGING) && (getenv('NAMELESS_DEBUGGING') || isset($_SERVER['NAMELESS_DEBUGGING']))) {
    define('DEBUGGING', true);
}

if (defined('DEBUGGING') && DEBUGGING) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}

// Ensure PHP version >= 7.4
if (PHP_VERSION_ID < 70400) {
    die('NamelessMC is not compatible with PHP versions older than 7.4, you are running PHP ' . PHP_VERSION);
}

// Start page load timer
define('PAGE_START_TIME', microtime(true));

if (!is_dir(__DIR__ . '/vendor') || !is_dir(__DIR__ . '/core/assets/vendor')) {
    die(
        "Your installation is missing the 'vendor' or 'core/assets/vendor' directory.<br>
        <br>
        Please use the 'nameless-deps-dist.zip' file, not a source code zip file.<br>
        <br>
        If you are a developer, please refer to the instructions in CONTRIBUTING.md"
    );
}

const PATH = '/';
const ROOT_PATH = __DIR__;

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_GET['route']) && $_GET['route'] == '/rewrite_test') {
    require_once('rewrite_test.php');
    die;
}

if (!Config::exists() || Config::get('core.installed') !== true) {
    if (is_file(ROOT_PATH . '/install.php')) {
        Redirect::to('install.php');
    } else {
        die('Config does not exist, but neither does the installer');
    }
}

$page = 'Home';

if (!ini_get('upload_tmp_dir')) {
    $tmp_dir = sys_get_temp_dir();
} else {
    $tmp_dir = ini_get('upload_tmp_dir');
}

if (HttpUtils::getProtocol() === 'https') {
    ini_set('session.cookie_secure', 'On');
}

ini_set('session.cookie_httponly', 1);
ini_set('open_basedir', ROOT_PATH . PATH_SEPARATOR . $tmp_dir . PATH_SEPARATOR . '/proc/stat');

// Get the directory the user is trying to access
$directory = $_SERVER['REQUEST_URI'];
$directories = explode('/', $directory);
$lim = count($directories);

// Start initialising the page
require(ROOT_PATH . '/core/init.php');

// Get page to load from URL
if (!isset($_GET['route']) || $_GET['route'] == '/') {
    if ((!isset($_GET['route']) || ($_GET['route'] != '/')) && count($directories) > 1) {
        require(ROOT_PATH . '/404.php');
    } else {
        // Homepage
        $homepage = $pages->getPageByURL(Settings::get('home_type'));
        if ($homepage != null) {
            $pages->setActivePage($homepage);
            require(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', $homepage['module'], $homepage['file']]));
        } else {
            $pages->setActivePage($pages->getPageByURL('/'));
            require(ROOT_PATH . '/modules/Core/pages/index.php');
        }
    }
    die;
}

$route = rtrim(strtok($_GET['route'], '?'), '/');

$all_pages = $pages->returnPages();

if (array_key_exists($route, $all_pages)) {
    $active_page = $all_pages[$route];
    $pages->setActivePage($active_page);
    if (isset($active_page['custom'])) {
        require(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Core', 'pages', 'custom.php']));
        die;
    }

    if ($active_page['controllerBased']) {
        $languageKey = "{$active_page['moduleSafeName']}Language";

        if (str_contains($route, 'queries')) {
            /** @var \NamelessMC\Framework\Queries\Query */
            $controller = $container->make($active_page['file']);
            $controller->handle();
        } elseif (str_contains($route, 'panel')) {
            /** @var \NamelessMC\Framework\Pages\PanelPage */
            $controller = $container->make($active_page['file'], [
                $languageKey => $container->get($languageKey),
            ]);

            if (!$user->handlePanelPageLoad($controller->permission())) {
                require_once(ROOT_PATH . '/403.php');
                die();
            }

            require_once(ROOT_PATH . '/core/templates/backend_init.php');

            $controller->render();
            Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);
            define('PAGE', 'panel');
            define('PARENT_PAGE', $active_page['moduleSafeName']);
            define('PANEL_PAGE', $controller->pageName());
            $smarty->assign(['TITLE' => $active_page['name'], 'PAGE' => $controller->pageName()]);
            $template->onPageLoad();
            require(ROOT_PATH . '/core/templates/panel_navbar.php');
            return $template->displayTemplate($controller->viewFile(), $smarty);
        } else {
            require_once(ROOT_PATH . '/core/templates/frontend_init.php');    
            /** @var \NamelessMC\Framework\Pages\Page */
            $controller = $container->make($active_page['file'], [
                'templatePagination' => $template_pagination,
                 // TODO: php-di does not use parameter names to resolve entries (it uses the typed class names),
                 // so if this is not provided it will use the root \Language class definition,
                 // which is the Core language. we can possibly make modules use #[Inject]
                $languageKey => $container->get($languageKey),
            ]);
            $controller->render();
            Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);
            define('PAGE', $controller->pageName());
            $smarty->assign('TITLE', $active_page['name']);
            $template->onPageLoad();
            require(ROOT_PATH . '/core/templates/navbar.php');
            require(ROOT_PATH . '/core/templates/footer.php');
            return $template->displayTemplate($controller->viewFile(), $smarty);
        }
    } else {
        $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', $active_page['module'], $active_page['file']]);

        if (file_exists($path)) {
            require($path);
            die;
        }
    }
} else {
    // Use recursion to check - might have URL parameters in path
    $path_array = explode('/', $route);

    for ($i = count($path_array) - 2; $i > 0; $i--) {
        $new_path = '/';
        for ($n = 1; $n <= $i; $n++) {
            $new_path .= $path_array[$n] . '/';
        }

        $new_path = rtrim($new_path, '/');

        if (array_key_exists($new_path, $all_pages)) {
            $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', $all_pages[$new_path]['module'], $all_pages[$new_path]['file']]);

            if (file_exists($path)) {
                $pages->setActivePage($all_pages[$new_path]);
                require($path);
                die;
            }
        }
    }
}

require(ROOT_PATH . '/404.php');
