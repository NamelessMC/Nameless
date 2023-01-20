<?php
/*
 *  Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  Panel integrations page
 */

if (!$user->handlePanelPageLoad('admincp.integrations.edit')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'integrations';
const EDITING_USER = true;
$page_title = $language->get('admin', 'integrations');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$integrations = Integrations::getInstance();

if (!isset($_GET['integration'])) {
    // Get integrations list
    $integrations_list = [];
    foreach ($integrations->getAll() as $integration) {
        $integrations_list[] = [
            'name' => Output::getClean($integration->getName()),
            'icon' => Output::getClean($integration->getIcon()),
            'edit_link' => URL::build('/panel/core/integrations/', 'integration=' . $integration->getName()),
            'enabled' => $integration->isEnabled(),
            'can_unlink' => $integration->data()->can_unlink,
            'required' => $integration->data()->required,
        ];
    }

    $smarty->assign([
        'INTEGRATIONS_LIST' => $integrations_list,
        'ENABLED' => $language->get('admin', 'enabled'),
        'CAN_UNLINK' => $language->get('admin', 'can_unlink'),
        'REQUIRED' => $language->get('admin', 'required')
    ]);

    $template_file = 'core/integrations.tpl';
} else {
    // View integration settings
    $integration = $integrations->getIntegration($_GET['integration']);
    if ($integration === null) {
        Redirect::to(URL::build('/panel/core/integrations'));
    }

    if (Input::exists()) {
        $errors = [];

        if (Token::check()) {
            if (Input::get('action') === 'general_settings') {
                DB::getInstance()->update('integrations', $integration->data()->id, [
                    'enabled' => Output::getClean(Input::get('enabled')),
                    'can_unlink' => Output::getClean(Input::get('can_unlink')),
                    'required' => Output::getClean(Input::get('required'))
                ]);

                Session::flash('integrations_success', $language->get('admin', 'integration_updated_successfully'));
                Redirect::to(URL::build('/panel/core/integrations/', 'integration=' . $integration->getName()));
            }
        } else {
            $errors[] = $language->get('general', 'invalid_token');
        }
    }

    if ($integration->getSettings() !== null) {
        if (file_exists($integration->getSettings())) {
            require_once($integration->getSettings());
        } else {
            $errors[] = $language->get('admin', 'integration_settings_does_not_exist', [
                'integration' => Output::getClean($integration->getName())
            ]);
        }
    }

    $smarty->assign([
        'EDITING_INTEGRATION' => $language->get('admin', 'editing_integration_x', ['integration' => Output::getClean($integration->getName())]),
        'BACK' => $language->get('general', 'back'),
        'BACK_LINK' => URL::build('/panel/core/integrations'),
        'ENABLED' => $language->get('admin', 'enabled'),
        'ENABLED_VALUE' => $integration->isEnabled(),
        'CAN_UNLINK_INTEGRATION' => $language->get('admin', 'can_unlink_integration'),
        'CAN_UNLINK_VALUE' => $integration->data()->can_unlink,
        'REQUIRE_INTEGRATION' => $language->get('admin', 'require_integration'),
        'REQUIRED_VALUE' => $integration->data()->required,
    ]);

    $template_file = 'core/integrations_edit.tpl';
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'INTEGRATION' => $language->get('admin', 'integration'),
    'EDIT' => $language->get('general', 'edit'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
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
