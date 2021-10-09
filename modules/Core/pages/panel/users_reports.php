<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel reports page
 */

if(!$user->handlePanelPageLoad('modcp.reports')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'users');
define('PANEL_PAGE', 'reports');
$page_title = $language->get('moderator', 'reports');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$timeago = new Timeago(TIMEZONE);

if (!isset($_GET['id'])) {
    // Get all reports
    $reports = array();

    if (!isset($_GET['view'])) {
        // Get open reports
        if (!isset($_GET['uid'])) {
            $report_query = DB::getInstance()->query('SELECT * FROM nl2_reports WHERE status = 0 ORDER BY date_updated DESC')->results();
            $url = URL::build('/panel/users/reports/', true);
            $change_view_link = URL::build('/panel/users/reports/', 'view=closed');
        } else {
            $report_query = DB::getInstance()->query('SELECT * FROM nl2_reports WHERE status = 0 AND reported_id = ? ORDER BY date_updated DESC', array(intval($_GET['uid'])))->results();
            $url = URL::build('/panel/users/reports/', 'uid=' . intval(Output::getClean($_GET['uid'])) . '&');
            $change_view_link = URL::build('/panel/users/reports/', 'view=closed&uid=' . intval(Output::getClean($_GET['uid'])));
        }

        $smarty->assign(array(
            'CHANGE_VIEW' => $language->get('moderator', 'view_closed'),
            'CHANGE_VIEW_LINK' => $change_view_link
        ));
    } else {
        // Get closed reports
        if (!isset($_GET['uid'])) {
            $report_query = DB::getInstance()->query('SELECT * FROM nl2_reports WHERE status = 1 ORDER BY date_updated DESC')->results();
            $url = URL::build('/panel/users/reports/', 'view=closed&');
            $change_view_link = URL::build('/panel/users/reports');
        } else {
            $report_query = DB::getInstance()->query('SELECT * FROM nl2_reports WHERE status = 1 AND reported_id = ? ORDER BY date_updated DESC', array(intval($_GET['uid'])))->results();
            $url = URL::build('/panel/users/reports/', 'view=closed&uid=' . intval(Output::getClean($_GET['uid'])) . '&');
            $change_view_link = URL::build('/panel/users/reports/', 'uid=' . intval(Output::getClean($_GET['uid'])));
        }

        $smarty->assign(array(
            'CHANGE_VIEW' => $language->get('moderator', 'view_open'),
            'CHANGE_VIEW_LINK' => $change_view_link
        ));
    }

    if (count($report_query)) {
        // Get page
        if (isset($_GET['p'])) {
            if (!is_numeric($_GET['p'])) {
                Redirect::to($url);
                die();
            } else {
                if ($_GET['p'] == 1) {
                    // Avoid bug in pagination class
                    Redirect::to($url);
                    die();
                }
                $p = $_GET['p'];
            }
        } else {
            $p = 1;
        }

        $paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
        $results = $paginator->getLimited($report_query, 10, $p, count($report_query));
        $pagination = $paginator->generate(7, $url);

        foreach ($results->data as $report) {
            // Get comments count
            $comments = $queries->getWhere('reports_comments', array('report_id', '=', $report->id));
            $comments = count($comments);

            $target_user = new User($report->reported_id);

            if ($report->type == 0) {
                // Site report
                $user_reported = $target_user->getDisplayname();
                $user_profile = URL::build('/panel/user/' . Output::getClean($report->reported_id . '-' . $target_user->data()->username));
                $user_style = $target_user->getGroupClass();
                $user_avatar = $target_user->getAvatar(128);
            } else {
                // Ingame report
                $user_reported = Output::getClean($report->reported_mcname);
                $user_profile = URL::build('/panel/user/' . Output::getClean($report->reported_id . '-' . $report->reported_mcname));
                $user_style = '';
                $user_avatar = Util::getAvatarFromUUID($report->reported_uuid, 128);
            }

            $updated_by_user = new User($report->updated_by);

            $reports[] = array(
                'id' => $report->id,
                'user_reported' => $user_reported,
                'user_profile' => $user_profile,
                'user_reported_style' => $user_style,
                'user_reported_avatar' => $user_avatar,
                'reported_at' => ($report->reported ? $timeago->inWords(date('Y-m-d H:i:s', $report->reported), $language->getTimeLanguage()) : $timeago->inWords($report->date_reported, $language->getTimeLanguage())),
                'reported_at_full' => ($report->reported ? date('d M Y, H:i', $report->reported) : date('d M Y, H:i', strtotime($report->date_reported))),
                'link' => URL::build('/panel/users/reports/', 'id=' . $report->id),
                'updated_by' => $updated_by_user->getDisplayname(),
                'updated_by_profile' => URL::build('/panel/user/' . Output::getClean($report->updated_by . '-' . $updated_by_user->data()->username)),
                'updated_by_style' => $updated_by_user->getGroupClass(),
                'updated_by_avatar' => $updated_by_user->getAvatar(128),
                'updated_at' => ($report->updated ? $timeago->inWords(date('Y-m-d H:i:s', $report->updated), $language->getTimeLanguage()) : $timeago->inWords($report->date_updated, $language->getTimeLanguage())),
                'updated_at_full' => ($report->updated ? date('d M Y, H:i', $report->updated) : date('d M Y, H:i', strtotime($report->date_updated))),
                'comments' => $comments
            );
        }

        $smarty->assign('PAGINATION', $pagination);
    } else {
        if (!isset($_GET['view'])) {
            $smarty->assign('NO_REPORTS', $language->get('moderator', 'no_open_reports'));
        } else {
            $smarty->assign('NO_REPORTS', $language->get('moderator', 'no_closed_reports'));
        }
    }

    if (isset($_GET['uid'])) {
        $smarty->assign('VIEWING_USER', Output::getClean($user->idToNickname(intval($_GET['uid']))));
    }

    // Smarty variables
    $smarty->assign(array(
        'ALL_REPORTS' => $reports,
        'VIEW' => $language->get('general', 'view'),
        'USER_REPORTED' => $language->get('moderator', 'user_reported'),
        'COMMENTS' => $language->get('moderator', 'comments'),
        'UPDATED_BY' => $language->get('moderator', 'updated_by'),
        'ACTIONS' => $language->get('moderator', 'actions')
    ));

    $template_file = 'core/users_reports.tpl';
} else {
    // Get report by ID
    if (!isset($_GET['action'])) {
        $report = $queries->getWhere('reports', array('id', '=', $_GET['id']));
        if (!count($report)) {
            Redirect::to(URL::build('/panel/users/reports'));
            die();
        }
        $report = $report[0];

        // Check input
        if (Input::exists()) {
            $errors = array();

            // Check token
            if (Token::check()) {
                // Valid token
                $validate = new Validate();

                $validation = $validate->check($_POST, [
                    'content' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 1,
                        Validate::MAX => 10000
                    ]
                ])->message($language->get('moderator', 'report_comment_invalid'));

                if ($validation->passed()) {
                    $queries->create('reports_comments', array(
                        'report_id' => $report->id,
                        'commenter_id' => $user->data()->id,
                        'comment_date' => date('Y-m-d H:i:s'),
                        'comment_content' => Output::getClean(Input::get('content')),
                        'date' => date('U')
                    ));

                    $queries->update('reports', $report->id, array(
                        'updated_by' => $user->data()->id,
                        'updated' => date('U'),
                        'date_updated' => date('Y-m-d H:i:s')
                    ));

                    $success = $language->get('moderator', 'comment_created');
                } else {
                    // Display error
                    $errors = $validation->errors();
                }
            } else {
                // Invalid token
                $errors[] = $language->get('general', 'invalid_token');
            }
        }

        // Get comments
        $comments = $queries->getWhere('reports_comments', array('report_id', '=', $report->id));
        $smarty_comments = array();
        foreach ($comments as $comment) {
            $comment_user = new User($comment->commenter_id);

            $smarty_comments[] = array(
                'username' => $comment_user->getDisplayname(),
                'profile' => URL::build('/panel/user/' . Output::getClean($comment->commenter_id . '-' . $comment_user->data()->username)),
                'style' => $comment_user->getGroupClass(),
                'avatar' => $comment_user->getAvatar(),
                'content' => Output::getPurified(Output::getDecoded($comment->comment_content)),
                'date' => ($comment->date ? date('d M Y, H:i', $comment->date) : date('d M Y, H:i', strtotime($comment->comment_date))),
                'date_friendly' => ($comment->date ? $timeago->inWords(date('Y-m-d H:i:s', $comment->date), $language->getTimeLanguage()) : $timeago->inWords($comment->comment_date, $language->getTimeLanguage()))
            );
        }

        if (!$report->reported_id) {
            $reported_user = new User($report->reported_uuid, 'uuid');
            if ($reported_user->data()) {
                $reported_user_profile = URL::build('/panel/user/' . Output::getClean($reported_user->data()->id . '-' . $reported_user->data()->username));
                $reported_user_style = $reported_user->getGroupClass();
                $reported_user_avatar = $reported_user->getAvatar(128);
            } else {
                $reported_user_profile = '#';
                $reported_user_style = '';
                $reported_user_avatar = Util::getAvatarFromUUID(Output::getClean($report->reported_uuid), 128);
            }

            $reported_user_name = Output::getClean($report->reported_mcname);
        } else {
            $reported_user = new User($report->reported_id);

            $reported_user_name = $reported_user->getDisplayname();
            $reported_user_profile = URL::build('/panel/user/' . Output::getClean($report->reported_id . '-' . $reported_user->data()->username));
            $reported_user_style = $reported_user->getGroupClass();
            $reported_user_avatar = $reported_user->getAvatar();
        }

        $reporter_user = new User($report->reporter_id);

        // Smarty variables
        $smarty->assign(array(
            'REPORTS_LINK' => URL::build('/panel/users/reports'),
            'VIEWING_REPORT' => $language->get('moderator', 'viewing_report'),
            'BACK' => $language->get('general', 'back'),
            'REPORTED_USER' => $reported_user_name,
            'REPORTED_USER_PROFILE' => $reported_user_profile,
            'REPORTED_USER_STYLE' => $reported_user_style,
            'REPORTED_USER_AVATAR' => $reported_user_avatar,
            'REPORT_DATE' => ($report->reported ? date('d M Y, H:i', $report->reported) : date('d M Y, H:i', strtotime($report->date_reported))),
            'REPORT_DATE_FRIENDLY' => ($report->reported ? $timeago->inWords(date('Y-m-d H:i:s', $report->reported), $language->getTimeLanguage()) : $timeago->inWords($report->date_reported, $language->getTimeLanguage())),
            'CONTENT_LINK' => $report->link,
            'VIEW_CONTENT' => $language->get('moderator', 'view_content'),
            'REPORT_CONTENT' => Output::getPurified(Output::getDecoded($report->report_reason)),
            'REPORTER_USER' => $reporter_user->getDisplayname(),
            'REPORTER_USER_PROFILE' => URL::build('/panel/user/' . Output::getClean($report->reporter_id . '-' . $reporter_user->data()->username)),
            'REPORTER_USER_STYLE' => $reporter_user->getGroupClass(),
            'REPORTER_USER_AVATAR' => $reporter_user->getAvatar(),
            'COMMENTS' => $smarty_comments,
            'COMMENTS_TEXT' => $language->get('moderator', 'comments'),
            'NO_COMMENTS' => $language->get('moderator', 'no_comments'),
            'NEW_COMMENT' => $language->get('moderator', 'new_comment'),
            'TYPE' => $report->type
        ));

        // Close/reopen link
        if ($report->status == 0) {
            $smarty->assign(array(
                'CLOSE_LINK' => URL::build('/panel/users/reports/', 'action=close&id=' . $report->id),
                'CLOSE_REPORT' => $language->get('moderator', 'close_report')
            ));
        } else {
            $smarty->assign(array(
                'REOPEN_LINK' => URL::build('/panel/users/reports/', 'action=open&id=' . $report->id),
                'REOPEN_REPORT' => $language->get('moderator', 'reopen_report')
            ));
        }

        $template_file = 'core/users_reports_view.tpl';
    } else {
        if ($_GET['action'] == 'close') {
            // Close report
            if (is_numeric($_GET['id'])) {
                // Get report
                $report = $queries->getWhere('reports', array('id', '=', $_GET['id']));
                if (count($report)) {
                    $queries->update('reports', $report[0]->id, array(
                        'status' => 1,
                        'date_updated' => date('Y-m-d H:i:s'),
                        'updated' => date('U'),
                        'updated_by' => $user->data()->id
                    ));

                    $queries->create('reports_comments', array(
                        'report_id' => $report[0]->id,
                        'commenter_id' => $user->data()->id,
                        'comment_date' => date('Y-m-d H:i:s'),
                        'date' => date('U'),
                        'comment_content' => str_replace('{x}', Output::getClean($user->data()->username), $language->get('moderator', 'x_closed_report'))
                    ));
                }

                Session::flash('report_success', $language->get('moderator', 'report_closed'));
                Redirect::to(URL::build('/panel/users/reports/', 'id=' . Output::getClean($report[0]->id)));
                die();
            }

            Redirect::to(URL::build('/panel/users/reports'));
            die();
        } else if ($_GET['action'] == 'open') {
            // Reopen report
            if (is_numeric($_GET['id'])) {
                // Get report
                $report = $queries->getWhere('reports', array('id', '=', $_GET['id']));
                if (count($report)) {
                    $queries->update('reports', $report[0]->id, array(
                        'status' => 0,
                        'date_updated' => date('Y-m-d H:i:s'),
                        'updated' => date('U'),
                        'updated_by' => $user->data()->id
                    ));

                    $queries->create('reports_comments', array(
                        'report_id' => $report[0]->id,
                        'commenter_id' => $user->data()->id,
                        'comment_date' => date('Y-m-d H:i:s'),
                        'date' => date('U'),
                        'comment_content' => str_replace('{x}', Output::getClean($user->data()->username), $language->get('moderator', 'x_reopened_report'))
                    ));
                }

                Session::flash('report_success', $language->get('moderator', 'report_reopened'));
                Redirect::to(URL::build('/panel/users/reports/', 'id=' . Output::getClean($report[0]->id)));
                die();
            }

            Redirect::to(URL::build('/panel/users/reports'));
            die();
        } else {
            Redirect::to(URL::build('/panel/users/reports'));
            die();
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('report_success'))
    $success = Session::flash('report_success');

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
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'REPORTS' => $language->get('moderator', 'reports'),
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
