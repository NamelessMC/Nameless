<?php
/**
 * User alerts page
 *
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */

/**
 * @var Cache $cache
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var Smarty $smarty
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_alerts';
$page_title = $language->get('user', 'alerts');
require_once ROOT_PATH . '/core/templates/frontend_init.php';

$timeAgo = new TimeAgo(TIMEZONE);

if (!isset($_GET['view'])) {
    if (!isset($_GET['action'])) {
        // Get alerts
        $alerts = [];
        $results = DB::getInstance()->query(
            <<<'SQL'
            SELECT
                `id`,
                `url`,
                `content`,
                `content_rich`,
                `created`,
                `read`
            FROM nl2_alerts
            WHERE `user_id` = ?
            ORDER BY `created` DESC LIMIT 30
            SQL,
            [$user->data()->id]
        );

        if ($results->count()) {
            $results = $results->results();

            $alerts = array_map(static fn ($alert) => [
                'id' => $alert->id,
                'title' => Output::getClean($alert->content),
                'content_rich' => Output::getPurified($alert->content_rich),
                'date' => date(DATE_FORMAT, $alert->created),
                'date_nice' => $timeAgo->inWords($alert->created, $language),
                'view_link' => URL::build('/user/alerts/', 'view=' . $alert->id),
                'read' => $alert->read,
            ], $results);
        }

        if (Session::exists('alerts_error')) {
            $smarty->assign('ERROR', Session::flash('alerts_error'));
        }

        // Language values
        $smarty->assign([
            'USER_CP' => $language->get('user', 'user_cp'),
            'ALERTS' => $language->get('user', 'alerts'),
            'ALERTS_LIST' => $alerts,
            'DELETE_ALL' => $language->get('user', 'delete_all'),
            'DELETE_ALL_LINK' => URL::build('/user/alerts/', 'action=purge'),
            'NO_ALERTS' => $language->get('user', 'no_alerts_usercp'),
        ]);

        // Load modules + template
        Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

        require ROOT_PATH . '/core/templates/cc_navbar.php';

        $template->onPageLoad();

        require ROOT_PATH . '/core/templates/navbar.php';
        require ROOT_PATH . '/core/templates/footer.php';

        // Display template
        $template->displayTemplate('user/alerts.tpl', $smarty);

    } elseif ($_GET['action'] == 'purge') {
        if (Token::check()) {
            DB::getInstance()->delete('alerts', ['user_id', $user->data()->id]);
        } else {
            Session::flash('alerts_error', $language->get('general', 'invalid_token'));
        }

        Redirect::to(URL::build('/user/alerts'));
    }

} else {
    // Redirect to alert, mark as read
    if (!is_numeric($_GET['view'])) {
        Redirect::to(URL::build('/user/alerts'));
    }

    // Check the alert belongs to the user...
    $alert = DB::getInstance()->get('alerts', ['id', $_GET['view']]);

    if (!$alert->count() || $alert->first()->user_id !== $user->data()->id) {
        Redirect::to(URL::build('/user/alerts'));
    }

    $alert = $alert->first();

    if (isset($_GET['delete'])) {
        if (Token::check()) {
            DB::getInstance()->delete('alerts', $alert->id);
            Redirect::to('/user/alerts');
        }

        Session::flash('alerts_error', $language->get('general', 'invalid_token'));
        Redirect::to(URL::build('/user/alerts', 'view=' . $alert->id));
    }

    if (!$alert->read) {
        DB::getInstance()->update('alerts', $alert->id, [
            'read' => true,
        ]);
    }

    if (!$alert->content_rich) {
        Redirect::to($alert->url && $alert->url !== '#' ? $alert->url : URL::build('/user/alerts'));
    }

    if (Session::exists('alerts_error')) {
        $smarty->assign('ERROR', Session::flash('alerts_error'));
    }

    if ($alert->url && $alert->url !== '#') {
        $smarty->assign([
            'VIEW' => $language->get('user', 'alerts_follow_link'),
            'VIEW_LINK' => urlencode($alert->url),
        ]);
    }

    $smarty->assign([
        'USER_CP' => $language->get('user', 'user_cp'),
        'ALERTS' => $language->get('user', 'alerts'),
        'DELETE' => $language->get('general', 'delete'),
        'DELETE_LINK' => URL::build('/user/alerts/', 'view=' . $alert->id . '&delete'),
        'ALERT_TITLE' => Output::getClean($alert->content),
        'ALERT_CONTENT' => $alert->bypass_purify ? $alert->content_rich : Output::getPurified($alert->content_rich),
        'ALERT_DATE' => date(DATE_FORMAT, $alert->created),
        'ALERT_DATE_NICE' => $timeAgo->inWords($alert->created, $language),
        'ALERT_READ' => $alert->read,
        'NEW' => $language->get('general', 'new'),
        'BACK' => $language->get('general', 'back'),
        'BACK_LINK' => URL::build('/user/alerts'),
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    require ROOT_PATH . '/core/templates/cc_navbar.php';

    $template->onPageLoad();

    require ROOT_PATH . '/core/templates/navbar.php';
    require ROOT_PATH . '/core/templates/footer.php';

    // Display template
    $template->displayTemplate('user/alert.tpl', $smarty);
}
