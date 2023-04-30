<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel - panel templates page
 */

if (!$user->handlePanelPageLoad('admincp.styles.panel_templates')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'layout';
const PANEL_PAGE = 'panel_templates';
$page_title = $language->get('admin', 'panel_templates');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action'])) {
    // Get all templates
    $templates = DB::getInstance()->get('panel_templates', ['id', '<>', 0])->results();

    // Get all active templates
    $active_templates = DB::getInstance()->get('panel_templates', ['enabled', true])->results();

    $current_template = $template;

    $templates_template = [];

    foreach ($templates as $item) {
        $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'panel_templates', Output::getClean($item->name), 'template.php']);

        if (file_exists($template_path)) {
            require($template_path);
        } else {
            DB::getInstance()->delete('panel_templates', ['id', $item->id]);
            continue;
        }

        $templates_template[] = [
            'name' => Output::getClean($item->name),
            'version' => Output::getClean($template->getVersion()),
            'author' => $template->getAuthor(),
            'author_x' => $language->get('admin', 'author_x', ['author' => $template->getAuthor()]),
            'version_mismatch' => !Util::isCompatible($template->getNamelessVersion(), NAMELESS_VERSION) ? $language->get('admin', 'template_outdated', [
                'intendedVersion' => Output::getClean($template->getNamelessVersion()),
                'actualVersion' => NAMELESS_VERSION,
            ]) : false,
            'third_party' => $template->getName() !== 'Default' ? $language->get('admin', 'panel_template_third_party', [
                'name' => Text::bold($template->getName()),
            ]) : false,
            'enabled' => $item->enabled,
            'activate_link' => (($item->enabled) ? null : URL::build('/panel/core/panel_templates/', 'action=activate&template=' . urlencode($item->id))),
            'delete_link' => (($item->id == 1 || $item->enabled) ? null : URL::build('/panel/core/panel_templates/', 'action=delete&template=' . urlencode($item->id))),
            'default' => $item->is_default,
            'deactivate_link' => (($item->enabled && count($active_templates) > 1 && !$item->is_default) ? URL::build('/panel/core/panel_templates/', 'action=deactivate&template=' . urlencode($item->id)) : null),
            'default_link' => (($item->enabled && !$item->is_default) ? URL::build('/panel/core/panel_templates/', 'action=make_default&template=' .urlencode($item->id)) : null)
        ];
    }

    $template = $current_template;

    $smarty->assign([
        'WARNING' => $language->get('admin', 'warning'),
        'ACTIVATE' => $language->get('admin', 'activate'),
        'DEACTIVATE' => $language->get('admin', 'deactivate'),
        'DELETE' => $language->get('admin', 'delete'),
        'CONFIRM_DELETE_TEMPLATE' => $language->get('admin', 'confirm_delete_template'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'ACTIVE' => $language->get('admin', 'active'),
        'DEFAULT' => $language->get('admin', 'default'),
        'MAKE_DEFAULT' => $language->get('admin', 'make_default'),
        'TEMPLATE_LIST' => $templates_template,
        'INSTALL_TEMPLATE' => $language->get('admin', 'install'),
        'INSTALL_TEMPLATE_LINK' => URL::build('/panel/core/panel_templates/', 'action=install'),
        'CLEAR_CACHE' => $language->get('admin', 'clear_cache'),
        'CLEAR_CACHE_LINK' => URL::build('/panel/core/panel_templates/', 'action=clear_cache'),
        'VIEW' => $language->get('general', 'view'),
        'TEMPLATE' => $language->get('admin', 'template'),
        'STATS' => $language->get('admin', 'stats'),
        'ACTIONS' => $language->get('general', 'actions')
    ]);

    $template_file = 'core/panel_templates.tpl';

} else {
    switch ($_GET['action']) {
        case 'install':
            if (Token::check()) {
                // Install new template
                // Scan template directory for new templates
                $directories = glob(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'panel_templates' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
                foreach ($directories as $directory) {
                    $folders = explode(DIRECTORY_SEPARATOR, $directory);

                    // Is it already in the database?
                    $exists = DB::getInstance()->get('panel_templates', ['name', $folders[count($folders) - 1]])->results();
                    if (!count($exists) && file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'panel_templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php')) {
                        $template = null;
                        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'panel_templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php');

                        /** @phpstan-ignore-next-line */
                        if ($template instanceof TemplateBase) {
                            // No, add it now
                            DB::getInstance()->insert('panel_templates', [
                                'name' => $folders[count($folders) - 1]
                            ]);
                        }
                    }
                }

                Session::flash('admin_templates', $language->get('admin', 'templates_installed_successfully'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/panel_templates'));

        case 'activate':
            if (Token::check()) {
                // Activate a template
                // Ensure it exists
                $template = DB::getInstance()->get('panel_templates', ['id', $_GET['template']])->results();
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/panel_templates'));
                }
                $name = str_replace(['../', '/', '..'], '', $template[0]->name);

                if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'panel_templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php')) {
                    $id = $template[0]->id;
                    $template = null;

                    require(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'panel_templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php');

                    /** @phpstan-ignore-next-line */
                    if ($template instanceof TemplateBase) {
                        // Activate the template
                        DB::getInstance()->update('panel_templates', $id, [
                            'enabled' => true,
                        ]);

                        // Session
                        Session::flash('admin_templates', $language->get('admin', 'template_activated'));

                    } else {
                        // Session
                        Session::flash('admin_templates_error', $language->get('admin', 'unable_to_enable_template'));
                    }
                }
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/panel_templates'));

        case 'deactivate':
            if (Token::check()) {
                // Deactivate a template
                // Ensure it exists
                $template = DB::getInstance()->get('panel_templates', ['id', $_GET['template']])->results();
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/panel_templates'));
                }

                $template = $template[0]->id;

                // Deactivate the template
                DB::getInstance()->update('panel_templates', $template, [
                    'enabled' => false,
                ]);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'template_deactivated'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/panel_templates'));

        case 'delete':
            if (!isset($_GET['template'])) {
                Redirect::to('/panel/core/panel_templates');
            }

            if (Token::check()) {
                $item = $_GET['template'];

                try {
                    // Ensure template is not default or active
                    $template = DB::getInstance()->get('panel_templates', ['id', $item])->results();
                    if (count($template)) {
                        $template = $template[0];
                        if ($template->name == 'Default' || $template->id == 1 || $template->enabled == 1 || $template->is_default == 1) {
                            Redirect::to(URL::build('/panel/core/panel_templates'));
                        }

                        $item = $template->name;
                    } else {
                        Redirect::to(URL::build('/panel/core/panel_templates'));
                    }

                    if (!Util::recursiveRemoveDirectory(ROOT_PATH . '/custom/panel_templates/' . $item)) {
                        Session::flash('admin_templates_error', $language->get('admin', 'unable_to_delete_template'));
                    } else {
                        Session::flash('admin_templates', $language->get('admin', 'template_deleted_successfully'));
                    }

                    // Delete from database
                    DB::getInstance()->delete('templates', ['name', $item]);
                } catch (Exception $e) {
                    Session::flash('admin_templates_error', $e->getMessage());
                }
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/panel_templates'));

        case 'make_default':
            if (Token::check()) {
                // Make a template default
                // Ensure it exists
                $new_default = DB::getInstance()->get('panel_templates', ['id', $_GET['template']])->results();
                if (!count($new_default)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/panel_templates'));
                }

                $new_default_template = $new_default[0]->name;
                $new_default = $new_default[0]->id;

                // Get current default template
                $current_default = DB::getInstance()->get('panel_templates', ['is_default', true])->results();
                if (count($current_default)) {
                    $current_default = $current_default[0]->id;
                    // No longer default
                    DB::getInstance()->update('panel_templates', $current_default, [
                        'is_default' => false,
                    ]);
                }

                // Make selected template default
                DB::getInstance()->update('panel_templates', $new_default, [
                    'is_default' => true,
                ]);

                // Cache
                $cache->setCache('templatecache');
                $cache->store('panel_default', $new_default_template);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'default_template_set', ['template' => Output::getClean($new_default_template)]));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/panel_templates'));

        case 'clear_cache':
            if (Token::check()) {
                $smarty->clearAllCache();
                Session::flash('admin_templates', $language->get('admin', 'cache_cleared'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/panel_templates'));

        default:
            Redirect::to(URL::build('/panel/core/panel_templates'));
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_templates')) {
    $success = Session::flash('admin_templates');
}

if (Session::exists('admin_templates_error')) {
    $errors = [Session::flash('admin_templates_error')];
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
    'LAYOUT' => $language->get('admin', 'layout'),
    'PANEL_TEMPLATES' => $language->get('admin', 'panel_templates'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
