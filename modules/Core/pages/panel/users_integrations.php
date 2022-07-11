<?php
/*
 *  Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel users page
 */

if (!$user->handlePanelPageLoad('admincp.users.edit')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'users';
const PANEL_PAGE = 'users';
const EDITING_USER = true;
$page_title = $language->get('admin', 'users');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/users'));
}

$view_user = new User($_GET['id']);
if (!$view_user->exists()) {
    Redirect::to(URL::build('/panel/users'));
}

if (!isset($_GET['action']) || !isset($_GET['integration'])) {
    $integrations_list = [];
    foreach (Integrations::getInstance()->getAll() as $integration) {
        $integrations_list[$integration->getName()] = [
            'name' => Output::getClean($integration->getName()),
            'icon' => Output::getClean($integration->getIcon()),
            'link' => URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id . '&action=link&integration=' . $integration->getName()),
            'edit' => URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id . '&action=edit&integration=' . $integration->getName())
        ];
    }

    $user_integrations_list = [];
    foreach ($view_user->getIntegrations() as $key => $integrationUser) {
        if ($integrationUser->data()->username === null && $integrationUser->data()->identifier === null) {
            continue;
        }

        $user_integrations_list[$key] = [
            'identifier' => Output::getClean($integrationUser->data()->identifier),
            'username' => Output::getClean($integrationUser->data()->username),
            'verified' => Output::getClean($integrationUser->isVerified())
        ];
    }

    $smarty->assign([
        'VIEWING_USER_INTEGRATIONS' => $language->get('admin', 'viewing_integrations_for_x', [
            'user' =>  Output::getClean($view_user->data()->username),
        ]),
        'INTEGRATION' => $language->get('admin', 'integration'),
        'INTEGRATIONS' => $integrations_list,
        'USER_INTEGRATIONS' => $user_integrations_list,
        'MANUAL_LINKING' => $language->get('admin', 'manual_linking'),
        'EDIT' => $language->get('general', 'edit'),
        'NOT_LINKED' => $language->get('admin', 'not_linked'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'ARE_YOU_SURE_MESSAGE' => $language->get('admin', 'unlink_account_confirm'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'UNLINK' => $language->get('admin', 'unlink'),
        'BACK_LINK' => URL::build('/panel/user/' . Output::getClean($view_user->data()->id . '-' . $view_user->data()->username)),
        'UNLINK_LINK' => URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id . '&action=unlink&integration='),
        'USERNAME' => $language->get('user', 'username'),
        'IDENTIFIER' => $language->get('admin', 'identifier'),
        'VERIFIED' => $language->get('admin', 'verified')
    ]);

    $template_file = 'core/users_integrations.tpl';
} else if (isset($_GET['integration'])) {
    switch ($_GET['action']) {
        case 'link':
            // Manual linking to integration (Integration User might already exist due of pending completion)
            $integration = Integrations::getInstance()->getIntegration($_GET['integration']);
            if ($integration === null) {
                Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));
            }

            $integrationUser = $view_user->getIntegration($_GET['integration']);
            if ($integrationUser != null && $integrationUser->data()->username != null && $integrationUser->data()->identifier != null) {
                Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));
            }

            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    if ($integration->validateUsername(Input::get('username')) && $integration->validateIdentifier(Input::get('identifier'))) {
                        if ($integrationUser === null) {
                            // Register new integration user
                            $code = uniqid('', true);

                            $integrationUser = new IntegrationUser($integration);
                            $integrationUser->linkIntegration($view_user, Output::getClean(Input::get('identifier')), Output::getClean(Input::get('username')), (bool) Output::getClean(Input::get('verified')), $code);

                            if (Output::getClean(Input::get('verified'))) {
                                $integrationUser->verifyIntegration();
                            }
                        } else {
                            // Update existing integration user
                            $integrationUser->update([
                                'username' => Output::getClean(Input::get('username')),
                                'identifier' => Output::getClean(Input::get('identifier')),
                                'verified' => Output::getClean(Input::get('verified'))
                            ]);
                        }

                        Session::flash('integrations_success', $language->get('admin', 'link_account_success', [
                            'user' => $view_user->getDisplayname(true),
                            'integration' => Output::getClean($integrationUser->getIntegration()->getName()),
                        ]));
                        Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));
                    } else {
                        $errors = $integration->getErrors();
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'INTEGRATION_TITLE' => $language->get('admin', 'linking_integration_for_x', [
                    'integration' => Output::getClean($integration->getName()),
                    'user' => Output::getClean($view_user->data()->username),
                ]),
                'USERNAME_VALUE' => ((isset($_POST['username']) && $_POST['username']) ? Output::getClean(Input::get('username')) : ''),
                'IDENTIFIER_VALUE' => ((isset($_POST['identifier']) && $_POST['identifier']) ? Output::getClean(Input::get('identifier')) : ''),
                'IS_VERIFIED' => $language->get('admin', 'is_verified'),
                'VERIFIED_VALUE' => ((isset($_POST['verified']) && $_POST['verified']) ? Output::getClean(Input::get('verified')) : 0),
                'BACK_LINK' => URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id),
                'USERNAME' => $language->get('admin', 'integration_username', ['integration' => Output::getClean($integration->getName())]),
                'IDENTIFIER' => $language->get('admin', 'integration_identifier', ['integration' => Output::getClean($integration->getName())]),
            ]);

            $template_file = 'core/users_integrations_form.tpl';

            break;

        case 'edit':
            // Edit integration user details
            $integrationUser = $view_user->getIntegration($_GET['integration']);
            if ($integrationUser === null) {
                Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));
            }

            $integration = $integrationUser->getIntegration();
            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    if (Input::get('action') == 'details') {
                        // Update integration user details
                        if ($integration->validateUsername(Input::get('username'), $integrationUser->data()->id) && $integration->validateIdentifier(Input::get('identifier'), $integrationUser->data()->id)) {
                            $integrationUser->update([
                                'username' => Output::getClean(Input::get('username')),
                                'identifier' => Output::getClean(Input::get('identifier')),
                                'verified' => Output::getClean(Input::get('verified'))
                            ]);

                            Session::flash('integrations_success', $language->get('admin', 'user_integration_updated_successfully'));
                            Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id . '&action=edit&integration=' . $integration->getName()));
                        } else {
                            $errors = $integration->getErrors();
                        }

                    } else if (Input::get('action') == 'sync') {
                        // Sync integration user
                        if ($integration->syncIntegrationUser($integrationUser)) {
                            Session::flash('integrations_success', $language->get('admin', 'user_integration_updated_successfully'));
                            Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id . '&action=edit&integration=' . $integration->getName()));
                        } else {
                            $errors = $integration->getErrors();
                        }
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'INTEGRATION_TITLE' => $language->get('admin', 'editing_integration_for_x', [
                    'integration' => Output::getClean($integration->getName()),
                    'user' => Output::getClean($view_user->data()->username),
                ]),
                'USERNAME_VALUE' => Output::getClean($integrationUser->data()->username),
                'IDENTIFIER_VALUE' => Output::getClean($integrationUser->data()->identifier),
                'IS_VERIFIED' => $language->get('admin', 'is_verified'),
                'VERIFIED_VALUE' => Output::getClean($integrationUser->isVerified()),
                'BACK_LINK' => URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id),
                'USERNAME' =>  $language->get('admin', 'integration_username', ['integration' => Output::getClean($integration->getName())]),
                'IDENTIFIER' => $language->get('admin', 'integration_identifier', ['integration' => Output::getClean($integration->getName())]),
                'SYNC_INTEGRATION' => $language->get('admin', 'sync_integration'),
            ]);

            $template_file = 'core/users_integrations_form.tpl';

            break;

        case 'unlink':
            // Unlink user from integration
            if (Input::exists()) {
                if (Token::check()) {
                    $integrationUser = $view_user->getIntegration($_POST['integration']);
                    if ($integrationUser != null) {
                        $integrationUser->unlinkIntegration();

                        Session::flash('integrations_success', $language->get('admin', 'unlink_account_success', [
                            'provider' => Output::getClean($integrationUser->getIntegration()->getName()),
                        ]));
                        Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));
                    }
                } else {
                    Session::flash('integrations_errors', $language->get('general', 'invalid_token'));
                }
            }

            Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));

        default:
            Redirect::to(URL::build('/panel/users/integrations/', 'id=' . $view_user->data()->id));
    }
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'USER_MANAGEMENT' => $language->get('admin', 'user_management'),
    'USERS' => $language->get('admin', 'users'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'USER_ID' => $view_user->data()->id,
    'BACK' => $language->get('general', 'back')
]);

if (Session::exists('integrations_success')) {
    $success = Session::flash('integrations_success');
}

if (Session::exists('integrations_errors')) {
    $errors = Session::flash('integrations_errors');
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

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
