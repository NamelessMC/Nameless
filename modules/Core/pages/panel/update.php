<?php
/**
 * Staff panel update page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

if (!$user->handlePanelPageLoad('admincp.update')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

if (isset($_GET['recheck'])) {
    $cache->setCache('update_check');
    if ($cache->isCached('update_check')) {
        $cache->erase('update_check');
    }

    Redirect::to(URL::build('/panel/update'));
}

const PAGE = 'panel';
const PARENT_PAGE = 'update';
const PANEL_PAGE = 'update';
$page_title = $language->get('admin', 'update');
require_once ROOT_PATH . '/core/templates/backend_init.php';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success)) {
    $template->getEngine()->addVariables([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($errors) && count($errors)) {
    $template->getEngine()->addVariables([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

$cache->setCache('update_check');
$update_check = $cache->fetch('update_check', fn () => Util::updateCheck(), 3600);

if (!is_string($update_check)) {
    if ($update_check->updateAvailable()) {
        $template->getEngine()->addVariables([
            'NEW_UPDATE' => $update_check->isUrgent()
                ? $language->get('admin', 'new_urgent_update_available')
                : $language->get('admin', 'new_update_available'),
            'NEW_UPDATE_URGENT' => $update_check->isUrgent(),
            'CURRENT_VERSION' => $language->get('admin', 'current_version_x', [
                'version' => Output::getClean(NAMELESS_VERSION)
            ]),
            'NEW_VERSION' => $language->get('admin', 'new_version_x', [
                'version' => Output::getClean($update_check->versionTag())
            ]),
            'INSTRUCTIONS' => $language->get('admin', 'instructions'),
            'INSTRUCTIONS_VALUE' => Output::getDecoded($update_check->instructions()),
            'UPGRADE_LINK' => URL::build('/panel/upgrade'),
            'DOWNLOAD_LINK' => $update_check->upgradeZipLink(),
            'DOWNLOAD' => $language->get('admin', 'download'),
            'INSTALL_CONFIRM' => $language->get('admin', 'install_confirm'),
        ]);

        // Get backup information
        if ($user->hasPermission('admincp.core.backups')) {
            $latest_backup = null;
            $backups_dir = ROOT_PATH . '/backups/';
            if (is_dir($backups_dir)) {
                $backup_files = glob($backups_dir . '*.zip');
                if ($backup_files) {
                    // Sort by modification time (newest first)
                    usort($backup_files, function($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });

                    $latest_backup = [
                        'filename' => basename($backup_files[0]),
                        'date' => date(DATE_FORMAT, filemtime($backup_files[0])),
                        'date_formatted' => $language->get('admin', 'backup_created', [
                            'ago' => (new TimeAgo(TIMEZONE))->inWords(date(DATE_FORMAT, filemtime($backup_files[0])), $language)
                        ]),
                        'timestamp' => filemtime($backup_files[0])
                    ];
                }
            }

            $template->getEngine()->addVariables([
                'BACKUP_RECOMMENDATION' => $language->get('admin', 'backup_recommendation'),
                'BACKUP_BEFORE_UPDATE' => $language->get('admin', 'backup_before_update'),
                'MOST_RECENT_BACKUP' => $language->get('admin', 'most_recent_backup'),
                'NO_RECENT_BACKUP' => $language->get('admin', 'no_recent_backup'),
                'CREATE_BACKUP' => $language->get('admin', 'create_backup'),
                'MANAGE_BACKUPS' => $language->get('admin', 'manage_backups'),
                'CREATE_BACKUP_LINK' => URL::build('/panel/core/backups', 'action=create&token=' . Token::get()),
                'BACKUPS_PAGE_LINK' => URL::build('/panel/core/backups'),
                'LATEST_BACKUP' => $latest_backup,
            ]);
        }
    }
} else {
    $template->getEngine()->addVariable('UPDATE_CHECK_ERROR', $update_check);
}

$template->getEngine()->addVariables([
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

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate('core/update');
