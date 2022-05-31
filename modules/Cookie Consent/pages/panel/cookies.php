<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel cookies page
 */

if (!$user->handlePanelPageLoad('admincp.cookies')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'cookie_divider';
const PANEL_PAGE = 'cookie_settings';
$page_title = $cookie_language->get('cookie', 'cookies');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        $validation = Validate::check($_POST, [
            'cookies' => [
                Validate::REQUIRED => true,
                Validate::MAX => 100000
            ],
        ])->messages([
            'cookies' => $cookie_language->get('cookie', 'cookie_notice_error'),
        ]);

        if ($validation->passed()) {
            try {
                $cookie_id = DB::getInstance()->get('privacy_terms', ['name', 'cookies'])->results();
                if (count($cookie_id)) {
                    $cookie_id = $cookie_id[0]->id;

                    DB::getInstance()->update('privacy_terms', $cookie_id, [
                        'value' => Input::get('cookies')
                    ]);
                } else {
                    DB::getInstance()->insert('privacy_terms', [
                        'name' => 'cookies',
                        'value' => Input::get('cookies')
                    ]);
                }

                $success = $cookie_language->get('cookie', 'cookie_notice_success');
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        } else {
            $errors = $validation->errors();
        }
    } else {
        $errors[] = $language->get('general', 'invalid_token');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

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

// Get cookie notice
$cookies = DB::getInstance()->query('SELECT value FROM nl2_privacy_terms WHERE `name` = ?', ['cookies'])->first()->value;

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'COOKIES' => $cookie_language->get('cookie', 'cookies'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'COOKIE_NOTICE' => $cookie_language->get('cookie', 'cookie_notice'),
    'COOKIE_NOTICE_VALUE' => Output::getPurified($cookies),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('cookies/cookies.tpl', $smarty);
