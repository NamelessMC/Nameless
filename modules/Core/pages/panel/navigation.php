<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel navigation page
 */

if (!$user->handlePanelPageLoad('admincp.core.navigation')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'navigation';
$page_title = $language->get('admin', 'navigation');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Deal with input
if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        // Valid token
        // Update cache
        $cache->setCache('navbar_order');
        if (isset($_POST['inputOrder']) && count($_POST['inputOrder'])) {
            foreach ($_POST['inputOrder'] as $key => $item) {
                if (is_numeric($item) && $item > 0) {
                    $cache->store($key . '_order', $item);
                }
            }
        }

        // Icons
        $cache->setCache('navbar_icons');
        if (isset($_POST['inputIcon']) && count($_POST['inputIcon'])) {
            foreach ($_POST['inputIcon'] as $key => $item) {
                if (is_numeric($key)) {
                    // Custom page?
                    $custom_page = DB::getInstance()->get('custom_pages', ['id', $key])->results();
                    if (count($custom_page)) {
                        DB::getInstance()->update('custom_pages', $key, [
                            'icon' => $item
                        ]);
                    }
                }
                $cache->store($key . '_icon', $item);
            }
        }

        $language->set('general', 'more', Output::getClean(Input::get('dropdown_name')));

        // Reload to update info
        Session::flash('navigation_success', $language->get('admin', 'navigation_settings_updated_successfully'));
        Redirect::to(URL::build('/panel/core/navigation'));
    }

    // Invalid token
    $errors[] = $language->get('general', 'invalid_token');
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('navigation_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('navigation_success'),
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
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'NAVIGATION' => $language->get('admin', 'navigation'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'INFO' => $language->get('general', 'info'),
    'NAVBAR_ORDER_INSTRUCTIONS' => $language->get('admin', 'navbar_order_instructions'),
    'NAVBAR_ICON_INSTRUCTIONS' => $language->get('admin', 'navbar_icon_instructions', [
        'faLink' => '<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" rel="noopener nofollow">Font Awesome</a>',
        'semLink' => '<a href="https://fomantic-ui.com/elements/icon.html" target="_blank" rel="noopener nofollow">Fomantic UI</a>'
    ]),
    'NAV_ITEMS' => $navigation->returnNav('top'),
    'NAVBAR_ORDER' => $language->get('admin', 'navbar_order'),
    'NAVBAR_ICON' => $language->get('admin', 'navbar_icon'),
    'DROPDOWN_ITEMS' => $language->get('admin', 'dropdown_items'),
    'DROPDOWN_NAME' => $language->get('admin', 'dropdown_name'),
    'DROPDOWN_NAME_VALUE' => $language->get('general', 'more')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/navigation.tpl', $smarty);
