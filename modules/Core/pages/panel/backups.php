<?php
/**
 * Staff panel backups page
 *
 * @author Aberdeener
 * @license MIT
 * @version 2.3.0
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

if (!$user->handlePanelPageLoad('admincp.backups')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'debugging_and_maintenance';
$page_title = $language->get('admin', 'backups');
require_once ROOT_PATH . '/core/templates/backend_init.php';

if (isset($_GET['action']) && $_GET['action'] == 'create') {
    if (Token::check($_GET['token'])) {
        $task = (new Backup())->fromNew(
            Module::getIdFromName('Core'),
            Backup::MANUAL_BACKUP,
            null,
            date('U'),
            null,
            null,
            false,
            null,
            $user->data()->id,
        );
        Queue::schedule($task);

        Session::flash('backup_success', $language->get('admin', 'backup_in_progress'));
        Redirect::to(URL::build('/panel/core/backups'));
    } else {
        Session::flash('backup_error', $language->get('general', 'invalid_token'));
    }
}

// Handle settings form submission
if (Input::exists() && Input::get('action') == 'settings') {
    if (Token::check()) {
        $max_retention = Input::get('max_backup_retention');
        $daily_scheduling = Input::get('daily_backup_scheduling') === '1' ? '1' : '0';

        Settings::set('backup_max_retention', $max_retention);
        Settings::set('backup_daily_scheduling', $daily_scheduling);

        // If daily scheduling is enabled, schedule the next backup, otherwise unschedule it
        if ($daily_scheduling) {
            Backup::scheduleNextDailyBackup();
        } else {
            Backup::unscheduleNextDailyBackup();
        }

        Session::flash('backup_success', $language->get('admin', 'backup_settings_updated'));
        Redirect::to(URL::build('/panel/core/backups'));
    } else {
        Session::flash('backup_error', $language->get('general', 'invalid_token'));
        Redirect::to(URL::build('/panel/core/backups'));
    }
}

$backups_dir = ROOT_PATH . '/cache/backups/';

// Handle download request
if (isset($_GET['download']) && !empty($_GET['download'])) {
    // Only allow root user to download backups
    if ($user->data()->id == 1) {
        $filename = basename($_GET['download']);
        $filepath = $backups_dir . $filename;

        if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) === 'zip') {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }
    } else {
        Session::flash('backup_error', $language->get('admin', 'only_root_user_can_download_backups'));
        Redirect::to(URL::build('/panel/core/backups'));
    }
}

// Get backup information
$backups = [];
if (is_dir($backups_dir)) {
    $backup_files = glob($backups_dir . '*.zip');
    if ($backup_files) {
        usort($backup_files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        foreach ($backup_files as $backup_file) {
            $backups[] = [
                'filename' => basename($backup_file),
                'date' => date(DATE_FORMAT, filemtime($backup_file)),
                'size' => Util::formatBytes(filesize($backup_file)),
                'download_link' => URL::build('/panel/core/backups', 'download=' . urlencode(basename($backup_file))),
            ];
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('backup_success')) {
    $template->getEngine()->addVariables([
        'SUCCESS' => Session::flash('backup_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (Session::exists('backup_error')) {
    $template->getEngine()->addVariables([
        'ERRORS' => [Session::flash('backup_error')],
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

$template->getEngine()->addVariables([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'DEBUGGING_AND_MAINTENANCE' => $language->get('admin', 'debugging_and_maintenance'),
    'PAGE' => PANEL_PAGE,
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/core/debugging_and_maintenance'),
    'TOKEN' => Token::get(),
    'BACKUPS' => $language->get('admin', 'backups'),
    'BACKUPS_INFO' => $language->get('admin', 'backups_info'),
    'CREATE_BACKUP' => $language->get('admin', 'create_backup'),
    'CREATE_BACKUP_LINK' => URL::build('/panel/core/backups', 'action=create&token=' . Token::get()),
    'EXISTING_BACKUPS' => $backups,
    'NO_BACKUPS' => $language->get('admin', 'no_backups'),
    'FILENAME' => $language->get('admin', 'filename'),
    'DATE_CREATED' => $language->get('admin', 'date_created'),
    'ACTIONS' => $language->get('general', 'actions'),
    'FILE_SIZE' => $language->get('admin', 'file_size'),
    'CAN_DOWNLOAD' => $user->data()->id == 1,
    'DOWNLOAD' => $language->get('admin', 'download'),
    'INFO' => $language->get('general', 'info'),
    'EXISTING' => $language->get('admin', 'existing_backups'),
    'BACKUP_SETTINGS' => $language->get('admin', 'backup_settings'),
    'MAX_BACKUP_RETENTION' => $language->get('admin', 'max_backup_retention'),
    'MAX_BACKUP_RETENTION_INFO' => $language->get('admin', 'max_backup_retention_info'),
    'MAX_BACKUP_RETENTION_VALUE' => Settings::get('backup_max_retention', '5'),
    'DAILY_BACKUP_SCHEDULING' => $language->get('admin', 'daily_backup_scheduling'),
    'DAILY_BACKUP_SCHEDULING_INFO' => $language->get('admin', 'daily_backup_scheduling_info'),
    'DAILY_BACKUP_SCHEDULING_VALUE' => Settings::get('backup_daily_scheduling', '0'),
    'ENABLED' => $language->get('admin', 'enabled'),
    'DISABLED' => $language->get('admin', 'disabled'),
    'SUBMIT' => $language->get('general', 'submit'),
]);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate('core/backups');
