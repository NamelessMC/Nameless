<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel modules page
 */

if(!$user->handlePanelPageLoad('admincp.modules')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'modules');
define('PANEL_PAGE', 'modules');
$page_title = $language->get('admin', 'modules');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(!isset($_GET['action'])){
    // Get all modules
    $modules = $queries->getWhere('modules', array('id', '<>', 0));
    $enabled_modules = Module::getModules();

    $template_array = array();

    foreach($modules as $item){
        $exists = false;
        foreach($enabled_modules as $enabled_item){
            if($enabled_item->getName() == $item->name){
                $exists = true;
                $module = $enabled_item;
                break;
            }
        }

        if(!$exists){
            if(!file_exists(ROOT_PATH . '/modules/' . $item->name . '/init.php'))
                continue;

            require_once(ROOT_PATH . '/modules/' . $item->name . '/init.php');
        }

        $template_array[] = array(
            'name' => Output::getClean($module->getName()),
            'version' => Output::getClean($module->getVersion()),
            'nameless_version' => Output::getClean($module->getNamelessVersion()),
            'author' => Output::getPurified($module->getAuthor()),
            'author_x' => str_replace('{x}', Output::getPurified($module->getAuthor()), $language->get('admin', 'author_x')),
            'version_mismatch' => (($module->getNamelessVersion() != NAMELESS_VERSION) ? str_replace(array('{x}', '{y}'), array(Output::getClean($module->getNamelessVersion()), NAMELESS_VERSION), $language->get('admin', 'module_outdated')) : false),
            'disable_link' => (($module->getName() != 'Core' && $item->enabled) ? URL::build('/panel/core/modules/', 'action=disable&m=' . Output::getClean($item->id)) : null),
            'enable_link' => (($module->getName() != 'Core' && !$item->enabled) ? URL::build('/panel/core/modules/', 'action=enable&m=' . Output::getClean($item->id)) : null),
            'enabled' => $item->enabled
        );
    }

    // Get templates from Nameless website
    $cache->setCache('all_templates');
    if($cache->isCached('all_modules')){
        $all_modules = $cache->retrieve('all_modules');

    } else {
        $all_modules = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/resources_modules');

        $all_modules_query = curl_exec($ch);

        if(curl_error($ch)){
            $all_modules_error = curl_error($ch);
        }

        curl_close($ch);

        if(isset($all_modules_error)){
            $smarty->assign('WEBSITE_MODULES_ERROR', $all_modules_error);

        } else {
            $all_modules_query = json_decode($all_modules_query);
            $timeago = new Timeago(TIMEZONE);

            foreach($all_modules_query as $item){
                $all_modules[] = array(
                    'name' => Output::getClean($item->name),
                    'description' => Output::getPurified($item->description),
                    'description_short' => Util::truncate(Output::getPurified($item->description)),
                    'author' => Output::getClean($item->author),
                    'author_x' => str_replace('{x}', Output::getClean($item->author), $language->get('admin', 'author_x')),
                    'contributors' => Output::getClean($item->contributors),
                    'created' => $timeago->inWords(date('d M Y, H:i', $item->created), $language->getTimeLanguage()),
                    'created_full' => date('d M Y, H:i', $item->created),
                    'updated' => $timeago->inWords(date('d M Y, H:i', $item->updated), $language->getTimeLanguage()),
                    'updated_full' => date('d M Y, H:i', $item->updated),
                    'url' => Output::getClean($item->url),
                    'latest_version' => Output::getClean($item->latest_version),
                    'rating' => Output::getClean($item->rating),
                    'downloads' => Output::getClean($item->downloads),
                    'views' => Output::getClean($item->views),
                    'rating_full' => str_replace('{x}', Output::getClean($item->rating * 2) . '/100', $language->get('admin', 'rating_x')),
                    'downloads_full' => str_replace('{x}', Output::getClean($item->downloads), $language->get('admin', 'downloads_x')),
                    'views_full' => str_replace('{x}', Output::getClean($item->views), $language->get('admin', 'views_x'))
                );
            }

            $cache->store('all_modules', $all_modules, 3600);
        }

    }

    if(count($all_modules)){
        if(count($all_modules) > 3){
            $rand_keys = array_rand($all_modules, 3);
            $all_modules = array($all_modules[$rand_keys[0]], $all_modules[$rand_keys[1]], $all_modules[$rand_keys[2]]);
        }
    }

    $smarty->assign(array(
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
    ));

} else {
    if($_GET['action'] == 'enable'){
        // Enable a module
        if(!isset($_GET['m']) || !is_numeric($_GET['m']) || $_GET['m'] == 1) die('Invalid module!');

        if (Token::check($_POST['token'])) {
            // Get module name
            $name = $queries->getWhere('modules', array('id', '=', $_GET['m']));
            if(!count($name)){
                Redirect::to(URL::build('/panel/modules'));
                die();
            }

            $name = Output::getClean($name[0]->name);

            // Ensure module is valid
            if(!file_exists(ROOT_PATH . '/modules/' . $name . '/init.php')){
                Redirect::to(URL::build('/panel/modules'));
                die();
            }

            $module = null;

            require_once(ROOT_PATH . '/modules/' . $name . '/init.php');

            if($module instanceof Module){
                // Cache
                $cache->setCache('modulescache');
                $modules = array();

                $order = Module::determineModuleOrder();

                foreach ($order['modules'] as $key => $item) {
                    $modules[] = array(
                        'name' => $item,
                        'priority' => $key
                    );
                }

                // Store
                $cache->store('enabled_modules', $modules);

                // OK to enable
                $module->onEnable();

                if (!in_array($module->getName(), $order['failed'])) {
                    $queries->update('modules', $_GET['m'], array(
                        'enabled' => 1
                    ));
                    Session::flash('admin_modules', $language->get('admin', 'module_enabled'));
                } else {
                    $enabled_modules = array();

                    foreach ($modules as $item) {
                        $enabled_modules[] = $item['name'];
                    }
                    foreach ($module->getLoadAfter() as $item) {
                        if (!in_array($item, $enabled_modules)) {
                            Session::flash('admin_modules_error', str_replace('{x}', Output::getClean($item), $language->get('admin', 'unable_to_enable_module_dependencies')));
                            Redirect::to(URL::build('/panel/core/modules'));
                            die();
                        }
                    }
                    Session::flash('admin_modules_error', $language->get('admin', 'unable_to_enable_module'));
                }

            } else
                Session::flash('admin_modules_error', $language->get('admin', 'unable_to_enable_module'));

        } else Session::flash('admin_modules_error', $language->get('general', 'invalid_token'));

        Redirect::to(URL::build('/panel/core/modules'));
        die();

    } else if($_GET['action'] == 'disable'){
        // Disable a module
        if(!isset($_GET['m']) || !is_numeric($_GET['m']) || $_GET['m'] == 1) die('Invalid module!');

        if (Token::check($_POST['token'])) {
            // Get module name
            $name = $queries->getWhere('modules', array('id', '=', $_GET['m']));
            $name = Output::getClean($name[0]->name);

            foreach (Module::getModules() as $item) {
                if (in_array($name, $item->getLoadAfter())) {
                    // Unable to disable module
                    Session::flash('admin_modules_error', str_replace('{x}', Output::getClean($item->getName()), $language->get('admin', 'unable_to_disable_module')));
                    Redirect::to(URL::build('/panel/core/modules'));
                    die();
                }
            }

            $queries->update('modules', $_GET['m'], array(
                'enabled' => 0
            ));

            // Cache
            $cache->setCache('modulescache');
            $modules = array();

            $order = Module::determineModuleOrder();

            foreach ($order['modules'] as $key => $item) {
                if ($item != $name) {
                    $modules[] = array(
                        'name' => $item,
                        'priority' => $key
                    );
                }
            }

            // Store
            $cache->store('enabled_modules', $modules);

            if(file_exists(ROOT_PATH . '/modules/' . $name . '/init.php')){
                require_once(ROOT_PATH . '/modules/' . $name . '/init.php');
                $module->onDisable();
            }

            Session::flash('admin_modules', $language->get('admin', 'module_disabled'));

        } else Session::flash('admin_modules_error', $language->get('general', 'invalid_token'));

        Redirect::to(URL::build('/panel/core/modules'));
        die();

    } else if($_GET['action'] == 'install'){
        // Install any new modules
        $directories = glob(ROOT_PATH . '/modules/*' , GLOB_ONLYDIR);

        define('MODULE_INSTALL', true);

        foreach($directories as $directory){
            $folders = explode('/', $directory);

            if(file_exists(ROOT_PATH . '/modules/' . $folders[count($folders) - 1] . '/init.php')){
                // Is it already in the database?
                $exists = $queries->getWhere('modules', array('name', '=', Output::getClean($folders[count($folders) - 1])));

                if(!count($exists)){
                    $module = null;

                    // No, add it now
                    require_once(ROOT_PATH . '/modules/' . $folders[count($folders) - 1] . '/init.php');

                    if($module instanceof Module){
                        $queries->create('modules', array(
                            'name' => Output::getClean($folders[count($folders) - 1])
                        ));
                        $module->onInstall();
                    }
                }
            }
        }

        Session::flash('admin_modules', $language->get('admin', 'modules_installed_successfully'));
        Redirect::to(URL::build('/panel/core/modules'));
        die();
    }
}

if(Session::exists('admin_modules'))
    $success = Session::flash('admin_modules');

if(Session::exists('admin_modules_error'))
    $errors = array(Session::flash('admin_modules_error'));

if(isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if(isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'MODULES' => $language->get('admin', 'modules'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/modules.tpl', $smarty);
