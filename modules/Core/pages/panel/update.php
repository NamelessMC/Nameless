<?php
declare(strict_types=1);

/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel update page
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 */

use DebugBar\DebugBarException;

if (!$user->handlePanelPageLoad('admincp.update')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (isset($_GET['recheck'])) {
    $cache->setCacheName('update_check');
    if ($cache->hasCashedData('update_check')) {
        $cache->erase('update_check');
    }

    Redirect::to(URL::build('/panel/update'));
}

const PAGE = 'panel';
const PARENT_PAGE = 'update';
const PANEL_PAGE = 'update';
$page_title = $language->get('admin', 'update');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPageWithMessages($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template, $language, $success ?? null, $errors ?? null);

$cache->setCacheName('update_check');
if ($cache->hasCashedData('update_check')) {
    $update_check = $cache->retrieve('update_check');
} else {
    try {
        $update_check = Util::updateCheck();
    } catch (DebugBarException $ignored) {
    }
    $cache->store('update_check', $update_check, 3600);
}

if (!is_string($update_check)) {
    if ($update_check->updateAvailable()) {
        $smarty->assign([
            'NEW_UPDATE' => $update_check->isUrgent()
                ? $language->get('admin', 'new_urgent_update_available')
                : $language->get('admin', 'new_update_available'),
            'NEW_UPDATE_URGENT' => $update_check->isUrgent(),
            'CURRENT_VERSION' => $language->get('admin', 'current_version_x', [
                'version' => Output::getClean(NAMELESS_VERSION)
            ]),
            'NEW_VERSION' => $language->get('admin', 'new_version_x', [
                'version' => Output::getClean($update_check->version())
            ]),
            'INSTRUCTIONS' => $language->get('admin', 'instructions'),
            'INSTRUCTIONS_VALUE' => Output::getDecoded($update_check->instructions()),
            'UPGRADE_LINK' => URL::build('/panel/upgrade'),
            'DOWNLOAD_LINK' => $update_check->upgradeZipLink(),
            'DOWNLOAD' => $language->get('admin', 'download'),
            'INSTALL_CONFIRM' => $language->get('admin', 'install_confirm')
        ]);
    }
} else {
    $smarty->assign([
        'UPDATE_CHECK_ERROR' => $update_check,
    ]);
}

// PHP version check
if (PHP_VERSION_ID < 70400) {
    $smarty->assign('PHP_WARNING', $language->get('admin', 'upgrade_php_version'));

    if (NAMELESS_VERSION !== '2.0.0-pr11') {
        $smarty->assign('PREVENT_UPGRADE', true);
    }
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'UPDATE' => $language->get('admin', 'update'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'UP_TO_DATE' => $language->get('admin', 'up_to_date'),
    'CHECK_AGAIN' => $language->get('admin', 'check_again'),
    'CHECK_AGAIN_LINK' => URL::build('/panel/update/', 'recheck=true'),
    'WARNING' => $language->get('general', 'warning'),
    'CANCEL' => $language->get('general', 'cancel'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
try {
    $template->displayTemplate('core/update.tpl', $smarty);
} catch (SmartyException $ignored) {
}
