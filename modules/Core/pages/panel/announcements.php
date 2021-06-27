<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel announcements page
 */

if(!$user->handlePanelPageLoad('admincp.core.announcements')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'announcements');
define('PANEL_PAGE', 'announcements');
$page_title = $language->get('admin', 'announcements');
require_once(ROOT_PATH . '/core/templates/backend_init.php');
$queries = new Queries();

if (!isset($_GET['action'])) {
    // View all announcements

    $announcements_list = array();
    foreach ($announcements->getAll() as $announcement) {
        $announcements_list[] = array(
            $announcement,
            'pages' => $announcements->getPagesCsv($announcement->pages)
        );
    }

    if (count($announcements_list) >= 1) {
        $smarty->assign(array(
            'ALL_ANNOUNCEMENTS' => $announcements_list
        ));
    }

    $smarty->assign(array(
        'NONE' => $language->get('general', 'none'),
        'NO_ANNOUNCEMENTS' => $language->get('admin', 'no_announcements'),
        'ANNOUCEMENTS_INFO' => $language->get('admin', 'announcement_info'),
        'NEW_LINK' => URL::build('/panel/core/announcements', 'action=new'),
        'NEW' => $language->get('admin', 'new_announcement'),
        'ACTIONS' => $language->get('general', 'actions'),
        'EDIT_LINK' => URL::build('/panel/core/announcements', 'action=edit&id='),
        'DELETE_LINK' => URL::build('/panel/core/announcements', 'action=delete'),
        'REORDER_DRAG_URL' => URL::build('/panel/core/announcements')
    ));

    $template_file = 'core/announcements.tpl';
} else {
    switch ($_GET['action']) {
        case 'new':
            // Create new hook
            if (Input::exists()) {
                $errors = array();
                if (Token::check()) {
                    // Validate input
                    $validate = new Validate();
                    $validation = $validate->check($_POST, [
                        'header' => [
                            Validate::REQUIRED => true
                        ],
                        'message' => [
                            Validate::REQUIRED => true
                        ],
                        'background_colour' => [
                            Validate::REQUIRED => true
                        ],
                        'text_colour' => [
                            Validate::REQUIRED => true
                        ],
                        'order' => [
                            Validate::REQUIRED => true,
                            Validate::NUMERIC => true
                        ]
                    ])->messages([
                        'header' => $language->get('admin', 'header_required'),
                        'message' => $language->get('admin', 'message_required'),
                        'background_colour' => $language->get('admin', 'background_colour_required'),
                        'text_colour' => $language->get('admin', 'text_colour_required')
                    ]);

                    if ($validation->passed()) {
                        $groups = $queries->getWhere('groups', array('id', '<>', '0'));
                        $all_groups = array();
                        if (Input::get('perm-view-0')) {
                            $all_groups[] = "0";
                        }
                        foreach ($groups as $group) {
                            if (Input::get('perm-view-' . $group->id)) {
                                $all_groups[] = $group->id;
                            }
                        }
                        $pages = array();
                        foreach (Input::get('pages') as $page) {
                            $pages[] = $page;
                        }
                        if (!$announcements->create($pages, $all_groups, Output::getClean(Input::get('text_colour')), Output::getClean(Input::get('background_colour')), Output::getClean(Input::get('icon')), Output::getClean(Input::get('closable')), Output::getClean(Input::get('header')), Output::getClean(Input::get('message')), Output::getClean(Input::get('order')))) {
                            Session::flash('announcement_error', $language->get('admin', 'creating_announcement_failure'));
                            Redirect::to(URL::build('/panel/core/announcements'));
                            die();
                            break;
                        } else {
                            Session::flash('announcement_success', $language->get('admin', 'creating_announcement_success'));
                            Redirect::to(URL::build('/panel/core/announcements'));
                            die();
                            break;
                        }
                    } else {
                        $errors = $validation->errors();
                    }
                } else {
                    // Invalid token
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $groups = DB::getInstance()->query('SELECT * FROM nl2_groups ORDER BY `order`')->results();
            $template_array = array();
            foreach ($groups as $group) {
                $template_array[Output::getClean($group->id)] = array(
                    'id' => Output::getClean($group->id),
                    'name' => Output::getClean($group->name),
                );
            }

            $smarty->assign(array(
                'ANNOUNCEMENT_TITLE' => $language->get('admin', 'creating_announcement'),
                'GROUPS' => $template_array,
            ));

            $template_file = 'core/announcements_form.tpl';
            break;
        case 'edit':
            // Edit hook
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                // Check the announcement ID is valid
                Redirect::to(URL::build('/panel/core/announcements'));
                die();
            }

            // Does the announcement exist?
            $announcement = $queries->getWhere('custom_announcements', array('id', '=', $_GET['id']));
            if (!count($announcement)) {
                // No, it doesn't exist
                Redirect::to(URL::build('/panel/core/announcements'));
                die();
            }
            $announcement = $announcement[0];

            if (Input::exists()) {
                $errors = array();
                if (Token::check()) {
                    // Validate input
                    $validate = new Validate();
                    $validation = $validate->check($_POST, [
                        'header' => [
                            Validate::REQUIRED => true
                        ],
                        'message' => [
                            Validate::REQUIRED => true
                        ],
                        'background_colour' => [
                            Validate::REQUIRED => true
                        ],
                        'text_colour' => [
                            Validate::REQUIRED => true
                        ],
                        'order' => [
                            Validate::REQUIRED => true,
                            Validate::NUMERIC => true
                        ]
                    ])->messages([
                        'header' => $language->get('admin', 'header_required'),
                        'message' => $language->get('admin', 'message_required'),
                        'background_colour' => $language->get('admin', 'background_colour_required'),
                        'text_colour' => $language->get('admin', 'text_colour_required')
                    ]);

                    if ($validation->passed()) {
                        $all_groups = array();
                        if (Input::get('perm-view-0')) {
                            $all_groups[] = "0";
                        }
                        foreach ($queries->getWhere('groups', array('id', '<>', '0')) as $group) {
                            if (Input::get('perm-view-' . $group->id)) {
                                $all_groups[] = $group->id;
                            }
                        }
                        $pages = array();
                        foreach (Input::get('pages') as $page) {
                            $pages[] = $page;
                        }
                        if (!$announcements->edit($announcement->id, $pages, $all_groups, Output::getClean(Input::get('text_colour')), Output::getClean(Input::get('background_colour')), Output::getClean(Input::get('icon')), Output::getClean(Input::get('closable')), Output::getClean(Input::get('header')), Output::getClean(Input::get('message')), Output::getClean(Input::get('order')))) {
                            Session::flash('announcement_error', $language->get('admin', 'editing_announcement_failure'));
                            Redirect::to(URL::build('/panel/core/announcements'));
                            die();
                            break;
                        } else {
                            Session::flash('announcement_success', $language->get('admin', 'editing_announcement_success'));
                            Redirect::to(URL::build('/panel/core/announcements'));
                            die();
                            break;
                        }
                    } else {
                        $errors = $validation->errors();
                    }
                } else {
                    // Invalid token
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $announcement_pages = json_decode($announcement->pages);
            $announcement->pages = is_array($announcement_pages) ? $announcement_pages : [];

            $guest_permissions = in_array("0", json_decode($announcement->groups));
            $groups = array();
            
            foreach (DB::getInstance()->query('SELECT * FROM nl2_groups ORDER BY `order`')->results() as $group) {
                $groups[$group->id] = array(
                    'name' => $group->name,
                    'id' => $group->id,
                    'allowed' => in_array($group->id, json_decode($announcement->groups))
                );
            }

            $smarty->assign(array(
                'ANNOUNCEMENT_TITLE' => $language->get('admin', 'editing_announcement'),
                'ANNOUNCEMENT' => $announcement,
                'GROUPS' => $groups,
                'GUEST_PERMISSIONS' => $guest_permissions,
            ));

            $template_file = 'core/announcements_form.tpl';
            break;

        case 'delete':
            // Delete Announcement
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    if (isset($_POST['id'])) {
                        $queries->delete('custom_announcements', array('id', '=', $_POST['id']));

                        $announcements->resetCache();
                        Session::flash('announcement_success', $language->get('admin', 'deleted_announcement_success'));
                    }
                } else {
                    Session::flash('announcement_error', $language->get('general', 'invalid_token'));
                }
            }
            die();
            break;

        case 'order':
            if (isset($_GET['announcements'])) {
                $announcements_list = json_decode($_GET['announcements'])->announcements;

                $i = 1;
                foreach ($announcements_list as $item) {
                    $queries->update('custom_announcements', $item, array(
                        '`order`' => $i
                    ));
                    $i++;
                }
            }
            $announcements->resetCache();
            die('Complete');

        default:
            Redirect::to(URL::build('/panel/core/announcements'));
            die();
            break;
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('announcement_success'))
    $smarty->assign(array(
        'SUCCESS' => Session::flash('announcement_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));
if (Session::exists('announcement_error'))
    $smarty->assign(array(
        'ERRORS' => [Session::flash('announcement_error')],
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));
if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PAGE' => PANEL_PAGE,
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'CONFIRM_DELETE_ANNOUNCEMENT' => $language->get('admin', 'verify_delete_announcement'),
    'ICON_INFO' => $language->get('admin', 'announcement_icon_instructions'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'ORDER' => $language->get('admin', 'announcement_order'),
    'HEADER' => $language->get('admin', 'header'),
    'MESSAGE' => $language->get('admin', 'message'),
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/core/announcements'),
    'PAGES' => $language->get('admin', 'pages'),
    'TEXT_COLOUR' => $language->get('admin', 'text_colour'),
    'BACKGROUND_COLOUR' => $language->get('admin', 'background_colour'),
    'ICON' => $language->get('admin', 'icon'),
    'CLOSABLE' => $language->get('admin', 'closable'),
    'PAGES_ARRAY' => $announcements->getPages($pages),
    'INFO' => $language->get('general', 'info'),
    'GUESTS' => $language->get('user', 'guests'),
    'NAME' => $language->get('admin', 'name'),
    'CAN_VIEW_ANNOUNCEMENT' => $language->get('admin', 'can_view_announcement'),
    'ANNOUNCEMENTS' => $language->get('admin', 'announcements'),
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
