<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel templates page
 */

if (!$user->handlePanelPageLoad('admincp.styles.templates')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'layout';
const PANEL_PAGE = 'template';
$page_title = $language->get('admin', 'templates');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action'])) {
    // Get all templates
    $templates = DB::getInstance()->get('templates', ['id', '<>', 0])->results();

    // Get all active templates
    $active_templates = DB::getInstance()->get('templates', ['enabled', true])->results();

    $current_template = $template;

    $templates_template = [];

    $loaded_templates = [];

    foreach ($templates as $item) {
        // Prevent the white screen error and delete template with duplicate name
        if (in_array($item->name, $loaded_templates)) {
            DB::getInstance()->delete('templates', ['id', $item->id]);
            continue;
        }

        $loaded_templates[] = $item->name;

        $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($item->name), 'template.php']);

        if (file_exists($template_path)) {
            require($template_path);
        } else {
            DB::getInstance()->delete('templates', ['id', $item->id]);
            continue;
        }

        $templates_template[] = [
            'name' => Output::getClean($item->name),
            'version' => Output::getClean($template->getVersion()),
            'author' => $template->getAuthor(),
            'author_x' => $language->get('admin', 'author_x', ['author' => $template->getAuthor()]),
            'version_mismatch' => !Util::isCompatible($template->getNamelessVersion(), NAMELESS_VERSION) ? $language->get('admin', 'template_outdated', [
                'intendedVersion' => Text::bold(Output::getClean($template->getNamelessVersion())),
                'actualVersion' => Text::bold(NAMELESS_VERSION)
            ]) : false,
            'enabled' => $item->enabled,
            'default_warning' => (Output::getClean($item->name) == 'Default') ? $language->get('admin', 'template_not_supported') : null,
            'activate_link' => (($item->enabled) ? null : URL::build('/panel/core/templates/', 'action=activate&template=' . urlencode($item->id))),
            'delete_link' => ((!$user->hasPermission('admincp.styles.templates.edit') || $item->id == 1 || $item->enabled) ? null : URL::build('/panel/core/templates/', 'action=delete&template=' . urlencode($item->id))),
            'default' => $item->is_default,
            'deactivate_link' => (($item->enabled && count($active_templates) > 1 && !$item->is_default) ? URL::build('/panel/core/templates/', 'action=deactivate&template=' . urlencode($item->id)) : null),
            'default_link' => (($item->enabled && !$item->is_default) ? URL::build('/panel/core/templates/', 'action=make_default&template=' . urlencode($item->id)) : null),
            'edit_link' => ($user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($item->id)) : null),
            'settings_link' => ($template->getSettings() && $user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/core/templates/', 'action=settings&template=' . urlencode($item->id)) : null)
        ];
    }

    $template = $current_template;

    // Get templates from Nameless website
    $cache->setCache('all_templates');
    if ($cache->isCached('all_templates')) {
        $all_templates = $cache->retrieve('all_templates');
    } else {
        $all_templates = [];

        $all_templates_query = HttpClient::get('https://namelessmc.com/frontend_templates');

        if ($all_templates_query->hasError()) {
            $all_templates_error = $all_templates_query->getError();
        }

        if (isset($all_templates_error)) {
            $smarty->assign('WEBSITE_TEMPLATES_ERROR', $all_templates_error);
        } else {
            $all_templates_query = $all_templates_query->json();
            $timeago = new TimeAgo(TIMEZONE);

            foreach ($all_templates_query as $item) {
                $all_templates[] = [
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

            $cache->store('all_templates', $all_templates, 3600);
        }
    }

    if (count($all_templates)) {
        if (count($all_templates) > 3) {
            $rand_keys = array_rand($all_templates, 3);
            $all_templates = [$all_templates[$rand_keys[0]], $all_templates[$rand_keys[1]], $all_templates[$rand_keys[2]]];
        }
    }

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
        'EDIT' => $language->get('general', 'edit'),
        'SETTINGS' => $language->get('admin', 'settings'),
        'TEMPLATE_LIST' => $templates_template,
        'INSTALL_TEMPLATE' => $language->get('admin', 'install'),
        'INSTALL_TEMPLATE_LINK' => URL::build('/panel/core/templates/', 'action=install'),
        'FIND_TEMPLATES' => $language->get('admin', 'find_templates'),
        'WEBSITE_TEMPLATES' => $all_templates,
        'VIEW_ALL_TEMPLATES' => $language->get('admin', 'view_all_templates'),
        'VIEW_ALL_TEMPLATES_LINK' => 'https://namelessmc.com/resources/category/2-namelessmc-v2-templates/',
        'UNABLE_TO_RETRIEVE_TEMPLATES' => $language->get('admin', 'unable_to_retrieve_templates'),
        'VIEW' => $language->get('general', 'view'),
        'TEMPLATE' => $language->get('admin', 'template'),
        'STATS' => $language->get('admin', 'stats'),
        'ACTIONS' => $language->get('general', 'actions')
    ]);

    $template_file = 'core/templates.tpl';
} else {
    switch ($_GET['action']) {
        case 'install':
            if (Token::check()) {
                // Install new template
                // Scan template directory for new templates
                $directories = glob(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
                foreach ($directories as $directory) {
                    $folders = explode(DIRECTORY_SEPARATOR, $directory);

                    // Is it already in the database?
                    $exists = DB::getInstance()->get('templates', ['name', $folders[count($folders) - 1]])->results();
                    if (!count($exists) && file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php')) {
                        $template = null;
                        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php');

                        /** @phpstan-ignore-next-line */
                        if ($template instanceof TemplateBase) {
                            // No, add it now
                            DB::getInstance()->insert('templates', [
                                'name' => $folders[count($folders) - 1]
                            ]);
                        }
                    }
                }

                Session::flash('admin_templates', $language->get('admin', 'templates_installed_successfully'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates'));

        case 'activate':
            if (Token::check()) {
                // Activate a template
                // Ensure it exists
                $template = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/templates/'));
                }
                $name = str_replace(['../', '/', '..'], '', $template[0]->name);

                if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php')) {
                    $id = $template[0]->id;
                    $template = null;
                    require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php');

                    /** @phpstan-ignore-next-line */
                    if ($template instanceof TemplateBase) {
                        // Activate the template
                        DB::getInstance()->update('templates', $id, [
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

            Redirect::to(URL::build('/panel/core/templates/'));

        case 'deactivate':
            if (Token::check()) {
                // Deactivate a template
                // Ensure it exists
                $template = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/templates/'));
                }

                $template = $template[0]->id;

                // Deactivate the template
                DB::getInstance()->update('templates', $template, [
                    'enabled' => false,
                ]);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'template_deactivated'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates'));

        case 'delete':
            if (!isset($_GET['template'])) {
                Redirect::to('/panel/core/templates');
            }

            if (Token::check()) {
                $item = $_GET['template'];

                try {
                    // Ensure template is not default or active
                    $template = DB::getInstance()->get('templates', ['id', $item])->results();
                    if (count($template)) {
                        $template = $template[0];
                        if ($template->name == 'DefaultRevamp' || $template->id == 1 || $template->enabled == 1 || $template->is_default == 1) {
                            Redirect::to(URL::build('/panel/core/templates'));
                        }

                        $item = $template->name;
                    } else {
                        Redirect::to(URL::build('/panel/core/templates'));
                    }

                    if (!Util::recursiveRemoveDirectory(ROOT_PATH . '/custom/templates/' . $item)) {
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

            Redirect::to(URL::build('/panel/core/templates'));

        case 'make_default':
            if (Token::check()) {
                // Make a template default
                // Ensure it exists
                $new_default = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
                if (!count($new_default)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/templates/'));
                }

                $new_default_template = $new_default[0]->name;
                $new_default = $new_default[0]->id;

                // Get current default template
                $current_default = DB::getInstance()->get('templates', ['is_default', true])->results();
                if (count($current_default)) {
                    $current_default = $current_default[0]->id;
                    // No longer default
                    DB::getInstance()->update('templates', $current_default, [
                        'is_default' => false,
                    ]);
                }

                // Make selected template default
                DB::getInstance()->update('templates', $new_default, [
                    'is_default' => true,
                ]);

                // Cache
                $cache->setCache('templatecache');
                $cache->store('default', $new_default_template);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'default_template_set', ['template' => Output::getClean($new_default_template)]));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates/'));

        case 'settings':
            // Editing template settings
            if (!$user->hasPermission('admincp.styles.templates.edit')) {
                Redirect::to(URL::build('/panel/core/templates'));
            }

            $current_template = $template;

            // Get the template
            $template_query = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
            }

            require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $template_query->name) . DIRECTORY_SEPARATOR . 'template.php');

            if ($template instanceof TemplateBase) {
                if ($template->getSettings()) {
                    require_once($template->getSettings());

                    $smarty->assign([
                        'EDITING_TEMPLATE' => $language->get('admin', 'editing_template_x', [
                            'template' => Text::bold(Output::getClean($template_query->name))
                        ]),
                        'BACK' => $language->get('general', 'back'),
                        'BACK_LINK' => URL::build('/panel/core/templates'),
                        'PERMISSIONS' => $language->get('admin', 'permissions'),
                        'PERMISSIONS_LINK' => $user->hasPermission('admincp.groups') ? URL::build('/panel/core/templates/', 'template=' . urlencode($template_query->id) . '&action=permissions') : null,
                    ]);

                    $template_file = 'core/template_settings.tpl';
                } else {
                    Redirect::to(URL::build('/panel/core/templates'));
                }
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
            }

            $template = $current_template;

            break;

        case 'permissions':
            // Template permissions
            if (!$user->hasPermission('admincp.groups')) {
                Redirect::to(URL::build('/panel/core/templates'));
            }

            // Get the template
            $template_query = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
            }

            // Handle input
            if (Input::exists()) {
                if (Token::check()) {
                    // Guest template permissions
                    $can_use_template = Input::get('perm-use-0');

                    if (!($can_use_template)) {
                        $can_use_template = 0;
                    }

                    $perm_exists = 0;

                    $perm_query = DB::getInstance()->get('groups_templates', ['template_id', $template_query->id])->results();
                    if (count($perm_query)) {
                        foreach ($perm_query as $query) {
                            if ($query->group_id == 0) {
                                $perm_exists = 1;
                                $update_id = $query->id;
                                break;
                            }
                        }
                    }

                    try {
                        if ($perm_exists != 0) { // Permission already exists, update
                            // Update the permission
                            DB::getInstance()->update('groups_templates', $update_id, [
                                'can_use_template' => $can_use_template
                            ]);
                        } else { // Permission doesn't exist, create
                            DB::getInstance()->insert('groups_templates', [
                                'group_id' => 0,
                                'template_id' => $template_query->id,
                                'can_use_template' => $can_use_template,
                            ]);
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    // Group template permissions
                    foreach (Group::all() as $group) {
                        $can_use_template = Input::get('perm-use-' . $group->id);

                        if (!($can_use_template)) {
                            $can_use_template = 0;
                        }

                        $perm_exists = 0;

                        if (count($perm_query)) {
                            foreach ($perm_query as $query) {
                                if ($query->group_id == $group->id) {
                                    $perm_exists = 1;
                                    $update_id = $query->id;
                                    break;
                                }
                            }
                        }

                        try {
                            if ($perm_exists != 0) { // Permission already exists, update
                                // Update the permission
                                DB::getInstance()->update('groups_templates', $update_id, [
                                    'can_use_template' => $can_use_template,
                                ]);
                            } else { // Permission doesn't exist, create
                                DB::getInstance()->insert('groups_templates', [
                                    'group_id' => $group->id,
                                    'template_id' => $template_query->id,
                                    'can_use_template' => $can_use_template,
                                ]);
                            }
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                        }
                    }

                    $success = $language->get('admin', 'successfully_updated');
                } else {
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            // Get permissions
            $guest_query = DB::getInstance()->query('SELECT 0 AS id, can_use_template FROM nl2_groups_templates WHERE group_id = 0 AND template_id = ?', [$template_query->id])->results();
            $group_query = DB::getInstance()->query('SELECT id, `name`, can_use_template FROM nl2_groups A LEFT JOIN (SELECT group_id, can_use_template FROM nl2_groups_templates WHERE template_id = ?) B ON A.id = B.group_id ORDER BY `order` ASC', [$template_query->id])->results();

            $smarty->assign([
                'EDITING_TEMPLATE' => $language->get('admin', 'editing_template_x', [
                    'template' => Text::bold(Output::getClean($template_query->name))
                ]),
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/core/templates'),
                'PERMISSIONS' => $language->get('admin', 'permissions'),
                'GUESTS' => $language->get('user', 'guests'),
                'GUEST_PERMISSIONS' => (count($guest_query) ? $guest_query[0] : []),
                'GROUP_PERMISSIONS' => $group_query,
                'GROUP' => $language->get('admin', 'group'),
                'CAN_USE_TEMPLATE' => $language->get('admin', 'can_use_template'),
                'SELECT_ALL' => $language->get('admin', 'select_all'),
                'DESELECT_ALL' => $language->get('admin', 'deselect_all')
            ]);

            $template_file = 'core/template_permissions.tpl';

            break;

        case 'edit':
            // Editing template
            if (!$user->hasPermission('admincp.styles.templates.edit')) {
                Redirect::to(URL::build('/panel/core/templates'));
            }
            // Get the template
            $template_query = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
            }

            if ($_GET['template'] == 1) {
                $smarty->assign('DEFAULT_TEMPLATE_WARNING', $language->get('admin', 'warning_editing_default_template'));
            }

            if (!isset($_GET['file']) && !isset($_GET['dir'])) {
                // Get all files
                // Build path to template folder
                $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name)]);
                $files = scandir($template_path);

                $template_files = [];
                $template_dirs = [];

                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && (is_dir($template_path . DIRECTORY_SEPARATOR . $file) || pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js' || pathinfo($file, PATHINFO_EXTENSION) == 'conf')) {
                        if (!is_dir($template_path . DIRECTORY_SEPARATOR . $file)) {
                            $template_files[] = [
                                'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($template_query->id) . '&file=' . urlencode($file)),
                                'name' => Output::getClean($file)
                            ];
                        } else {
                            $template_dirs[] = [
                                'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($template_query->id) . '&dir=' . urlencode($file)),
                                'name' => Output::getClean($file)
                            ];
                        }
                    }
                }

                $smarty->assign([
                    'BACK' => $language->get('general', 'back'),
                    'BACK_LINK' => URL::build('/panel/core/templates/'),
                    'TEMPLATE_FILES' => $template_files,
                    'TEMPLATE_DIRS' => $template_dirs,
                    'VIEW' => $language->get('general', 'view'),
                    'EDIT' => $language->get('general', 'edit'),
                    'PERMISSIONS' => $language->get('admin', 'permissions'),
                    'PERMISSIONS_LINK' => $user->hasPermission('admincp.groups') ? URL::build('/panel/core/templates/', 'template=' . urlencode($template_query->id) . '&action=permissions') : null,
                ]);

                $template_file = 'core/templates_list_files.tpl';
            } else {
                if (isset($_GET['dir']) && !isset($_GET['file'])) {
                    // List files in dir
                    $realdir = realpath(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), Output::getClean($_GET['dir'])]));
                    $dir = ltrim(explode('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_query->name, $realdir)[1], '/');

                    if (!is_dir(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), $dir]))) {
                        Redirect::to(URL::build('/panel/core/templates'));
                    }

                    $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), $dir]);
                    $files = scandir($template_path);

                    $template_files = [];
                    $template_dirs = [];

                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..' && (is_dir($template_path . DIRECTORY_SEPARATOR . $file) || pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js' || pathinfo($file, PATHINFO_EXTENSION) == 'conf')) {
                            if (!is_dir($template_path . DIRECTORY_SEPARATOR . $file)) {
                                $template_files[] = [
                                    'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($template_query->id) . '&dir=' . urlencode($dir) . '&file=' . urlencode($file)),
                                    'name' => Output::getClean($file)
                                ];
                            } else {
                                $template_dirs[] = [
                                    'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($template_query->id) . '&dir=' . urlencode($dir) . DIRECTORY_SEPARATOR . urlencode($file)),
                                    'name' => Output::getClean($file)
                                ];
                            }
                        }
                    }

                    // Get back link
                    $dirs = explode('/', $_GET['dir']);
                    if (count($dirs) > 1) {
                        unset($dirs[count($dirs) - 1]);
                        $new_dir = implode('/', $dirs);
                        $back_link = URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($template_query->id) . '&dir=' . urlencode($new_dir));
                    } else {
                        $back_link = URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($template_query->id));
                    }

                    $smarty->assign([
                        'BACK' => $language->get('general', 'back'),
                        'BACK_LINK' => $back_link,
                        'TEMPLATE_FILES' => $template_files,
                        'TEMPLATE_DIRS' => $template_dirs,
                        'VIEW' => $language->get('general', 'view'),
                        'EDIT' => $language->get('general', 'edit'),
                        'PERMISSIONS' => $language->get('admin', 'permissions'),
                        'PERMISSIONS_LINK' => $user->hasPermission('admincp.groups') ? '/panel/core/templates/?template=' . Output::getClean($template_query->id) . '&action=permissions' : null,
                    ]);

                    $template_file = 'core/templates_list_files.tpl';
                } else {
                    if (isset($_GET['file'])) {
                        $file = basename(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), Output::getClean($_GET['file'])]));

                        if (isset($_GET['dir'])) {
                            $realdir = realpath(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), Output::getClean($_GET['dir'])]));
                            $dir = ltrim(explode('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_query->name, $realdir)[1], '/');

                            if (!is_dir(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), $dir]))) {
                                Redirect::to(URL::build('/panel/core/templates'));
                            }

                            $file_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), $dir, $file]);
                        } else {
                            $file_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($template_query->name), $file]);
                        }

                        $file_type = null;
                        if (file_exists($file_path)) {
                            $raw_type = pathinfo($file, PATHINFO_EXTENSION);
                            $type_map = [
                                'tpl' => 'smarty',
                                'css' => 'css',
                                'js' => 'javascript',
                                'conf' => 'properties'
                            ];
                            if (array_key_exists($raw_type, $type_map)) {
                                $file_type = $type_map[$raw_type];
                            }
                        }

                        if ($file_type === null) {
                            Redirect::to(URL::build('/panel/core/templates'));
                        }

                        // Deal with input
                        if (Input::exists()) {
                            if (Token::check()) {
                                // Valid token
                                if (is_writable($file_path)) {
                                    // Can write to template file
                                    // Write
                                    $file = fopen($file_path, 'w');
                                    fwrite($file, Input::get('code'));
                                    fclose($file);

                                    Log::getInstance()->log(Log::Action('admin/template/update'), Output::getClean($file_path));

                                    // Display session success message
                                    Session::flash('admin_templates', $language->get('admin', 'template_updated'));

                                    // Redirect to refresh page
                                    if (isset($_GET['dir'])) {
                                        Redirect::to(URL::build('/panel/core/templates/', 'action=edit&template=' . $_GET['template'] . '&dir=' . urlencode($_GET['dir']) . '&file=' . urlencode($_GET['file'])));
                                    } else {
                                        Redirect::to(URL::build('/panel/core/templates/', 'action=edit&template=' . $_GET['template'] . '&file=' . urlencode($_GET['file'])));
                                    }
                                }

// No write permission
                                $errors = [$language->get('admin', 'cant_write_to_template')];
                            } else {
                                // Invalid token
                                $errors = [$language->get('general', 'invalid_token')];
                            }
                        }

                        if (isset($_GET['dir'])) {
                            $cancel_link = URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($_GET['template']) . '&dir=' . urlencode($_GET['dir']));
                        } else {
                            $cancel_link = URL::build('/panel/core/templates/', 'action=edit&template=' . urlencode($_GET['template']));
                        }

                        if (isset($_GET['dir'])) {
                            $template_path = Output::getClean($_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file']);
                        } else {
                            $template_path = Output::getClean($_GET['file']);
                        }

                        $smarty->assign([
                            'EDITING_FILE' => $language->get('admin', 'editing_template_file_in_template', [
                                'file' => Text::bold($template_path),
                                'template' => Text::bold(Output::getClean($template_query->name)),
                            ]),
                            'CANCEL' => $language->get('general', 'cancel'),
                            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                            'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                            'YES' => $language->get('general', 'yes'),
                            'NO' => $language->get('general', 'no'),
                            'CANCEL_LINK' => $cancel_link,
                            'FILE_CONTENTS' => Output::getClean(file_get_contents($file_path)),
                            'FILE_TYPE' => $file_type
                        ]);

                        $template_file = 'core/templates_edit.tpl';
                    }
                }
            }

            $smarty->assign([
                'EDITING_TEMPLATE' => $language->get('admin', 'editing_template_x', [
                    'template' => Text::bold(Output::getClean($template_query->name))
                ]),
            ]);

            break;

        default:
            Redirect::to(URL::build('/panel/core/templates'));
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
    'TEMPLATES' => $language->get('admin', 'templates'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
