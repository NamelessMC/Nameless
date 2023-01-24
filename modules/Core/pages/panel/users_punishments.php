<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel punishments page
 */

if (!$user->handlePanelPageLoad('modcp.punishments')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

$timeago = new TimeAgo(TIMEZONE);

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'punishments';
$page_title = $language->get('moderator', 'punishments');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['user'])) {
    // Viewing a certain user
    $view_user = new User($_GET['user']);
    if (!$view_user->exists()) {
        Redirect::to(URL::build('/panel/users/punishments'));
    }
    $query = $view_user->data();

    if (isset($_GET['do'], $_GET['id']) && $_GET['do'] == 'revoke' && is_numeric($_GET['id'])) {
        if (Token::checK()) {
            $infraction = DB::getInstance()->get('infractions', ['id', $_GET['id']])->results();
            if (!$user->hasPermission('modcp.punishments.revoke') || !count($infraction) || ($infraction[0]->punished != $query->id)) {
                Redirect::to(URL::build('/panel/users/punishments/', 'user=' . urlencode($query->id)));
            }
            $infraction = $infraction[0];

            // Revoke infraction
            // Unban user/IP
            if ($infraction->type == 1) {
                // Unban user
                try {
                    DB::getInstance()->update('users', $query->id, [
                        'isbanned' => false,
                        'active' => true,
                    ]);
                } catch (Exception $e) {
                    // Error
                    $errors = [$e->getMessage()];
                }
            } else {
                if ($infraction->type == 3) {
                    try {
                        DB::getInstance()->update('users', $query->id, [
                            'isbanned' => false,
                            'active' => true,
                        ]);

                        DB::getInstance()->delete('ip_bans', ['ip', $query->lastip]);
                    } catch (Exception $e) {
                        // Error
                        $errors = [$e->getMessage()];
                    }
                }
            }

            try {
                DB::getInstance()->update('infractions', $infraction->id, [
                    'acknowledged' => true,
                    'revoked' => true,
                    'revoked_by' => $user->data()->id,
                    'revoked_at' => date('U')
                ]);
            } catch (Exception $e) {
                // Error
                $errors = [$e->getMessage()];
            }

            Session::flash('user_punishment_success', $language->get('moderator', 'punishment_revoked'));

        } else {
            $errors = [$language->get('general', 'invalid_token')];
        }

        Redirect::to(URL::build('/panel/users/punishments/', 'user=' . urlencode($query->id)));
    }

    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {
            if (isset($_POST['type'])) {
                switch ($_POST['type']) {
                    case 'reset_avatar':
                        // Reset Avatar
                        if (!$user->hasPermission('modcp.punishments.reset_avatar')) {
                            Redirect::to(URL::build('/panel/users/punishments'));
                        }
                        $type = 4;
                        break;

                    case 'ban':
                        // Ban
                        if (!$user->hasPermission('modcp.punishments.ban')) {
                            Redirect::to(URL::build('/panel/users/punishments'));
                        }
                        $type = 1;
                        break;

                    case 'ban_ip':
                        // Ban IP
                        if (!$user->hasPermission('modcp.punishments.banip')) {
                            Redirect::to(URL::build('/panel/users/punishments'));
                        }
                        $type = 3;
                        break;

                    default:
                        // Warn
                        if (!$user->hasPermission('modcp.punishments.warn')) {
                            Redirect::to(URL::build('/panel/users/punishments'));
                        }
                        $type = 2;
                        break;
                }

                // Check reason
                if (isset($_POST['reason']) && strlen($_POST['reason']) >= 5 && strlen($_POST['reason']) <= 5000) {
                    try {
                        // Ensure user is not an admin
                        $banned_user = new User($query->id);
                        $is_admin = $banned_user->canViewStaffCP();

                        // Ensure user is not admin
                        if (!$is_admin) {
                            // Prevent ip banning if target ip match the user ip
                            // TODO: Change message for when IP matches
                            if ($type != 3 || $user->data()->lastip != $banned_user->data()->lastip) {
                                DB::getInstance()->insert('infractions', [
                                    'type' => $type,
                                    'punished' => $query->id,
                                    'staff' => $user->data()->id,
                                    'reason' => $_POST['reason'],
                                    'infraction_date' => date('Y-m-d H:i:s'),
                                    'created' => date('U'),
                                    'acknowledged' => ($type == 2) ? 0 : 1,
                                ]);

                                switch($type) {
                                    case 1:
                                    case 3:
                                        // Ban the user
                                        DB::getInstance()->update('users', $query->id, [
                                            'isbanned' => true,
                                            'active' => false
                                        ]);

                                        $banned_user_ip = $banned_user->data()->lastip;

                                        DB::getInstance()->delete('users_session', ['user_id', $query->id]);

                                        if ($type == 3) {
                                            // Ban IP
                                            DB::getInstance()->insert('ip_bans', [
                                                'ip' => $banned_user_ip,
                                                'banned_by' => $user->data()->id,
                                                'banned_at' => date('U'),
                                                'reason' => $_POST['reason']
                                            ]);
                                        }

                                        // Fire userBanned event
                                        EventHandler::executeEvent(new UserBannedEvent(
                                            $banned_user,
                                            $user,
                                            $_POST['reason'],
                                            $type == 3
                                        ));
                                        break;
                                    case 2:
                                        // Fire userWarned event
                                        EventHandler::executeEvent(new UserWarnedEvent(
                                            $banned_user,
                                            $user,
                                            $_POST['reason']
                                        ));
                                        break;
                                    case 4:
                                        // Need to delete any other avatars
                                        $to_remove = [];
                                        foreach (['jpg', 'jpeg', 'png', 'gif'] as $extension) {
                                            $to_remove += glob(ROOT_PATH . '/uploads/avatars/' . $query->id . '.' . $extension);
                                        }

                                        foreach ($to_remove as $item) {
                                            unlink($item);
                                        }

                                        DB::getInstance()->update('users', $query->id, [
                                            'has_avatar' => false,
                                            'avatar_updated' => date('U')
                                        ]);
                                        break;
                                    default:
                                        throw new InvalidArgumentException("Unexpected type $type");
                                }

                                // Send alerts
                                $groups_query = DB::getInstance()->query('SELECT id FROM nl2_groups WHERE permissions LIKE \'%"modcp.punishments":1%\'');
                                if ($groups_query->count()) {
                                    $groups_query = $groups_query->results();

                                    $groups = '(';
                                    foreach ($groups_query as $group) {
                                        if (is_numeric($group->id)) {
                                            $groups .= ((int)$group->id) . ',';
                                        }
                                    }
                                    $groups = rtrim($groups, ',') . ')';

                                    // Get users in this group
                                    $users = DB::getInstance()->query('SELECT DISTINCT(nl2_users.id) AS id FROM nl2_users LEFT JOIN nl2_users_groups ON nl2_users.id = nl2_users_groups.user_id WHERE group_id in ' . $groups);

                                    if ($users->count()) {
                                        $users = $users->results();

                                        foreach ($users as $item) {
                                            if ($user->data()->id == $item->id) {
                                                continue;
                                            }

                                            // Send alert
                                            Alert::create(
                                                $item->id,
                                                'punishment',
                                                [
                                                    'path' => 'core',
                                                    'file' => 'moderator',
                                                    'term' => 'user_punished_alert',
                                                    'replace' => ['{{staffUser}}', '{{user}}'],
                                                    'replace_with' => [
                                                        Output::getClean($user->data()->nickname),
                                                        Output::getClean($query->nickname),
                                                    ],
                                                ],
                                                [
                                                    'path' => 'core',
                                                    'file' => 'moderator',
                                                    'term' => 'user_punished_alert',
                                                    'replace' => ['{{staffUser}}', '{{user}}'],
                                                    'replace_with' => [
                                                        Output::getClean($user->data()->nickname),
                                                        Output::getClean($query->nickname)
                                                    ],
                                                ],
                                                URL::build('/panel/users/punishments/', 'user=' . urlencode($query->id))
                                            );
                                        }
                                    }
                                }
                                Redirect::to(URL::build('/panel/users/punishments/', 'user=' . urlencode($query->id)));
                            } else {
                                $errors[] = $language->get('moderator', 'cant_punish_admin');
                            }
                        } else {
                            $errors[] = $language->get('moderator', 'cant_punish_admin');
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                } else {
                    $errors[] = $language->get('moderator', 'enter_valid_punishment_reason');
                }
            }
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    // Get any previous punishments
    $previous_punishments = DB::getInstance()->orderWhere('infractions', 'punished = ' . $query->id, 'created', 'DESC')->results();
    $previous_punishments_array = [];
    if (count($previous_punishments)) {
        foreach ($previous_punishments as $punishment) {
            switch ($punishment->type) {
                case 1:
                    // Ban
                    $type = $language->get('moderator', 'ban');
                    break;
                case 2:
                    // Warning
                    $type = $language->get('moderator', 'warning');
                    break;
                case 4:
                    // Reset Avatar
                    $type = $language->get('moderator', 'reset_avatar');
                    break;
                default:
                    // IP Ban
                    $type = $language->get('moderator', 'ip_ban');
                    break;
            }

            $issued_by_user = new User($punishment->staff);
            $previous_punishments_array[] = [
                'type' => $type,
                'type_numeric' => $punishment->type,
                'revoked' => $punishment->revoked,
                'acknowledged' => $punishment->acknowledged,
                'reason' => Output::getClean($punishment->reason),
                'issued_by_nickname' => $issued_by_user->getDisplayname(),
                'issued_by_profile' => URL::build('/panel/user/' . urlencode($punishment->staff . '-' . $issued_by_user->data()->username)),
                'issued_by_style' => $issued_by_user->getGroupStyle(),
                'issued_by_avatar' => $issued_by_user->getAvatar(),
                'date_full' => ($punishment->created ? date(DATE_FORMAT, $punishment->created) : date(DATE_FORMAT, strtotime($punishment->infraction_date))),
                'date_friendly' => ($punishment->created ? $timeago->inWords($punishment->created, $language) : $timeago->inWords($punishment->infraction_date, $language)),
                'revoke_link' => (($user->hasPermission('modcp.punishments.revoke') && $punishment->type != 4) ? URL::build('/panel/users/punishments/', 'user=' . urlencode($query->id) . '&do=revoke&id=' . urlencode($punishment->id)) : 'none'),
                'confirm_revoke_punishment' => (($punishment->type == 2) ? $language->get('moderator', 'confirm_revoke_warning') : $language->get('moderator', 'confirm_revoke_ban'))
            ];
        }
    }

    if ($user->hasPermission('modcp.punishments.reset_avatar')) {
        $smarty->assign('RESET_AVATAR', $language->get('moderator', 'reset_avatar'));
    }

    if ($user->hasPermission('modcp.punishments.warn')) {
        $smarty->assign('WARN', $language->get('moderator', 'warn'));
    }

    if ($user->hasPermission('modcp.punishments.ban')) {
        $smarty->assign('BAN', $language->get('moderator', 'ban'));
    }

    if ($user->hasPermission('modcp.punishments.banip')) {
        $smarty->assign('BAN_IP', $language->get('moderator', 'ban_ip'));
    }

    if ($user->hasPermission('modcp.punishments.revoke')) {
        $smarty->assign('REVOKE_PERMISSION', true);
    }

    $smarty->assign([
        'HAS_AVATAR' => $query->has_avatar,
        'BACK_LINK' => URL::build('/panel/user/' . urlencode($view_user->data()->id)),
        'BACK' => $language->get('general', 'back'),
        'VIEWING_USER' => $language->get('moderator', 'viewing_user_x', ['user' => $view_user->getDisplayname()]),
        'PREVIOUS_PUNISHMENTS' => $language->get('moderator', 'previous_punishments'),
        'PREVIOUS_PUNISHMENTS_LIST' => $previous_punishments_array,
        'NO_PREVIOUS_PUNISHMENTS' => $language->get('moderator', 'no_previous_punishments'),
        'CANCEL' => $language->get('general', 'cancel'),
        'WARN_USER' => $language->get('moderator', 'warn_user'),
        'BAN_USER' => $language->get('moderator', 'ban_user'),
        'REASON' => $language->get('moderator', 'reason'),
        'REVOKED' => $language->get('moderator', 'revoked'),
        'REVOKE' => $language->get('moderator', 'revoke'),
        'ACKNOWLEDGED' => $language->get('moderator', 'acknowledged'),
        'USERNAME' => $view_user->getDisplayname(true),
        'NICKNAME' => $view_user->getDisplayname(),
        'USER_STYLE' => $view_user->getGroupStyle(),
        'AVATAR' => $view_user->getAvatar(),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no')
    ]);

    $template_file = 'core/users_punishments_user.tpl';
} else {
    if (Input::exists() && isset($_POST['username'])) {
        if (Token::check()) {
            $check = DB::getInstance()->query('SELECT id FROM nl2_users WHERE username = ? OR nickname = ?', [$_POST['username'], $_POST['username']]);

            if ($check->count()) {
                $check = $check->first();

                Redirect::to(URL::build('/panel/users/punishments/', 'user=' . urlencode($check->id)));
            }

            $errors = [$language->get('user', 'couldnt_find_that_user')];
        } else {
            $errors = [$language->get('general', 'invalid_token')];
        }
    }

    // List all punishments
    $punishments = DB::getInstance()->orderWhere('infractions', 'id <> 0', 'created', 'DESC')->results();

    if (count($punishments)) {
        // Pagination
        // Get page
        if (isset($_GET['p'])) {
            if (!is_numeric($_GET['p'])) {
                Redirect::to(URL::build('/panel/users/punishments'));
            }

            if ($_GET['p'] == 1) {
                // Avoid bug in pagination class
                Redirect::to(URL::build('/panel/users/punishments'));
            }
            $p = $_GET['p'];
        } else {
            $p = 1;
        }

        $paginator = new Paginator(
            $template_pagination ?? null,
            $template_pagination_left ?? null,
            $template_pagination_right ?? null
        );
        $results = $paginator->getLimited($punishments, 10, $p, count($punishments));
        $pagination = $paginator->generate(7, URL::build('/panel/users/punishments/'));

        $smarty_results = [];
        foreach ($results->data as $result) {
            switch ($result->type) {
                case 1:
                    // Ban
                    $type = $language->get('moderator', 'ban');
                    break;
                case 2:
                    // Warning
                    $type = $language->get('moderator', 'warning');
                    break;
                default:
                    // IP Ban
                    $type = $language->get('moderator', 'ip_ban');
                    break;
            }

            $target_user = new User($result->punished);
            $staff_user = new User($result->staff);

            $smarty_results[] = [
                'username' => $target_user->getDisplayname(true),
                'nickname' => $target_user->getDisplayname(),
                'profile' => URL::build('/panel/user/' . urlencode($result->punished . '-' . $target_user->data()->username)),
                'style' => $target_user->getGroupStyle(),
                'avatar' => $target_user->getAvatar(),
                'staff_username' => $staff_user->getDisplayname(true),
                'staff_nickname' => $staff_user->getDisplayname(),
                'staff_profile' => URL::build('/panel/user/' . urlencode($result->staff . '-' . $staff_user->data()->username)),
                'staff_style' => $staff_user->getGroupStyle(),
                'staff_avatar' => $staff_user->getAvatar(),
                'type' => $type,
                'type_numeric' => $result->type,
                'revoked' => $result->revoked,
                'acknowledged' => $result->acknowledged,
                'time_full' => ($result->created ? date(DATE_FORMAT, $result->created) : date(DATE_FORMAT, strtotime($result->infraction_date))),
                'time' => ($result->created ? $timeago->inWords($result->created, $language) : $timeago->inWords($result->infraction_date, $language)),
                'link' => URL::build('/panel/users/punishments/', 'user=' . urlencode($result->punished)),
            ];
        }

        $smarty->assign([
            'PAGINATION' => $pagination,
            'STAFF' => $language->get('moderator', 'staff'),
            'ACTIONS' => $language->get('moderator', 'actions'),
            'WHEN' => $language->get('moderator', 'when'),
            'VIEW_USER' => $language->get('moderator', 'view_user'),
            'TYPE' => $language->get('moderator', 'type'),
            'RESULTS' => $smarty_results,
            'ACKNOWLEDGED' => $language->get('moderator', 'acknowledged'),
            'REVOKED' => $language->get('moderator', 'revoked')
        ]);
    } else {
        $smarty->assign('NO_PUNISHMENTS', $language->get('moderator', 'no_punishments_found'));
    }

    $smarty->assign([
        'USERNAME' => $language->get('user', 'username'),
        'SEARCH' => $language->get('general', 'search'),
        'CANCEL' => $language->get('general', 'cancel')
    ]);

    $template_file = 'core/users_punishments.tpl';
}

if (Session::exists('user_punishment_success')) {
    $success = Session::flash('user_punishment_success');
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
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'PUNISHMENTS' => $language->get('moderator', 'punishments'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
