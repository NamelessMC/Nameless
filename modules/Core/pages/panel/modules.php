<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel modules page
 */

if (!$user->handlePanelPageLoad('admincp.modules')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'modules';
const PANEL_PAGE = 'modules';
$page_title = $language->get('admin', 'modules');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (!isset($_GET['action'])) {
    // Get all modules
    $modules = DB::getInstance()->get('modules', ['id', '<>', 0])->results();
    $enabled_modules = Module::getModules();

    $errors = [];
    $template_array = [];

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

            try {
                require_once ROOT_PATH . '/modules/' . $item->name . '/init.php';
            } catch (Exception $e) {
                $term = 'unable_to_load_module';

                if ($e->getMessage() === 'Translation file not found') {
                    $term = 'unable_to_load_outdated_module';
                }

                $errors[] = $language->get('admin', $term, [
                    'message' => $e->getMessage(),
                    'module' => Output::getClean($item->name),
                ]);
                continue;
            }
        }

        $template_array[] = [
            'name' => Output::getClean($module->getName()),
            'version' => Output::getClean($module->getVersion()),
            'nameless_version' => Output::getClean($module->getNamelessVersion()),
            'author' => Output::getPurified($module->getAuthor()),
            'author_x' => $language->get('admin', 'author_x', ['author' => Output::getPurified($module->getAuthor())]),
            'version_mismatch' => !Util::isCompatible($module->getNamelessVersion(), NAMELESS_VERSION) ? $language->get('admin', 'module_outdated', [
                'intendedVersion' => Text::bold(Output::getClean($module->getNamelessVersion())),
                'actualVersion' => Text::bold(NAMELESS_VERSION)
            ]) : false,
            'disable_link' => (($module->getName() != 'Core' && $item->enabled) ? URL::build('/panel/core/modules/', 'action=disable&m=' . urlencode($item->id)) : null),
            'enable_link' => (($module->getName() != 'Core' && !$item->enabled) ? URL::build('/panel/core/modules/', 'action=enable&m=' . urlencode($item->id)) : null),
            'enabled' => $item->enabled
        ];
    }

    if (count($errors)) {
        Session::put('admin_modules_errors', $errors);
    }

    // Get modules from Nameless website
    $cache->setCache('all_templates');
    if ($cache->isCached('all_modules')) {
        $all_modules = $cache->retrieve('all_modules');

    } else {
        $all_modules = [];

        $all_modules_query = HttpClient::get('https://namelessmc.com/resources_modules');

        if ($all_modules_query->hasError()) {
            $all_modules_error = $all_modules_query->getError();
        }

        if (isset($all_modules_error)) {
            $smarty->assign('WEBSITE_MODULES_ERROR', $all_modules_error);

        } else {
            $all_modules_query = json_decode($all_modules_query->contents());
            $timeago = new TimeAgo(TIMEZONE);

            foreach ($all_modules_query as $item) {
                $all_modules[] = [
                    'name' => Output::getClean($item->name),
                    'description' => Output::getPurified($item->description),
                    'description_short' => Text::truncate(Output::getPurified($item->description)),
                    'author' => Output::getClean($item->author),
                    'author_x' => $language->get('admin', 'author_x', ['author' => Output::getClean($item->author)]),
                    'updated_x' => $language->get('admin', 'updated_x', ['updatedAt' => date(DATE_FORMAT, $item->updated)]),
                    'url' => Output::getClean($item->url),
                    'latest_version' => Output::getClean($item->latest_version),
                    'rating' => Output::getClean($item->rating),
                    'downloads' => Output::getClean($item->downloads),
                    'views' => Output::getClean($item->views),
                    'rating_full' => $language->get('admin', 'rating_x', ['rating' => Output::getClean($item->rating * 2) . '/100']),
                    'downloads_full' => $language->get('admin', 'downloads_x', ['downloads' => Output::getClean($item->downloads)]),
                    'views_full' =>  $language->get('admin', 'views_x', ['views' => Output::getClean($item->views)])
                ];
            }

            $cache->store('all_modules', $all_modules, 3600);
        }

    }

    if (count($all_modules)) {
        if (count($all_modules) > 3) {
            $rand_keys = array_rand($all_modules, 3);
            $all_modules = [$all_modules[$rand_keys[0]], $all_modules[$rand_keys[1]], $all_modules[$rand_keys[2]]];
        }
    }

    $smarty->assign([
        'INSTALL_MODULE' => $language->get('admin', 'install'),
        'INSTALL_MODULE_LINK' => URL::build('/panel/core/modules/', 'action=install'),
        'AUTHOR' => $language->get('admin', 'author'),
        'ENABLE' => $language->get('admin', 'enable'),
        'DISABLE' => $language->get('admin', 'disable'),
        'MODULE_LIST' => $template_array,
        'FIND_MODULES' => $language->get('admin', 'find_modules'),
        'WEBSITE_MODULES' => $all_modules,
        'VIEW_ALL_MODULES' => $language->get('admin', 'view_all_modules'),
        'VIEW_ALL_MODULES_LINK' => 'https://namelessmc.com/resources/category/1-namelessmc-modules/',
        'UNABLE_TO_RETRIEVE_MODULES' => $language->get('admin', 'unable_to_retrieve_modules'),
        'VIEW' => $language->get('general', 'view'),
        'MODULE' => $language->get('admin', 'module'),
        'STATS' => $language->get('admin', 'stats'),
        'ACTIONS' => $language->get('general', 'actions'),
        'WARNING' => $language->get('general', 'warning')
    ]);

} else {
    if ($_GET['action'] == 'enable') {
        // Enable a module
        if (!isset($_GET['m']) || !is_numeric($_GET['m']) || $_GET['m'] == 1) {
            die('Invalid module!');
        }

        if (Token::check($_POST['token'])) {
            // Get module name
            $name = DB::getInstance()->get('modules', ['id', $_GET['m']])->results();
            if (!count($name)) {
                Redirect::to(URL::build('/panel/modules'));
            }

            $name = Output::getClean($name[0]->name);

            // Ensure module is valid
            if (!file_exists(ROOT_PATH . '/modules/' . $name . '/init.php')) {
                Redirect::to(URL::build('/panel/modules'));
            }

            $module = null;

            require_once(ROOT_PATH . '/modules/' . $name . '/init.php');

            /** @phpstan-ignore-next-line */
            if ($module instanceof Module) {
                // Cache
                $cache->setCache('modulescache');
                $modules = [];

                $order = Module::determineModuleOrder();

                foreach ($order['modules'] as $key => $item) {
                    $modules[] = [
                        'name' => $item,
                        'priority' => $key
                    ];
                }

                if (!in_array($module->getName(), $order['failed'])) {
                    // OK to enable
                    $module->onEnable();

                    // Store
                    $cache->store('enabled_modules', $modules);

                    DB::getInstance()->update('modules', $_GET['m'], [
                        'enabled' => true,
                    ]);
                    Session::flash('admin_modules', $language->get('admin', 'module_enabled'));
                } else {
                    $enabled_modules = [];

                    foreach ($modules as $item) {
                        $enabled_modules[] = $item['name'];
                    }
                    foreach ($module->getLoadAfter() as $item) {
                        if (!in_array($item, $enabled_modules)) {
                            Session::flash('admin_modules_error', $language->get('admin', 'unable_to_enable_module_dependencies', ['module' =>  Output::getClean($item)]));
                            Redirect::to(URL::build('/panel/core/modules'));
                        }
                    }
                    Session::flash('admin_modules_error', $language->get('admin', 'unable_to_enable_module'));
                }

            } else {
                Session::flash('admin_modules_error', $language->get('admin', 'unable_to_enable_module'));
            }

        } else {
            Session::flash('admin_modules_error', $language->get('general', 'invalid_token'));
        }

        Redirect::to(URL::build('/panel/core/modules'));
    }

    if ($_GET['action'] == 'disable') {
        // Disable a module
        if (!isset($_GET['m']) || !is_numeric($_GET['m']) || $_GET['m'] == 1) {
            die('Invalid module!');
        }

        if (Token::check($_POST['token'])) {
            // Get module name
            $name = DB::getInstance()->get('modules', ['id', $_GET['m']])->results();
            $name = Output::getClean($name[0]->name);

            foreach (Module::getModules() as $item) {
                if (in_array($name, $item->getLoadAfter())) {
                    // Unable to disable module
                    Session::flash('admin_modules_error', $language->get('admin', 'unable_to_disable_module', ['module' => Output::getClean($item->getName())]));
                    Redirect::to(URL::build('/panel/core/modules'));
                }
            }

            DB::getInstance()->update('modules', $_GET['m'], [
                'enabled' => false,
            ]);

            // Cache
            $cache->setCache('modulescache');
            $modules = [];

            $order = Module::determineModuleOrder();

            foreach ($order['modules'] as $key => $item) {
                if ($item != $name) {
                    $modules[] = [
                        'name' => $item,
                        'priority' => $key
                    ];
                }
            }

            // Store
            $cache->store('enabled_modules', $modules);

            if (file_exists(ROOT_PATH . '/modules/' . $name . '/init.php')) {
                require_once(ROOT_PATH . '/modules/' . $name . '/init.php');
                $module->onDisable();
            }

            Session::flash('admin_modules', $language->get('admin', 'module_disabled'));

        } else {
            Session::flash('admin_modules_error', $language->get('general', 'invalid_token'));
        }

        Redirect::to(URL::build('/panel/core/modules'));
    }

    if ($_GET['action'] == 'install') {
        if (Token::check()) {
            // Install any new modules
            $directories = glob(ROOT_PATH . '/modules/*', GLOB_ONLYDIR);

            define('MODULE_INSTALL', true);
            $errors = [];

            foreach ($directories as $directory) {
                $folders = explode('/', $directory);

                if (file_exists(ROOT_PATH . '/modules/' . $folders[count($folders) - 1] . '/init.php')) {
                    $exists = DB::getInstance()->get('modules', ['name', $folders[count($folders) - 1]])->results();

                    if (!count($exists)) {
                        $module = null;

                        try {
                            require_once(ROOT_PATH . '/modules/' . $folders[count($folders) - 1] . '/init.php');

                            /** @phpstan-ignore-next-line */
                            if ($module instanceof Module) {
                                DB::getInstance()->insert('modules', [
                                    'name' => $folders[count($folders) - 1]
                                ]);
                                $module->onInstall();
                            }
                        } catch (Exception $e) {
                            $term = 'unable_to_load_module';

                            if ($e->getMessage() == 'Translation file not found') {
                                $term = 'unable_to_load_outdated_module';
                            }

                            $errors[] = $language->get('admin', $term, [
                                'message' => $e->getMessage(),
                                'module' => Output::getClean($folders[count($folders) - 1]),
                            ]);
                        }
                    }
                }
            }

            if (count($errors)) {
                Session::put('admin_modules_errors', $errors);
            } else {
                Session::flash('admin_modules', $language->get('admin', 'modules_installed_successfully'));
            }

        } else {
            Session::flash('admin_modules_error', $language->get('general', 'invalid_token'));
        }

        Redirect::to(URL::build('/panel/core/modules'));
    }
}

if (Session::exists('admin_modules')) {
    $success = Session::flash('admin_modules');
}

if (Session::exists('admin_modules_error')) {
    $errors = [Session::flash('admin_modules_error')];
}

if (Session::exists('admin_modules_errors')) {
    $errors = Session::flash('admin_modules_errors');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'MODULES' => $language->get('admin', 'modules'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/modules.tpl', $smarty);
