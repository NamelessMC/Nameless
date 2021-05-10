<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel security page
 */

if(!$user->handlePanelPageLoad('admincp.security')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'security');
define('PANEL_PAGE', 'security');
// Define the sort column #, as for group_sync we dont show IP (since its from MC server or Discord bot)
define('SORT', (isset($_GET['view']) && $_GET['view'] == 'group_sync') ? 1 : 2);
$page_title = $language->get('admin', 'security');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['view'])) {
    $links = array();

    if ($user->hasPermission('admincp.security.acp_logins')) {
        $links[] = array(
            'link' => URL::build('/panel/security/', 'view=acp_logins'),
            'title' => $language->get('admin', 'acp_logins')
        );
    }

    if ($user->hasPermission('admincp.security.emails')) {
        $links[] = array(
            'link' => URL::build('/panel/security/', 'view=emails'),
            'title' => $language->get('admin', 'email_logs')
        );
    }

    if ($user->hasPermission('admincp.security.group_sync')) {
        $links[] = array(
            'link' => URL::build('/panel/security/', 'view=group_sync'),
            'title' => $language->get('admin', 'group_sync_logs')
        );
    }

    if ($user->hasPermission('admincp.security.template')) {
        $links[] = array(
            'link' => URL::build('/panel/security/', 'view=template_changes'),
            'title' => $language->get('admin', 'template_changes')
        );
    }

    if ($user->hasPermission('admincp.security.all')) {
        $links[] = array(
            'link' => URL::build('/panel/security/', 'view=all'),
            'title' => $language->get('admin', 'all_logs')
        );
    }

    $smarty->assign(array(
        'PLEASE_SELECT_LOGS' => $language->get('admin', 'please_select_logs'),
        'LINKS' => $links
    ));

    $template_file = 'core/security.tpl';
} else {
    switch ($_GET['view']) {
        case 'acp_logins':
            if (!$user->hasPermission('admincp.security.acp_logins')) {
                Redirect::to(URL::build('/panel/security'));
                die();
            }

            $log_title = $language->get('admin', 'acp_logins');
            $logs = $queries->orderWhere('logs', 'action = \'acp_login\'', 'time', 'DESC');

            $cols = 3;
            $col_titles = array(
                $language->get('user', 'username'),
                $language->get('admin', 'ip_address'),
                $language->get('general', 'date')
            );
            $rows = array();

            foreach ($logs as $log) {
                $target_user = new User($log->user_id);
				
                $rows[] = array(
                    0 => array(
                        'content' => '<a style="' . $target_user->getGroupClass() . '" href="' . URL::build('/panel/user/' . Output::getClean($log->user_id . '-' . $target_user->getDisplayname(true))) . '">' . $target_user->getDisplayname() . '</a>'
                    ),
                    1 => array(
                        'content' => '<a href="' . URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean($log->ip)) . '">' . Output::getClean($log->ip) . '</a>'
                    ),
                    2 => array(
                        'content' => date('d M Y, H:i', $log->time),
                        'order' => Output::getClean($log->time)
                    )
                );
            }

            break;

        case 'template_changes':
            if (!$user->hasPermission('admincp.security.template')) {
                Redirect::to(URL::build('/panel/security'));
                die();
            }

            $log_title = $language->get('admin', 'template_changes');
            $logs = $queries->orderWhere('logs', 'action = \'acp_template_update\'', 'time', 'DESC');

            $cols = 4;
            $col_titles = array(
                $language->get('user', 'username'),
                $language->get('admin', 'ip_address'),
                $language->get('general', 'date'),
                $language->get('admin', 'file_changed')
            );
            $rows = array();

            foreach ($logs as $log) {
                $target_user = new User($log->user_id);
				
                $rows[] = array(
                    0 => array(
                        'content' => '<a style="' . $target_user->getGroupClass() . '" href="' . URL::build('/panel/user/' . Output::getClean($log->user_id . '-' . $target_user->getDisplayname(true))) . '">' . $target_user->getDisplayname() . '</a>'
                    ),
                    1 => array(
                        'content' => '<a href="' . URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean($log->ip)) . '">' . Output::getClean($log->ip) . '</a>'
                    ),
                    2 => array(
                        'content' => date('d M Y, H:i', $log->time),
                        'order' => Output::getClean($log->time)
                    ),
                    3 => array(
                        'content' => Output::getClean($log->info)
                    )
                );
            }

            break;

        case 'emails':
            if (!$user->hasPermission('admincp.security.emails')) {
                Redirect::to(URL::build('/panel/security'));
                die();
            }

            $log_title = $language->get('admin', 'email_logs');
            $logs = $queries->orderWhere('logs', 'action = \'acp_email_mass_message\'', 'time', 'DESC');

            $cols = 3;
            $col_titles = array(
                $language->get('user', 'username'),
                $language->get('admin', 'ip_address'),
                $language->get('general', 'date'),
            );
            $rows = array();

            foreach ($logs as $log) {
                $target_user = new User($log->user_id);
				
                $rows[] = array(
                    0 => array(
                        'content' => '<a style="' . $target_user->getGroupClass() . '" href="' . URL::build('/panel/user/' . Output::getClean($log->user_id . '-' . $target_user->getDisplayname(true))) . '">' . $target_user->getDisplayname() . '</a>'
                    ),
                    1 => array(
                        'content' => '<a href="' . URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean($log->ip)) . '">' . Output::getClean($log->ip) . '</a>'
                    ),
                    2 => array(
                        'content' => date('d M Y, H:i', $log->time),
                        'order' => Output::getClean($log->time)
                    ),
                );
            }
            break;

        case 'group_sync':
            if (!$user->hasPermission('admincp.security.group_sync')) {
                Redirect::to(URL::build('/panel/security'));
                die();
            }
            
            $log_title = $language->get('admin', 'group_sync_logs');
            $logs_set = $queries->orderWhere('logs', 'action = \'discord_role_set\' OR action = \'mc_group_sync_set\' ', 'time', 'DESC');

            $cols = 5;
            $col_titles = array(
                $language->get('user', 'username'),
                $language->get('general', 'date'),
                $language->get('admin', 'action'),
                $language->get('admin', 'groups_removed'),
                $language->get('admin', 'groups_added')
            );
            $rows = array();

            foreach ($logs_set as $log) {
                $target_user = new User($log->user_id);

                $removed = '';
                foreach (json_decode($log->info, true)['removed'] as $r) {
                    $removed .= $r . ', ';
                }
                $removed = rtrim($removed, ', ');

                $added = '';
                foreach (json_decode($log->info, true)['added'] as $a) {
                    $added .= $a . ', ';
                }
                $added = rtrim($added, ', ');

                $rows[] = array(
                    0 => array(
                        'content' => '<a style="' . $target_user->getGroupClass() . '" href="' . URL::build('/panel/user/' . Output::getClean($log->user_id . '-' . $target_user->getDisplayname(true))) . '">' . $target_user->getDisplayname() . '</a>'
                    ),
                    1 => array(
                        'content' => date('d M Y, H:i', $log->time),
                        'order' => Output::getClean($log->time)
                    ),
                    2 => array(
                        'content' => Output::getClean($log->action)
                    ),
                    3 => array(
                        'content' => Output::getClean($removed)
                    ),
                    4 => array(
                        'content' => Output::getClean($added)
                    )
                );
            }
            break;

            // TODO: Forums section - get all records which action starts with "forum_"

        case 'all':
            if (!$user->hasPermission('admincp.security.all')) {
                Redirect::to(URL::build('/panel/security'));
                die();
            }

            $log_title = $language->get('admin', 'all_logs');
            $logs = $queries->orderWhere('logs', 'id <> 0', 'time', 'DESC');

            $cols = 5;
            $col_titles = array(
                $language->get('user', 'username'),
                $language->get('admin', 'ip_address'),
                $language->get('general', 'date'),
                $language->get('admin', 'action'),
                $language->get('admin', 'action_info')
            );
            $rows = array();

            foreach ($logs as $log) {
                $target_user = new User($log->user_id);
				
                $rows[] = array(
                    0 => array(
                        'content' => '<a style="' . $target_user->getGroupClass() . '" href="' . URL::build('/panel/user/' . Output::getClean($log->user_id . '-' . $target_user->getDisplayname(true))) . '">' . $target_user->getDisplayname() . '</a>'
                    ),
                    1 => array(
                        'content' => '<a href="' . URL::build('/panel/users/ip_lookup/', 'ip=' . Output::getClean($log->ip)) . '">' . Output::getClean($log->ip) . '</a>'
                    ),
                    2 => array(
                        'content' => date('d M Y, H:i', $log->time),
                        'order' => Output::getClean($log->time)
                    ),
                    3 => array(
                        'content' => Output::getClean($log->action)
                    ),
                    4 => array(
                        'content' => Output::getClean($log->info)
                    )
                );
            }

            break;

        default:
            Redirect::to(URL::build('/panel/security'));
            die();
            break;
    }

    $smarty->assign(array(
        'BACK' => $language->get('general', 'back'),
        'BACK_LINK' => URL::build('/panel/security'),
        'LOG_TITLE' => $log_title,
        'COLS' => $cols,
        'COL_TITLES' => $col_titles,
        'ROWS' => $rows
    ));

    $template_file = 'core/security_view.tpl';
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (isset($success))
    $smarty->assign(array(
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'SECURITY' => $language->get('admin', 'security'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
