<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel announcements page
 */

if (!$user->handlePanelPageLoad('admincp.core.announcements')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'announcements';
const PANEL_PAGE = 'announcements';
$page_title = $language->get('admin', 'announcements');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action'])) {
    // View all announcements

    $announcements_list = [];
    foreach ($announcements->getAll() as $announcement) {
        $announcements_list[] = [
            $announcement,
            'pages' => Announcements::getPagesCsv($announcement->pages)
        ];
    }

    if (count($announcements_list) >= 1) {
        $smarty->assign([
            'ALL_ANNOUNCEMENTS' => $announcements_list
        ]);
    }

    $smarty->assign([
        'NONE' => $language->get('general', 'none'),
        'NO_ANNOUNCEMENTS' => $language->get('admin', 'no_announcements'),
        'ANNOUCEMENTS_INFO' => $language->get('admin', 'announcement_info'),
        'NEW_LINK' => URL::build('/panel/core/announcements', 'action=new'),
        'NEW' => $language->get('admin', 'new_announcement'),
        'ACTIONS' => $language->get('general', 'actions'),
        'EDIT_LINK' => URL::build('/panel/core/announcements', 'action=edit&id='),
        'DELETE_LINK' => URL::build('/panel/core/announcements', 'action=delete'),
        'REORDER_DRAG_URL' => URL::build('/panel/core/announcements')
    ]);

    $template_file = 'core/announcements.tpl';
} else {
    switch ($_GET['action']) {
        case 'new':
            // Create new hook
            if (Input::exists()) {
                $errors = [];
                if (Token::check()) {
                    // Validate input
                    $validation = Validate::check($_POST, [
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
                        $all_groups = [];
                        if (Input::get('perm-view-0')) {
                            $all_groups[] = '0';
                        }
                        foreach (Group::all() as $group) {
                            if (Input::get('perm-view-' . $group->id)) {
                                $all_groups[] = $group->id;
                            }
                        }
                        $pages = [];
                        foreach (Input::get('pages') as $page) {
                            $pages[] = $page;
                        }
                        if (!$announcements->create($user, $pages, $all_groups, Input::get('text_colour'), Input::get('background_colour'), Input::get('icon'), Input::get('closable'), Input::get('header'), Input::get('message'), Input::get('order'))) {
                            Session::flash('announcement_error', $language->get('admin', 'creating_announcement_failure'));
                        } else {
                            Session::flash('announcement_success', $language->get('admin', 'creating_announcement_success'));
                        }
                        Redirect::to(URL::build('/panel/core/announcements'));
                    }

                    $errors = $validation->errors();
                } else {
                    // Invalid token
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $groups = [];
            foreach (Group::all() as $group) {
                $groups[$group->id] = [
                    'id' => $group->id,
                    'name' => Output::getClean($group->name),
                    'allowed' => (isset($_POST['perm-view-' . $group->id]) && $_POST['perm-view-' . $group->id] == 1)
                ];
            }

            $smarty->assign([
                'ANNOUNCEMENT_TITLE' => $language->get('admin', 'creating_announcement'),
                'HEADER_VALUE' => ((isset($_POST['header']) && $_POST['header']) ? Output::getClean(Input::get('header')) : ''),
                'MESSAGE_VALUE' => ((isset($_POST['message']) && $_POST['message']) ? Output::getClean(Input::get('message')) : ''),
                'PAGES_VALUE' => ((isset($_POST['pages']) && is_array($_POST['pages'])) ? Input::get('pages') : []),
                'BACKGROUND_COLOUR_VALUE' => ((isset($_POST['background_colour']) && $_POST['background_colour']) ? Output::getClean(Input::get('background_colour')) : '#007BFF'),
                'TEXT_COLOUR_VALUE' => ((isset($_POST['text_colour']) && $_POST['text_colour']) ? Output::getClean(Input::get('text_colour')) : '#ffffff'),
                'ICON_VALUE' => ((isset($_POST['icon']) && $_POST['icon']) ? Output::getClean(Input::get('icon')) : ''),
                'ORDER_VALUE' => ((isset($_POST['order']) && $_POST['order']) ? Output::getClean(Input::get('order')) : 1),
                'CLOSABLE_VALUE' => ((isset($_POST['closable']) && $_POST['closable']) ? Output::getClean(Input::get('closable')) : ''),
                'GROUPS_VALUE' => $groups,
                'GUEST_PERMISSIONS' => (isset($_POST['perm-view-0']) && $_POST['perm-view-0'] == 1)
            ]);

            $template_file = 'core/announcements_form.tpl';
            break;
        case 'edit':
            // Edit hook
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                // Check the announcement ID is valid
                Redirect::to(URL::build('/panel/core/announcements'));
            }

            // Does the announcement exist?
            $announcement = Announcement::find($_GET['id']);
            if (!$announcement) {
                // No, it doesn't exist
                Redirect::to(URL::build('/panel/core/announcements'));
            }

            if (Input::exists()) {
                $errors = [];
                if (Token::check()) {
                    // Validate input
                    $validation = Validate::check($_POST, [
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
                        $all_groups = [];
                        if (Input::get('perm-view-0')) {
                            $all_groups[] = '0';
                        }
                        foreach (Group::all() as $group) {
                            if (Input::get('perm-view-' . $group->id)) {
                                $all_groups[] = $group->id;
                            }
                        }
                        $pages = [];
                        foreach (Input::get('pages') as $page) {
                            $pages[] = $page;
                        }
                        if (!$announcements->edit($announcement->id, $pages, $all_groups, Input::get('text_colour'), Input::get('background_colour'), Input::get('icon'), Input::get('closable'), Input::get('header'), Input::get('message'), Input::get('order'))) {
                            Session::flash('announcement_error', $language->get('admin', 'editing_announcement_failure'));
                        } else {
                            Session::flash('announcement_success', $language->get('admin', 'editing_announcement_success'));
                        }
                        Redirect::to(URL::build('/panel/core/announcements'));
                    }

                    $errors = $validation->errors();
                } else {
                    // Invalid token
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $announcement_pages = json_decode($announcement->pages);
            $guest_permissions = in_array('0', json_decode($announcement->groups));

            $groups = [];
            foreach (Group::all() as $group) {
                $groups[$group->id] = [
                    'name' => $group->name,
                    'id' => $group->id,
                    'allowed' => in_array($group->id, json_decode($announcement->groups))
                ];
            }

            $smarty->assign([
                'ANNOUNCEMENT_TITLE' => $language->get('admin', 'editing_announcement'),
                'HEADER_VALUE' => Output::getClean($announcement->header),
                'MESSAGE_VALUE' => Output::getClean($announcement->message),
                'PAGES_VALUE' => is_array($announcement_pages) ? $announcement_pages : [],
                'BACKGROUND_COLOUR_VALUE' => Output::getClean($announcement->background_colour),
                'TEXT_COLOUR_VALUE' => Output::getClean($announcement->text_colour),
                'ICON_VALUE' => Output::getClean($announcement->icon),
                'ORDER_VALUE' => Output::getClean($announcement->order),
                'CLOSABLE_VALUE' => Output::getClean($announcement->closable),
                'GROUPS_VALUE' => $groups,
                'GUEST_PERMISSIONS' => $guest_permissions,
            ]);

            $template_file = 'core/announcements_form.tpl';
            break;

        case 'delete':
            // Delete Announcement
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    if (isset($_POST['id'])) {
                        DB::getInstance()->delete('custom_announcements', ['id', $_POST['id']]);

                        $announcements->resetCache();
                        Session::flash('announcement_success', $language->get('admin', 'deleted_announcement_success'));
                    }
                } else {
                    Session::flash('announcement_error', $language->get('general', 'invalid_token'));
                }
            }
            die();

        case 'order':
            if (isset($_GET['announcements'])) {
                if (!Token::check()) {
                    Session::flash('announcement_error', $language->get('general', 'invalid_token'));
                    die('Invalid Token');
                }

                $announcements_list = json_decode($_GET['announcements'])->announcements;

                $i = 1;
                foreach ($announcements_list as $item) {
                    DB::getInstance()->update('custom_announcements', $item, [
                        'order' => $i
                    ]);
                    $i++;
                }
            }
            $announcements->resetCache();
            die('Complete');

        default:
            Redirect::to(URL::build('/panel/core/announcements'));
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('announcement_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('announcement_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}
if (Session::exists('announcement_error')) {
    $smarty->assign([
        'ERRORS' => [Session::flash('announcement_error')],
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}
if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PAGE' => PANEL_PAGE,
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'CONFIRM_DELETE_ANNOUNCEMENT' => $language->get('admin', 'verify_delete_announcement'),
    'ICON_INFO' => Output::getClean($language->get('admin', 'announcement_icon_instructions', [
        'faLink' => '<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" rel="noopener nofollow">Font Awesome</a>',
        'semLink' => '<a href="https://fomantic-ui.com/elements/icon.html" target="_blank" rel="noopener nofollow">Fomantic UI</a>',
    ])),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'ORDER' => $language->get('admin', 'announcement_order'),
    'HEADER' => $language->get('admin', 'header'),
    'MESSAGE' => $language->get('admin', 'message'),
    'GROUPS' => $language->get('admin', 'groups'),
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/core/announcements'),
    'PAGES' => $language->get('admin', 'pages'),
    'TEXT_COLOUR' => $language->get('admin', 'text_colour'),
    'BACKGROUND_COLOUR' => $language->get('admin', 'background_colour'),
    'ICON' => $language->get('admin', 'icon'),
    'CLOSABLE' => $language->get('admin', 'closable'),
    'PAGES_ARRAY' => Announcements::getPages($pages),
    'INFO' => $language->get('general', 'info'),
    'GUESTS' => $language->get('user', 'guests'),
    'NAME' => $language->get('admin', 'name'),
    'CAN_VIEW_ANNOUNCEMENT' => $language->get('admin', 'can_view_announcement'),
    'ANNOUNCEMENTS' => $language->get('admin', 'announcements'),
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
