<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  Panel queue page
 */

if (!$user->handlePanelPageLoad('admincp.core.queue')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'queue';
$page_title = $language->get('admin', 'queue');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('queue_success')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('queue_success'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (Session::exists('queue_error')) {
    $smarty->assign([
        'ERRORS' => Session::flash('queue_error'),
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

if (isset($_GET['view'])) {
    switch ($_GET['view']) {
        case 'status':
            // List queue tasks

            $smarty->assign([
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/core/queue'),
                'CANCEL' => $language->get('admin', 'queue_cancel_task'),
                'NAME' => $language->get('admin', 'queue_task_name'),
                'RETRY' => $language->get('admin', 'queue_retry_task'),
                'SCHEDULED_FOR' => $language->get('admin', 'queue_task_scheduled_for'),
                'STATUS' => $language->get('admin', 'queue_task_status'),
                'STATUS_CANCELLED' => $language->get('admin', 'queue_status_cancelled'),
                'STATUS_COMPLETED' => $language->get('admin', 'queue_status_completed'),
                'STATUS_ERROR' => $language->get('admin', 'queue_status_error'),
                'STATUS_FAILED' => $language->get('admin', 'queue_status_failed'),
                'STATUS_IN_PROGRESS' => $language->get('admin', 'queue_status_in_progress'),
                'STATUS_READY' => $language->get('admin', 'queue_status_ready'),
                'TASK' => $language->get('admin', 'queue_task'),
            ]);

            $template->assets()->include([
                AssetTree::DATATABLES,
            ]);

            $template->addJSScript('
                $(document).ready(function() {
                    const queueTable = $(\'.dataTables-queue\').DataTable({
                        columnDefs: [
                            { targets: [0], sClass: "hide" },
                            { "width": "25%", target: 1 },
                            { "width": "25%", target: 2 },
                            { "width": "25%", target: 3 },
                            { "width": "25%", target: 4 }
                        ],
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: "' . URL::build('/queries/queue_status') . '",
                        columns: [
                            { data: "id", hidden: true },
                            { data: "name" },
                            { data: "task" },
                            { data: "scheduled_for"},
                            { data: "status" }
                        ],
                        language: {
                            "lengthMenu": "' . $language->get('table', 'display_records_per_page') . '",
                            "zeroRecords": "' . $language->get('table', 'nothing_found') . '",
                            "info": "' . $language->get('table', 'page_x_of_y') . '",
                            "infoEmpty": "' . $language->get('table', 'no_records') . '",
                            "infoFiltered": "' . $language->get('table', 'filtered') . '",
                            "search": "' . $language->get('general', 'search') . '",
                            "paginate": {
                                "next": "' . $language->get('general', 'next') . '",
                                "previous": "' . $language->get('general', 'previous') . '"
                            }
                        }
                    });

                    $(\'.dataTables-queue\').on(\'click\', \'tr\', function(){
                        window.location.href = "' . URL::build('/panel/core/queue', 'view=task&id=') . '" + queueTable.row(this).data().id;
                    });
                });
            ');

            $template_file = 'core/queue_status.tpl';
            break;

        case 'task':
            // Individual task view
            $taskId = intval($_GET['id']);

            if (!$taskId) {
                Redirect::to(URL::build('/panel/core/queue', 'view=status'));
            }

            $task = DB::getInstance()->get('queue', ['id', $taskId]);

            if (!$task->count()) {
                Redirect::to(URL::build('/panel/core/queue', 'view=status'));
            }

            $task = $task->first();

            if (Input::exists()) {
                if (Token::check()) {
                    switch (Input::get('task_action')) {
                        case 'cancel':
                            if (in_array($task->status, [Task::STATUS_READY, Task::STATUS_ERROR, Task::STATUS_IN_PROGRESS])) {
                                Queue::cancelTaskById($taskId);
                                Session::put('queue_success', $language->get('admin', 'queue_task_cancelled_successfully'));
                            } else {
                                Session::put('queue_error', [$language->get('admin', 'queue_task_invalid_status_to_cancel')]);
                            }
                            break;

                        case 'requeue':
                            if (in_array($task->status, [Task::STATUS_FAILED, Task::STATUS_CANCELLED, Task::STATUS_IN_PROGRESS])) {
                                Queue::requeueTaskById($taskId);
                                Session::put('queue_success', $language->get('admin', 'queue_task_requeued_successfully'));
                            } else {
                                Session::put('queue_error', [$language->get('admin', 'queue_task_invalid_status_to_requeue')]);
                            }
                            break;

                        default:
                            Session::put('queue_error', [$language->get('admin', 'queue_task_action_invalid')]);
                    }
                } else {
                    // Invalid token
                    Session::put('queue_error', [$language->get('general', 'invalid_token')]);
                }

                Redirect::to(URL::build('/panel/core/queue', 'view=task&id=' . $taskId));
            }

            if (in_array($task->status, [Task::STATUS_READY, Task::STATUS_ERROR, Task::STATUS_IN_PROGRESS])) {
                $smarty->assign([
                    'CANCEL_TASK' => $language->get('admin', 'queue_cancel_task'),
                    'CONFIRM_CANCEL_TASK' => $language->get('admin', 'queue_cancel_task_confirm'),
                ]);
            }

            if (in_array($task->status, [Task::STATUS_FAILED, Task::STATUS_CANCELLED, Task::STATUS_IN_PROGRESS])) {
                $smarty->assign([
                    'CONFIRM_REQUEUE_TASK' => $language->get('admin', 'queue_requeue_task_confirm'),
                    'REQUEUE_TASK' => $language->get('admin', 'queue_requeue_task'),
                ]);
            }

            if ($task->user_id) {
                $taskUser = new User($task->user_id);

                if ($taskUser->exists()) {
                    $smarty->assign([
                        'TASK_TRIGGERED_BY' => $language->get('admin', 'queue_task_triggered_by'),
                        'TASK_USERNAME' => $taskUser->getDisplayname(),
                        'TASK_USERNAME_STYLE' => $taskUser->getGroupStyle(),
                        'TASK_AVATAR' => $taskUser->getAvatar(),
                        'TASK_PROFILE' => URL::build('/panel/user/' . Output::getClean($taskUser->data()->id)),
                        'PROFILE' => $language->get('general', 'profile'),
                    ]);
                }
            }

            $smarty->assign([
                'ACTIONS' => $language->get('general', 'actions'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'BACK' => $language->get('general', 'back'),
                'NO' => $language->get('general', 'no'),
                'QUEUE_LINK' => URL::build('/panel/core/queue'),
                'QUEUE_STATUS_LINK' => URL::build('/panel/core/queue', 'view=status'),
                'TASK' => $language->get('admin', 'queue_task'),
                'TASK_VALUE' => Output::getClean($task->task),
                'TASK_ENTITY' => $language->get('admin', 'queue_task_entity'),
                'TASK_ENTITY_VALUE' => Output::getClean($task->entity),
                'TASK_ENTITY_ID' => $language->get('admin', 'queue_task_entity_id'),
                'TASK_ENTITY_ID_VALUE' => Output::getClean($task->entity_id),
                'TASK_MODULE' => $language->get('admin', 'module'),
                'TASK_MODULE_VALUE' => Output::getClean(Module::getNameFromId($task->module_id)),
                'TASK_NAME' => $language->get('admin', 'queue_task_name'),
                'TASK_NAME_VALUE' => Output::getClean($task->name),
                'TASK_DATA' => $language->get('admin', 'queue_task_data'),
                'TASK_DATA_VALUE' => $task->data ? Output::getClean(json_encode(json_decode($task->data), JSON_PRETTY_PRINT)) : '',
                'TASK_OUTPUT' => $language->get('admin', 'queue_task_output'),
                'TASK_OUTPUT_VALUE' => $task->output ? Output::getClean(json_encode(json_decode($task->output), JSON_PRETTY_PRINT)) : '',
                'TASK_SCHEDULED_AT' => $language->get('admin', 'queue_task_scheduled_at'),
                'TASK_SCHEDULED_AT_VALUE' => date(DATE_FORMAT, $task->scheduled_at),
                'TASK_SCHEDULED_FOR' => $language->get('admin', 'queue_task_scheduled_for'),
                'TASK_SCHEDULED_FOR_VALUE' => date(DATE_FORMAT, $task->scheduled_for),
                'TASK_EXECUTED_AT' => $language->get('admin', 'queue_task_executed_at'),
                'TASK_EXECUTED_AT_VALUE' => $task->executed_at ? date(DATE_FORMAT, $task->executed_at) : '',
                'TASK_STATUS' => $language->get('admin', 'queue_task_status'),
                'TASK_STATUS_VALUE' => $language->get('admin', 'queue_status_' . $task->status),
                'TASK_FRAGMENT' => $language->get('admin', 'queue_task_fragment'),
                'TASK_FRAGMENT_VALUE' => $language->get('general', $task->fragment ? 'yes' : 'no'),
                'TASK_FRAGMENT_TOTAL' => $language->get('admin', 'queue_task_fragment_total'),
                'TASK_FRAGMENT_TOTAL_VALUE' => $task->fragment_total ?? 0,
                'TASK_FRAGMENT_NEXT' => $language->get('admin', 'queue_task_fragment_next'),
                'TASK_FRAGMENT_NEXT_VALUE' => $task->fragment_next ?? 0,
                'TASK_ATTEMPTS' => $language->get('admin', 'queue_task_attempts'),
                'TASK_ATTEMPTS_VALUE' => $task->attempts ?? 0,
                'YES' => $language->get('general', 'yes'),
            ]);

            $template_file = 'core/queue_task.tpl';
            break;

        default:
            Redirect::to(URL::build('/panel/core/queue'));
    }

} else {
    // Deal with input
    if (Input::exists()) {
        if (Token::check()) {
            // Validate input
            $validation = Validate::check($_POST, [
                'runner' => [
                    Validate::REQUIRED => true,
                    Validate::IN => ['ajax', 'cron']
                ],
                'interval' => [
                    Validate::REQUIRED => true,
                    Validate::NUMERIC => true,
                    Validate::AT_LEAST => 0.5
                ],
            ])->messages([
                'runner' => [
                    Validate::REQUIRED => $language->get('admin', 'queue_runner_required'),
                    Validate::IN => $language->get('admin', 'queue_runner_one_of'),
                ],
                'interval' => [
                    Validate::REQUIRED => $language->get('admin', 'queue_interval_required'),
                    Validate::NUMERIC => $language->get('admin', 'queue_interval_numeric'),
                    Validate::AT_LEAST => $language->get('admin', 'queue_interval_at_least_x', ['x' => '0.5']),
                ],
            ]);

            if ($validation->passed()) {
                Util::setSetting('queue_runner', Input::get('runner'));
                Util::setSetting('queue_interval', floatval(Input::get('interval')));

                Session::flash('queue_success', $language->get('user', 'settings_updated_successfully'));
            } else {
                // Validation error
                Session::put('queue_error', $validation->errors());
            }

        } else {
            // Invalid token
            Session::put('queue_error', [$language->get('general', 'invalid_token')]);
        }

        Redirect::to(URL::build('/panel/core/queue'));
    }

    $runners = [
        [
            'label' => $language->get('admin', 'queue_runner_ajax'),
            'selected' => Util::getSetting('queue_runner') == 'ajax',
            'value' => 'ajax',
        ],
        [
            'label' => $language->get('admin', 'queue_runner_cron'),
            'selected' => Util::getSetting('queue_runner') == 'cron',
            'value' => 'cron',
        ],
    ];

    if (!($cron_key = Util::getSetting('cron_key'))) {
        $cron_key = SecureRandom::alphanumeric();
        Util::setSetting('cron_key', $cron_key);
    }

    $smarty->assign([
        'SUBMIT' => $language->get('general', 'submit'),
        'INFO' => $language->get('general', 'info'),
        'QUEUE_INFO' => $language->get('admin', 'queue_info'),
        'QUEUE_CRON_URL' => rtrim(URL::getSelfURL(), '/') . URL::build('/queries/queue', 'cron&key=' . $cron_key),
        'QUEUE_INTERVAL' => $language->get('admin', 'queue_interval'),
        'QUEUE_INTERVAL_VALUE' => Util::getSetting('queue_interval', 1),
        'QUEUE_RUNNER' => $language->get('admin', 'queue_runner'),
        'QUEUE_RUNNERS' => $runners,
        'QUEUE_STATUS_LINK' => URL::build('/panel/core/queue', 'view=status'),
    ]);

    $template_file = 'core/queue.tpl';
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'QUEUE' => $language->get('admin', 'queue'),
    'QUEUE_STATUS' => $language->get('admin', 'queue_status'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
]);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate($template_file, $smarty);
