<?php
/*
 *  Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel forums page
 */

// Can the user view the panel?
if (!$user->handlePanelPageLoad('admincp.forums')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'forum';
const PANEL_PAGE = 'forum_settings';
$page_title = $forum_language->get('forum', 'forums');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
        $validation = Validate::check($_POST, [
            'news_items' => [
                Validate::MIN => 0,
                Validate::MAX => 20,
            ],
        ])->messages([
            'news_items' => [
                Validate::MIN => static fn($meta) => $forum_language->get('forum', 'news_items_min', $meta),
                Validate::MAX => static fn($meta) => $forum_language->get('forum', 'news_items_max', $meta),
            ],
        ]);

        if ($validation->passed()) {
            // Update link location
            if (isset($_POST['link_location'])) {
                switch ($_POST['link_location']) {
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        $location = $_POST['link_location'];
                        break;
                    default:
                        $location = 1;
                }
            } else {
                $location = 1;
            }

            // Update Link location cache
            $cache->setCache('nav_location');
            $cache->store('forum_location', $location);

            Util::setSetting('forum_reactions', (isset($_POST['use_reactions']) && $_POST['use_reactions'] == 'on') ? '1' : 0);
            Util::setSetting('news_items_front_page', $_POST['news_items'], 'forum');

            Session::flash('admin_forums_settings', $forum_language->get('forum', 'settings_updated_successfully'));
        } else {
            Session::put('admin_forums_settings_errors', $validation->errors());
        }
    } else {
        // Invalid token
        Session::put('admin_forums_settings_errors', [$language->get('general', 'invalid_token')]);
    }
    Redirect::to(URL::build('/panel/forums/settings'));
}

// Retrieve Link Location from cache
$cache->setCache('nav_location');
$link_location = $cache->retrieve('forum_location');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_forums_settings')) {
    $success = Session::flash('admin_forums_settings');
}

if (Session::exists('admin_forums_settings_errors')) {
    $errors = Session::flash('admin_forums_settings_errors');
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
    'FORUM' => $forum_language->get('forum', 'forum'),
    'SETTINGS' => $language->get('admin', 'settings'),
    'LINK_LOCATION' => $language->get('admin', 'page_link_location'),
    'LINK_LOCATION_VALUE' => $link_location,
    'LINK_NAVBAR' => $language->get('admin', 'page_link_navbar'),
    'LINK_MORE' => $language->get('admin', 'page_link_more'),
    'LINK_FOOTER' => $language->get('admin', 'page_link_footer'),
    'LINK_NONE' => $language->get('admin', 'page_link_none'),
    'USE_REACTIONS' => $forum_language->get('forum', 'use_reactions'),
    'USE_REACTIONS_VALUE' => Util::getSetting('forum_reactions') === '1',
    'NEWS_ITEMS_ON_FRONT_PAGE' => $forum_language->get('forum', 'news_items_front_page_limit'),
    'NEWS_ITEMS_ON_FRONT_PAGE_VALUE' => Util::getSetting('news_items_front_page', 5, 'forum'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('forum/forums_settings.tpl', $smarty);
