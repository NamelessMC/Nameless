<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Moderator punishments page
 */

// Can the user view the ModCP?
if($user->isLoggedIn()){
    if(!$user->canViewMCP()){
        // No
        Redirect::to(URL::build('/'));
        die();
    } else if(!$user->hasPermission('modcp.punishments')){
        // Can't view this page
        require(ROOT_PATH . '/404.php');
        die();
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

$timeago = new Timeago(TIMEZONE);

define('PAGE', 'mod_punishments');
$page_title = $language->get('moderator', 'mod_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require(ROOT_PATH . '/core/templates/mod_navbar.php');

$smarty->assign(array(
    'MOD_CP' => $language->get('moderator', 'mod_cp'),
    'PUNISHMENTS' => $language->get('moderator', 'punishments'),
    'SUBMIT' => $language->get('general', 'submit'),
    'TOKEN' => Token::get()
));

if(isset($_GET['view'])){
    if($_GET['view'] == 'punishments'){
        // Get page
        if(isset($_GET['p'])){
            if(!is_numeric($_GET['p'])){
                Redirect::to(URL::build('/mod/punishments/', 'view=punishments'));
                die();
            } else {
                if($_GET['p'] == 1){
                    // Avoid bug in pagination class
                    Redirect::to(URL::build('/mod/punishments/', 'view=punishments'));
                    die();
                }
                $p = $_GET['p'];
            }
        } else {
            $p = 1;
        }

        $smarty->assign('VIEWING_ALL_PUNISHMENTS', $language->get('moderator', 'viewing_all_punishments'));

        // Get punishments
        $punishments = $queries->orderWhere('infractions', 'id <> 0', 'infraction_date', 'DESC');
        if(count($punishments)){
            // Pagination
            $paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
            $results = $paginator->getLimited($punishments, 10, $p, count($punishments));
            $pagination = $paginator->generate(7, URL::build('/mod/punishments/', 'view=punishments&amp;'));

            $smarty_results = array();
            foreach($results->data as $result){
                switch($result->type){
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

                $smarty_results[] = array(
                    'username' => Output::getClean($user->idToName($result->punished)),
                    'nickname' => Output::getClean($user->idToNickname($result->punished)),
                    'profile' => URL::build('/profile/' . Output::getClean($user->idToName($result->punished))),
                    'style' => $user->getGroupClass($result->punished),
                    'staff_username' => Output::getClean($user->idToName($result->staff)),
                    'staff_nickname' => Output::getClean($user->idToNickname($result->staff)),
                    'staff_profile' => URL::build('/profile/' . Output::getClean($user->idToName($result->staff))),
                    'staff_style' => $user->getGroupClass($result->staff),
                    'type' => $type,
                    'type_numeric' => $result->type,
                    'revoked' => $result->revoked,
                    'acknowledged' => $result->acknowledged,
                    'time_full' => date('d M Y, H:i', strtotime($result->infraction_date)),
                    'time' => $timeago->inWords($result->infraction_date, $language->getTimeLanguage()),
                    'link' => URL::build('/mod/punishments/', 'user=' . $result->punished)
                );
            }

            $smarty->assign(array(
                'PAGINATION' => $pagination,
                'USERNAME' => $language->get('user', 'username'),
                'STAFF' => $language->get('moderator', 'staff'),
                'ACTIONS' => $language->get('moderator', 'actions'),
                'WHEN' => $language->get('moderator', 'when'),
                'VIEW_USER' => $language->get('moderator', 'view_user'),
                'TYPE' => $language->get('moderator', 'type'),
                'RESULTS' => $smarty_results,
                'ACKNOWLEDGED' => $language->get('moderator', 'acknowledged'),
                'REVOKED' => $language->get('moderator', 'revoked')
            ));
        } else {
            $smarty->assign('NO_PUNISHMENTS', $language->get('moderator', 'no_punishments_found'));
        }

	    // Load modules + template
	    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

	    $page_load = microtime(true) - $start;
	    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	    $template->onPageLoad();

	    require(ROOT_PATH . '/core/templates/navbar.php');
	    require(ROOT_PATH . '/core/templates/footer.php');

		// Display template
	    $template->displayTemplate('mod/all_punishments.tpl', $smarty);

    } else {
        Redirect::to(URL::build('/mod/punishments'));
        die();
    }
} else {
    if(isset($_GET['user']) && is_numeric($_GET['user'])) {
        // Viewing a certain user
        $query = $queries->getWhere('users', array('id', '=', $_GET['user']));

        if(!count($query)){
            Redirect::to(URL::build('/mod/punishments'));
            die();
        }
        $query = $query[0];

        if(isset($_GET['do']) && $_GET['do'] == 'revoke' && isset($_GET['id']) && is_numeric($_GET['id'])){
            $infraction = $queries->getWhere('infractions', array('id', '=', $_GET['id']));
            if(!$user->hasPermission('modcp.punishments.revoke') || !count($infraction) || (count($infraction) && $infraction[0]->punished != $query->id)){
                Redirect::to(URL::build('/mod/punishments/', 'user=' . $query->id));
                die();
            }
            $infraction = $infraction[0];

            // Revoke infraction
            // Unban user/IP
            if($infraction->type == 1){
                // Unban user
                try {

                    $queries->update('users', $query->id, array(
                        'isbanned' => 0,
                        'active' => 1
                    ));
                } catch(Exception $e){
                    // Error
                }
            } else if($infraction->type == 3){
                // Unban IP
                try {

                    $queries->update('users', $query->id, array(
                        'isbanned' => 0,
                        'active' => 1
                    ));

                    $queries->delete('ip_bans', array('ip', '=', $query->lastip));

                } catch(Exception $e){
                    // Error
                }
            }

            try {
                $infractionTypeText = "";
                if($infraction->type == 1){
                    $infractionTypeText = $language->get('moderator', 'ban');
                }else if($infraction->type == 2){
                    $infractionTypeText = $language->get('moderator', 'warn');
                }else if($infraction->type == 3){
                    $infractionTypeText = $language->get('moderator', 'ban_ip');
                }
                
                $query_user = $queries->getWhere("users", ["id", "=", Output::getClean($_GET['user'])]);
                $query_user = Output::getClean($query_user[0]->username);

                Log::getInstance()->log(Log::Action('mod/punishment/revoke'),  $language->get('moderator', 'punishments')."{$infractionTypeText}: ".$query_user);

                $queries->create('logs', array(
                        'time' => date('U'),
                        'action' => $language->get('log', 'log_punishment_revoke'),
                        'user_id' => $user->data()->id,
                        'ip' => $user->getIP(),
                        'info' => $language->get('moderator', 'punishments')."{$infractionTypeText}: ".$query_user,
                    ));

                $queries->update('infractions', $infraction->id, array(
                    'acknowledged' => 1,
                    'revoked' => 1,
                    'revoked_by' => $user->data()->id,
                    'revoked_at' => date('U')
                ));
            } catch(Exception $e){
                // Error
            }

            Session::flash('user_punishment_success', $language->get('moderator', 'punishment_revoked'));
            Redirect::to(URL::build('/mod/punishments/', 'user=' . $query->id));
            die();
        }

        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                if(isset($_POST['type'])) {
                    switch ($_POST['type']) {
                        case 'ban':
                            // Ban
                            if(!$user->hasPermission('modcp.punishments.ban')){
                                Redirect::to(URL::build('/mod/punishments'));
                                die();
                            }
                            $type = 1;
                            break;
                        case 'ban_ip':
                            // Ban IP
                            if(!$user->hasPermission('modcp.punishments.banip')){
                                Redirect::to(URL::build('/mod/punishments'));
                                die();
                            }
                            $type = 3;
                            break;
                        default:
                            // Warn
                            if(!$user->hasPermission('modcp.punishments.warn')){
                                Redirect::to(URL::build('/mod/punishments'));
                                die();
                            }
                            $type = 2;
                            break;
                    }

                    // Check reason
                    if(isset($_POST['reason']) && strlen($_POST['reason']) >= 5 && strlen($_POST['reason']) <= 5000){
                        try {
                            // Ensure user is not an admin
                            $banned_user = new User($query->id);
                            $is_admin = $banned_user->canViewACP();

                            // Ensure user is not admin
                            if(!$is_admin){
                                $queries->create('infractions', array(
                                    'type' => $type,
                                    'punished' => $query->id,
                                    'staff' => $user->data()->id,
                                    'reason' => $_POST['reason'],
                                    'infraction_date' => date('Y-m-d H:i:s'),
                                    'acknowledged' => (($type == 2) ? 0 : 1)
                                ));
                                
                                $typeText = "null";
                                if($type == 1){
                                    $typeText = "Ban";
                                }else if($type == 2){
                                    $typeText = "Warn";
                                }else if($type == 3){
                                    $typeText = "IP Ban";
                                }
                                Log::getInstance()->log(Log::Action('mod/punishment/revoke'),  $language->get('moderator', 'punishments').': '. 
                                                $typeText.
                                                ', '.
                                                $language->get('user', 'username').
                                                Output::getClean($banned_user->data()->username));

                                if($type == 1 || $type == 3){

                                    // Ban the user
                                    $queries->update('users', $query->id, array(
                                        'isbanned' => 1,
                                        'active' => 0
                                    ));

                                    $banned_user_ip = $banned_user->data()->lastip;

                                    $queries->delete('users_session', array('user_id', '=', $query->id));

                                    if($type == 3){
                                        // Ban IP
                                        $queries->create('ip_bans', array(
                                            'ip' => $banned_user_ip,
                                            'banned_by' => $user->data()->id,
                                            'banned_at' => date('U'),
                                            'reason' => $_POST['reason']
                                        ));
                                    }
                                }

                                Log::getInstance()->log(Log::Action('mod/punishment/create'),  $language->get('moderator', 'punishments').': '.$typeText. ', '.$language->get('user', 'username'). Output::getClean($banned_user->data()->username));

                                // Send alerts
                                // TODO

                            } else
                                $error = $language->get('moderator', 'cant_punish_admin');
                        } catch(Exception $e){
                            $error = $e->getMessage();
                        }
                    } else
                        $error = $language->get('moderator', 'enter_valid_punishment_reason');
                }
            } else
                $error = $language->get('general', 'invalid_token');
        }

        if(isset($error))
            $smarty->assign('ERROR', $error);

        if(Session::exists('user_punishment_success'))
            $smarty->assign('SUCCESS', Session::flash('user_punishment_success'));

        // Get any previous punishments
        $previous_punishments = $queries->orderWhere('infractions', 'punished = ' . $query->id, 'infraction_date', 'DESC');
        $previous_punishments_array = array();
        if(count($previous_punishments)){
            foreach($previous_punishments as $punishment){
                switch($punishment->type){
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

                $previous_punishments_array[] = array(
                    'type' => $type,
                    'type_numeric' => $punishment->type,
                    'revoked' => $punishment->revoked,
                    'acknowledged' => $punishment->acknowledged,
                    'reason' => Output::getClean($punishment->reason),
                    'issued_by_nickname' => Output::getClean($user->idToNickname($punishment->staff)),
                    'issued_by_profile' => URL::build('/profile/' . Output::getClean($user->idToName($punishment->staff))),
                    'issued_by_style' => $user->getGroupClass($punishment->staff),
                    'date_full' => date('d M Y, H:i', strtotime($punishment->infraction_date)),
                    'date_friendly' => $timeago->inWords($punishment->infraction_date, $language->getTimeLanguage()),
                    'revoke_link' => (($user->hasPermission('modcp.punishments.revoke')) ? URL::build('/mod/punishments/', 'user=' . $query->id . '&amp;do=revoke&amp;id=' . $punishment->id) : 'none'),
                    'confirm_revoke_punishment' => (($punishment->type == 2) ? $language->get('moderator', 'confirm_revoke_warning') : $language->get('moderator', 'confirm_revoke_ban'))
                );
            }
        }

        if($user->hasPermission('modcp.punishments.warn'))
          $smarty->assign('WARN', $language->get('moderator', 'warn'));

        if($user->hasPermission('modcp.punishments.ban'))
          $smarty->assign('BAN', $language->get('moderator', 'ban'));

        if($user->hasPermission('modcp.punishments.banip'))
          $smarty->assign('BAN_IP', $language->get('moderator', 'ban_ip'));

        $smarty->assign(array(
            'BACK_LINK' => URL::build('/mod/punishments'),
            'BACK' => $language->get('general', 'back'),
            'VIEWING_USER' => str_replace('{x}', Output::getClean($query->nickname), $language->get('moderator', 'viewing_user_x')),
            'PREVIOUS_PUNISHMENTS' => $language->get('moderator', 'previous_punishments'),
            'PREVIOUS_PUNISHMENTS_LIST' => $previous_punishments_array,
            'NO_PREVIOUS_PUNISHMENTS' => $language->get('moderator', 'no_previous_punishments'),
            'CANCEL' => $language->get('general', 'cancel'),
            'WARN_USER' => $language->get('moderator', 'warn_user'),
            'BAN_USER' => $language->get('moderator', 'ban_user'),
            'REASON' => $language->get('moderator', 'reason'),
            'REVOKED' => $language->get('moderator', 'revoked'),
            'REVOKE' => $language->get('moderator', 'revoke'),
            'ACKNOWLEDGED' => $language->get('moderator', 'acknowledged')
        ));

		// Load modules + template
	    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

	    $page_load = microtime(true) - $start;
	    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	    $template->onPageLoad();

	    require(ROOT_PATH . '/core/templates/navbar.php');
	    require(ROOT_PATH . '/core/templates/footer.php');

		// Display template
	    $template->displayTemplate('mod/punishments_user.tpl', $smarty);

    } else {
        // View all users
        $users = $queries->getWhere('users', array('id', '<>', 0));

        $user_array = array();
        foreach($users as $item){
            // Get groups
            $groups = $user->getAllGroups($item->id, true);
            $user_array[] = array(
                'username' => Output::getClean($item->username),
                'nickname' => Output::getClean($item->nickname),
                'profile' => URL::build('/profile/' . Output::getClean($item->username)),
                'style' => $user->getGroupClass($item->id),
                'groups' => $groups,
                'banned' => $item->isbanned,
                'punish_link' => URL::build('/mod/punishments/', 'user=' . $item->id)
            );
        }

        $smarty->assign(array(
            'VIEW_PUNISHMENTS' => $language->get('moderator', 'view_punishments'),
            'VIEW_PUNISHMENTS_LINK' => URL::build('/mod/punishments/', 'view=punishments'),
            'PUNISH' => $language->get('moderator', 'punish'),
            'USERNAME' => $language->get('user', 'username'),
            'GROUPS' => $language->get('moderator', 'groups'),
            'BANNED' => $language->get('moderator', 'banned'),
            'USERS' => $user_array
        ));

        $template->addJSFiles(array(
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/dataTables/jquery.dataTables.min.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/dataTables/dataTables.bootstrap4.min.js' => array(),
		));

        $template->addJSScript('
        $(document).ready(function() {
            $(\'.dataTables-users\').dataTable({
                responsive: true,
                language: {
                    "lengthMenu": "' . $language->get('table', 'display_records_per_page') . '",
                    "zeroRecords": "' . $language->get('table', 'nothing_found') . '",
                    "info": "' . $language->get('table', 'page_x_of_y') . '",
                    "infoEmpty": "' . $language->get('table', 'no_records') . '",
                    "infoFiltered": "' . $language->get('table', 'filtered') . '",
                    "search": "' . $language->get('general', 'search'). '",
                    "paginate": {
                        "next": "' . $language->get('general', 'next') . '",
                        "previous": "' . $language->get('general', 'previous') . '"
                    }
                }
            });
        });
        ');

		// Load modules + template
	    Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

	    $page_load = microtime(true) - $start;
	    define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	    $template->onPageLoad();

	    $smarty->assign('WIDGETS', $widgets->getWidgets());

	    require(ROOT_PATH . '/core/templates/navbar.php');
	    require(ROOT_PATH . '/core/templates/footer.php');

		// Display template
	    $template->displayTemplate('mod/punishments.tpl', $smarty);
    }
}