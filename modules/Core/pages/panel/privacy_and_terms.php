<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel API page
 */

if (!$user->handlePanelPageLoad('admincp.core.terms')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'privacy_and_terms';
$page_title = $language->get('admin', 'privacy_and_terms');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    $errors = [];

    if (Token::check()) {
        $validation = Validate::check($_POST, [
            'privacy' => [
                Validate::REQUIRED => true,
                Validate::MAX => 100000
            ],
            'terms' => [
                Validate::REQUIRED => true,
                Validate::MAX => 100000
            ]
        ])->messages([
            'privacy' => $language->get('admin', 'privacy_policy_error'),
            'terms' => $language->get('admin', 'terms_error')
        ]);

        if ($validation->passed()) {
            try {
                $privacy_id = DB::getInstance()->get('privacy_terms', ['name', 'privacy'])->results();
                if (count($privacy_id)) {
                    $privacy_id = $privacy_id[0]->id;

                    DB::getInstance()->update('privacy_terms', $privacy_id, [
                        'value' => Input::get('privacy')
                    ]);
                } else {
                    DB::getInstance()->insert('privacy_terms', [
                        'name' => 'privacy',
                        'value' => Input::get('privacy')
                    ]);
                }

                $terms_id = DB::getInstance()->get('privacy_terms', ['name', 'terms'])->results();
                if (count($terms_id)) {
                    $terms_id = $terms_id[0]->id;

                    DB::getInstance()->update('privacy_terms', $terms_id, [
                        'value' => Input::get('terms')
                    ]);
                } else {
                    DB::getInstance()->insert('privacy_terms', [
                        'name' => 'terms',
                        'value' => Input::get('terms')
                    ]);
                }

                $success = $language->get('admin', 'terms_updated');
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

// Get privacy policy + terms
$site_terms = DB::getInstance()->get('privacy_terms', ['name', 'terms'])->results();
if (!count($site_terms)) {
    $site_terms = Util::getSetting('t_and_c_site');
} else {
    $site_terms = $site_terms[0]->value;
}

$site_privacy = DB::getInstance()->get('privacy_terms', ['name', 'privacy'])->results();
if (!count($site_privacy)) {
    $site_privacy = Util::getSetting('privacy_policy');
} else {
    $site_privacy = $site_privacy[0]->value;
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'PRIVACY_AND_TERMS' => $language->get('admin', 'privacy_and_terms'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'PRIVACY_POLICY' => $language->get('general', 'privacy_policy'),
    'PRIVACY_POLICY_VALUE' => Output::getPurified($site_privacy),
    'TERMS_AND_CONDITIONS' => $language->get('user', 'terms_and_conditions'),
    'TERMS_AND_CONDITIONS_VALUE' => Output::getPurified($site_terms)
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/privacy_and_terms.tpl', $smarty);
