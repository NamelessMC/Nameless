<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel templates page
 */

if(!$user->handlePanelPageLoad('admincp.styles.templates')) {
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
    $templates = $queries->getWhere('templates', ['id', '<>', 0]);

    // Get all active templates
    $active_templates = $queries->getWhere('templates', ['enabled', '=', 1]);

    $current_template = $template;

    $templates_template = [];

    $loaded_templates = [];

    foreach ($templates as $item) {
        // Prevent the white screen error and delete template with duplicate name
        if (in_array($item->name, $loaded_templates)) {
            $queries->delete('templates', ['id', '=', $item->id]);
            continue;
        } else $loaded_templates[] = $item->name;

        $template_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($item->name), 'template.php']);

        if (file_exists($template_path))
            require($template_path);
        else {
            $queries->delete('templates', ['id', '=', $item->id]);
            continue;
        }

        $templates_template[] = [
            'name' => Output::getClean($item->name),
            'version' => Output::getClean($template->getVersion()),
            'author' => $template->getAuthor(),
            'author_x' => str_replace('{x}', $template->getAuthor(), $language->get('admin', 'author_x')),
            'version_mismatch' => (($template->getNamelessVersion() != NAMELESS_VERSION) ? str_replace(['{x}', '{y}'], [Output::getClean($template->getNamelessVersion()), NAMELESS_VERSION], $language->get('admin', 'template_outdated')) : false),
            'enabled' => $item->enabled,
            'default_warning' => (Output::getClean($item->name) == 'Default') ? $language->get('admin', 'template_not_supported') : null,
            'activate_link' => (($item->enabled) ? null : URL::build('/panel/core/templates/', 'action=activate&template=' . Output::getClean($item->id))),
            'delete_link' => ((!$user->hasPermission('admincp.styles.templates.edit') || $item->id == 1 || $item->enabled) ? null : URL::build('/panel/core/templates/', 'action=delete&template=' . Output::getClean($item->id))),
            'default' => $item->is_default,
            'deactivate_link' => (($item->enabled && count($active_templates) > 1 && !$item->is_default) ? URL::build('/panel/core/templates/', 'action=deactivate&template=' . Output::getClean($item->id)) : null),
            'default_link' => (($item->enabled && !$item->is_default) ? URL::build('/panel/core/templates/', 'action=make_default&template=' . Output::getClean($item->id)) : null),
            'edit_link' => ($user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($item->id)) : null),
            'settings_link' => ($template->getSettings() && $user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/core/templates/', 'action=settings&template=' . Output::getClean($item->id)) : null)
        ];
    }

    $template = $current_template;

    // Get templates from Nameless website
    $cache->setCache('all_templates');
    if ($cache->isCached('all_templates')) {
        $all_templates = $cache->retrieve('all_templates');
    } else {
        $all_templates = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/frontend_templates');

        $all_templates_query = curl_exec($ch);

        if (curl_error($ch)) {
            $all_templates_error = curl_error($ch);
        }

        curl_close($ch);

        if (isset($all_templates_error)) {
            $smarty->assign('WEBSITE_TEMPLATES_ERROR', $all_templates_error);
        } else {
            $all_templates_query = json_decode($all_templates_query);
            $timeago = new TimeAgo(TIMEZONE);

            foreach ($all_templates_query as $item) {
                $all_templates[] = [
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
                    $exists = $queries->getWhere('templates', ['name', '=', htmlspecialchars($folders[count($folders) - 1])]);
                    if (!count($exists) && file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php')) {
                        $template = null;
                        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php');

                        if ($template instanceof TemplateBase) {
                            // No, add it now
                            $queries->create('templates', [
                                'name' => htmlspecialchars($folders[count($folders) - 1])
                            ]);
                        }
                    }
                }

                Session::flash('admin_templates', $language->get('admin', 'templates_installed_successfully'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates'));
            die();

        case 'activate':
            if (Token::check()) {
                // Activate a template
                // Ensure it exists
                $template = $queries->getWhere('templates', ['id', '=', $_GET['template']]);
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/templates/'));
                    die();
                }
                $name = str_replace(['../', '/', '..'], '', $template[0]->name);

                if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php')) {
                    $id = $template[0]->id;
                    $template = null;

                    require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php');

                    if ($template instanceof TemplateBase) {
                        // Activate the template
                        $queries->update('templates', $id, [
                            'enabled' => 1
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
            die();

        case 'deactivate':
            if (Token::check()) {
                // Deactivate a template
                // Ensure it exists
                $template = $queries->getWhere('templates', ['id', '=', $_GET['template']]);
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/templates/'));
                    die();
                }

                $template = $template[0]->id;

                // Deactivate the template
                $queries->update('templates', $template, [
                    'enabled' => 0
                ]);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'template_deactivated'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates'));
            die();

        case 'delete':
            if (!isset($_GET['template'])) {
                Redirect::to('/panel/core/templates');
                die();
            }

            if (Token::check()) {
                $item = $_GET['template'];

                try {
                    // Ensure template is not default or active
                    $template = $queries->getWhere('templates', ['id', '=', $item]);
                    if (count($template)) {
                        $template = $template[0];
                        if ($template->name == 'DefaultRevamp' || $template->id == 1 || $template->enabled == 1 || $template->is_default == 1) {
                            Redirect::to(URL::build('/panel/core/templates'));
                            die();
                        }

                        $item = $template->name;
                    } else {
                        Redirect::to(URL::build('/panel/core/templates'));
                        die();
                    }

                    if (!Util::recursiveRemoveDirectory(ROOT_PATH . '/custom/templates/' . $item))
                        Session::flash('admin_templates_error', $language->get('admin', 'unable_to_delete_template'));
                    else
                        Session::flash('admin_templates', $language->get('admin', 'template_deleted_successfully'));

                    // Delete from database
                    $queries->delete('templates', ['name', '=', $item]);
                } catch (Exception $e) {
                    Session::flash('admin_templates_error', $e->getMessage());
                }
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates'));
            die();

        case 'make_default':
            if (Token::check()) {
                // Make a template default
                // Ensure it exists
                $new_default = $queries->getWhere('templates', ['id', '=', $_GET['template']]);
                if (!count($new_default)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/core/templates/'));
                    die();
                } else {
                    $new_default_template = $new_default[0]->name;
                    $new_default = $new_default[0]->id;
                }

                // Get current default template
                $current_default = $queries->getWhere('templates', ['is_default', '=', 1]);
                if (count($current_default)) {
                    $current_default = $current_default[0]->id;
                    // No longer default
                    $queries->update('templates', $current_default, [
                        'is_default' => 0
                    ]);
                }

                // Make selected template default
                $queries->update('templates', $new_default, [
                    'is_default' => 1
                ]);

                // Cache
                $cache->setCache('templatecache');
                $cache->store('default', $new_default_template);

                // Session
                Session::flash('admin_templates', str_replace('{x}', Output::getClean($new_default_template), $language->get('admin', 'default_template_set')));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/core/templates/'));
            die();

        case 'settings':
            // Editing template settings
            if (!$user->hasPermission('admincp.styles.templates.edit')) {
                Redirect::to(URL::build('/panel/core/templates'));
                die();
            }

            $current_template = $template;

            // Get the template
            $template_query = $queries->getWhere('templates', ['id', '=', $_GET['template']]);
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
                die();
            }

            require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $template_query->name) . DIRECTORY_SEPARATOR . 'template.php');

            if ($template && $template instanceof TemplateBase) {
                if ($template->getSettings()) {
                    require_once($template->getSettings());

                    $smarty->assign([
                        'EDITING_TEMPLATE' => str_replace('{x}', Output::getClean($template_query->name), $language->get('admin', 'editing_template_x')),
                        'BACK' => $language->get('general', 'back'),
                        'BACK_LINK' => URL::build('/panel/core/templates'),
                        'PERMISSIONS' => $language->get('admin', 'permissions'),
                        'PERMISSIONS_LINK' => $user->hasPermission('admincp.groups') ? URL::build('/panel/core/templates/', 'template=' . Output::getClean($template_query->id) . '&action=permissions') : null,
                    ]);

                    $template_file = 'core/template_settings.tpl';
                } else {
                    Redirect::to(URL::build('/panel/core/templates'));
                    die();
                }
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
                die();
            }

            $template = $current_template;

            break;

        case 'permissions':
            // Template permissions
            if (!$user->hasPermission('admincp.groups')) {
                Redirect::to(URL::build('/panel/core/templates'));
                die();
            }

            // Get the template
            $template_query = $queries->getWhere('templates', ['id', '=', $_GET['template']]);
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
                die();
            }

            // Get groups
            $groups = $queries->getWhere('groups', ['id', '<>', 0]);

            // Handle input
            if (Input::exists()) {
                if (Token::check()) {
                    // Guest template permissions
                    $can_use_template = Input::get('perm-use-0');

                    if (!($can_use_template)) $can_use_template = 0;

                    $perm_exists = 0;

                    $perm_query = $queries->getWhere('groups_templates', ['template_id', '=', $template_query->id]);
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
                            $queries->update('groups_templates', $update_id, [
                                'can_use_template' => $can_use_template
                            ]);
                        } else { // Permission doesn't exist, create
                            $queries->create('groups_templates', [
                                'group_id' => 0,
                                'template_id' => $template_query->id,
                                'can_use_template' => $can_use_template,
                            ]);
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    // Group template permissions
                    foreach ($groups as $group) {
                        $can_use_template = Input::get('perm-use-' . $group->id);

                        if (!($can_use_template)) $can_use_template = 0;

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
                                $queries->update('groups_templates', $update_id, [
                                    'can_use_template' => $can_use_template,
                                ]);
                            } else { // Permission doesn't exist, create
                                $queries->create('groups_templates', [
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
            $guest_query = DB::getInstance()->selectQuery('SELECT 0 AS id, can_use_template FROM nl2_groups_templates WHERE group_id = 0 AND template_id = ?', [$template_query->id])->results();
            $group_query = DB::getInstance()->selectQuery('SELECT id, `name`, can_use_template FROM nl2_groups A LEFT JOIN (SELECT group_id, can_use_template FROM nl2_groups_templates WHERE template_id = ?) B ON A.id = B.group_id ORDER BY `order` ASC', [$template_query->id])->results();

            $smarty->assign([
                'EDITING_TEMPLATE' => str_replace('{x}', Output::getClean($template_query->name), $language->get('admin', 'editing_template_x')),
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
                die();
            }
            // Get the template
            $template_query = $queries->getWhere('templates', ['id', '=', $_GET['template']]);
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/core/templates'));
                die();
            }

            if ($_GET['template'] == 1) {
                $smarty->assign('DEFAULT_TEMPLATE_WARNING', $language->get('admin', 'warning_editing_default_template'));
            }

            if (!isset($_GET['file']) && !isset($_GET['dir'])) {
                // Get all files
                // Build path to template folder
                $template_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name)]);
                $files = scandir($template_path);

                $template_files = [];
                $template_dirs = [];

                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && (is_dir($template_path . DIRECTORY_SEPARATOR . $file) || pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js' || pathinfo($file, PATHINFO_EXTENSION) == 'conf')) {
                        if (!is_dir($template_path . DIRECTORY_SEPARATOR . $file))
                            $template_files[] = [
                                'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&file=' . htmlspecialchars($file)),
                                'name' => Output::getClean($file)
                            ];
                        else
                            $template_dirs[] = [
                                'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . htmlspecialchars($file)),
                                'name' => Output::getClean($file)
                            ];
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
                    'PERMISSIONS_LINK' => $user->hasPermission('admincp.groups') ? URL::build('/panel/core/templates/', 'template=' . Output::getClean($template_query->id) . '&action=permissions') : null,
                ]);

                $template_file = 'core/templates_list_files.tpl';
            } else if (isset($_GET['dir']) && !isset($_GET['file'])) {
                // List files in dir
                $realdir = realpath(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), htmlspecialchars($_GET['dir'])]));
                $dir = ltrim(explode('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_query->name, $realdir)[1], '/');

                if (!isset($dir) || !is_dir(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir]))) {
                    Redirect::to(URL::build('/panel/core/templates'));
                    die();
                }

                $template_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir]);
                $files = scandir($template_path);

                $template_files = [];
                $template_dirs = [];

                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && (is_dir($template_path . DIRECTORY_SEPARATOR . $file) || pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js' || pathinfo($file, PATHINFO_EXTENSION) == 'conf')) {
                        if (!is_dir($template_path . DIRECTORY_SEPARATOR . $file))
                            $template_files[] = [
                                'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . htmlspecialchars($dir) . '&file=' . htmlspecialchars($file)),
                                'name' => Output::getClean($file)
                            ];
                        else
                            $template_dirs[] = [
                                'link' => URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . htmlspecialchars($dir) . DIRECTORY_SEPARATOR . htmlspecialchars($file)),
                                'name' => Output::getClean($file)
                            ];
                    }
                }

                // Get back link
                $dirs = explode('/', $_GET['dir']);
                if (count($dirs) > 1) {
                    unset($dirs[count($dirs) - 1]);
                    $new_dir = implode('/', $dirs);
                    $back_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id) . '&dir=' . $new_dir);
                } else {
                    $back_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($template_query->id));
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
            } else if (isset($_GET['file'])) {
                $file = basename(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), htmlspecialchars($_GET['file'])]));

                if (isset($_GET['dir'])) {
                    $realdir = realpath(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), htmlspecialchars($_GET['dir'])]));
                    $dir = ltrim(explode('custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_query->name, $realdir)[1], '/');

                    if (!isset($dir) || !is_dir(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir]))) {
                        Redirect::to(URL::build('/panel/core/templates'));
                        die();
                    }

                    $file_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $dir, $file]);
                } else
                    $file_path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', htmlspecialchars($template_query->name), $file]);

                if (!file_exists($file_path) || !(pathinfo($file, PATHINFO_EXTENSION) == 'tpl' || pathinfo($file, PATHINFO_EXTENSION) == 'css' || pathinfo($file, PATHINFO_EXTENSION) == 'js'|| pathinfo($file, PATHINFO_EXTENSION) == 'conf')) {
                    Redirect::to(URL::build('/panel/core/templates'));
                    die();
                }

                if (pathinfo($file, PATHINFO_EXTENSION) == 'tpl')
                    $file_type = 'smarty';
                else if (pathinfo($file, PATHINFO_EXTENSION) == 'css')
                    $file_type = 'css';
                else if (pathinfo($file, PATHINFO_EXTENSION) == 'js')
                    $file_type = 'javascript';
                else if (pathinfo($file, PATHINFO_EXTENSION) == 'conf')
                    $file_type = 'properties';

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
                            if (isset($_GET['dir']))
                                Redirect::to(URL::build('/panel/core/templates/', 'action=edit&template=' . $_GET['template'] . '&dir=' . Output::getClean($_GET['dir']) . '&file=' . Output::getClean($_GET['file'])));
                            else
                                Redirect::to(URL::build('/panel/core/templates/', 'action=edit&template=' . $_GET['template'] . '&file=' . Output::getClean($_GET['file'])));
                            die();
                        } else {
                            // No write permission
                            $errors = [$language->get('admin', 'cant_write_to_template')];
                        }
                    } else {
                        // Invalid token
                        $errors = [$language->get('general', 'invalid_token')];
                    }
                }

                if (isset($_GET['dir']))
                    $cancel_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($_GET['template']) . '&dir=' . Output::getClean($_GET['dir']));
                else
                    $cancel_link = URL::build('/panel/core/templates/', 'action=edit&template=' . Output::getClean($_GET['template']));

                if (isset($_GET['dir']))
                    $template_path = Output::getClean($_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file']);
                else
                    $template_path = Output::getClean($_GET['file']);

                $smarty->assign([
                    'EDITING_FILE' => str_replace(['{x}', '{y}'], [$template_path, Output::getClean($template_query->name)], $language->get('admin', 'editing_template_file_in_template')),
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

            $smarty->assign([
                'EDITING_TEMPLATE' => str_replace('{x}', Output::getClean($template_query->name), $language->get('admin', 'editing_template_x'))
            ]);

            break;

        default:
            Redirect::to(URL::build('/panel/core/templates'));
            die();
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_templates'))
    $success = Session::flash('admin_templates');

if (Session::exists('admin_templates_error'))
    $errors = [Session::flash('admin_templates_error')];

if (isset($success))
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);

if (isset($errors) && count($errors))
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'LAYOUT' => $language->get('admin', 'layout'),
    'TEMPLATES' => $language->get('admin', 'templates'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
