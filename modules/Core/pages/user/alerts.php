<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  User alerts page
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_alerts';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);

if (!isset($_GET['view'])) {
    if (!isset($_GET['action'])) {
        // Get alerts
        $alerts = DB::getInstance()->orderWhere('alerts', 'user_id = ' . $user->data()->id, 'created', 'DESC')->results();

        $alerts_limited = [];
        $n = 0;

        if (count($alerts) > 30) {
            $limit = 30;
        } else {
            $limit = count($alerts);
        }

        while ($n < $limit) {
            // Only display 30 alerts
            // Get date
            $alerts[$n]->date = date(DATE_FORMAT, $alerts[$n]->created);
            $alerts[$n]->date_nice = $timeago->inWords($alerts[$n]->created, $language);
            $alerts[$n]->view_link = URL::build('/user/alerts/', 'view=' . urlencode($alerts[$n]->id));

            $alerts_limited[] = $alerts[$n];

            $n++;
        }

        if (Session::exists('alerts_error')) {
            $smarty->assign('ERROR', Session::flash('alerts_error'));
        }

        // Language values
        $smarty->assign([
            'USER_CP' => $language->get('user', 'user_cp'),
            'ALERTS' => $language->get('user', 'alerts'),
            'ALERTS_LIST' => $alerts_limited,
            'DELETE_ALL' => $language->get('user', 'delete_all'),
            'DELETE_ALL_LINK' => URL::build('/user/alerts/', 'action=purge'),
            'CLICK_TO_VIEW' => $language->get('user', 'click_here_to_view'),
            'NO_ALERTS' => $language->get('user', 'no_alerts_usercp'),
            'TOKEN' => Token::get()
        ]);

        // Load modules + template
        Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

        require(ROOT_PATH . '/core/templates/cc_navbar.php');

        $template->onPageLoad();

        require(ROOT_PATH . '/core/templates/navbar.php');
        require(ROOT_PATH . '/core/templates/footer.php');

        // Display template
        $template->displayTemplate('user/alerts.tpl', $smarty);

    } else {
        if ($_GET['action'] == 'purge') {
            if (Token::check()) {
                DB::getInstance()->delete('alerts', ['user_id', $user->data()->id]);
            } else {
                Session::flash('alerts_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/user/alerts'));
        }
    }

} else {
    // Redirect to alert, mark as read
    if (!is_numeric($_GET['view'])) {
        Redirect::to(URL::build('/user/alerts'));
    }

    // Check the alert belongs to the user..
    $alert = DB::getInstance()->get('alerts', ['id', $_GET['view']])->results();

    if (!count($alert) || $alert[0]->user_id != $user->data()->id) {
        Redirect::to(URL::build('/user/alerts'));
    }

    if ($alert[0]->read == 0) {
        DB::getInstance()->update('alerts', $alert[0]->id, [
            'read' => true,
        ]);
    }

    Redirect::to($alert[0]->url != '#' ? $alert[0]->url : URL::build('/user/alerts'));
}
