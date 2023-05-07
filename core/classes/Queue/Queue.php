<?php
/**
 * Queue management class
 *
 * @package NamelessMC\Queue
 * @author Samerton
 * @version 2.1.0
 * @license MIT
 */

use \DI\Container;

class Queue {
    /**
     * Schedule a task
     *
     * @param Task $task
     * @return bool Whether the task was scheduled successfully or not
     */
    public static function schedule(Task $task): bool {
        if (!$task->getTask()) {
            return false;
        }

        $db = DB::getInstance();

        $db->query(
            <<<SQL
            INSERT INTO nl2_queue
                (
                 module_id,
                 task,
                 name,
                 data,
                 scheduled_at,
                 scheduled_for,
                 fragment,
                 fragment_total,
                 user_id,
                 entity,
                 entity_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            SQL,
            [
                $task->getModuleId(),
                $task->getTask(),
                $task->getName(),
                json_encode($task->getData()),
                date('U'),
                $task->getScheduledFor(),
                $task->getWillFragment(),
                $task->getFragmentTotal(),
                $task->getUserId(),
                $task->getEntity(),
                $task->getEntityId(),
            ]
        );

        return !$db->error();
    }

    /**
     * Process the next 5 tasks in the queue
     *
     * @param Container $container Dependency injection container
     *
     * @return array Processed tasks
     */
    public static function process(Container $container): array {
        $db = DB::getInstance();

        $pendingTasks = DB::getInstance()->query(
            <<<SQL
            SELECT `nl2_queue`.`id`, `nl2_queue`.`task`, `nl2_queue`.`name`, `nl2_queue`.`attempts`, `nl2_queue`.`output`
            FROM nl2_queue LEFT JOIN nl2_modules ON module_id=nl2_modules.id
            WHERE nl2_modules.enabled = 1 AND scheduled_for <= ? AND status IN (?, ?)
            ORDER BY scheduled_for
            LIMIT 5
            SQL,
            [
                date('U'),
                Task::STATUS_READY,
                Task::STATUS_ERROR,
            ]
        );

        $tasks = [];

        if ($pendingTasks->count()) {
            foreach ($pendingTasks->results() as $taskData) {
                $tasks[$taskData->id] = [
                    'attempts' => $taskData->attempts,
                    'name' => $taskData->name,
                    'output' => $taskData->output ? json_decode($taskData->output, true) : [],
                    'task' => $taskData->task,
                ];
            }

            $in = implode(',', array_map(static fn() => '?', $tasks));
            $db->query("UPDATE nl2_queue SET `status` = ? WHERE `id` IN ($in)", [Task::STATUS_IN_PROGRESS, ...array_keys($tasks)]);

            foreach ($tasks as $id => $task) {
                $attempts = $task['attempts'] + 1;
                $fragment = '';

                if (class_exists($task['task'])) {
                    DB::getInstance()->beginTransaction();
                    $rollback = false;

                    try {
                        /** @var Task $instance */
                        $instance = (new $task['task'])->fromId($id);
                        $instance->setContainer($container);

                        $status = $instance->run();
                        $output = $instance->getOutput();

                        if ($status == Task::STATUS_ERROR) {
                            if ($attempts >= 3) {
                                $status = Task::STATUS_FAILED;
                            }
                            $rollback = true;
                        } else {
                            $fragmentNext = $instance->getFragmentNext();
                            $fragment = $fragmentNext ? ',`fragment_next` = ?' : '';
                        }

                    } catch (Exception $e) {
                        $status = $attempts >= 3 ? Task::STATUS_FAILED : Task::STATUS_ERROR;
                        $output = ['error' => "Unable to execute task {$task['name']}: {$e->getMessage()}"];
                        $rollback = true;
                    }

                    if (!$rollback) {
                        DB::getInstance()->commitTransaction();
                    } else {
                        DB::getInstance()->rollBackTransaction();
                    }
                } else {
                    $status = $attempts >= 3 ? Task::STATUS_FAILED : Task::STATUS_ERROR;
                    $output = ['error' => "Unable to load class {$task['task']} for task {$task['name']}."];
                }

                // Output should be a multidimensional array for each time the task has been run
                $output = count($task['output']) ? [...$task['output'], $output] : [$output];

                // Params for update query
                $params = [$attempts, $status, json_encode($output), date('U')];

                if ($fragment && isset($fragmentNext)) {
                    $params[] = $fragmentNext;
                }

                $params[] = $id;

                $db->query(
                    <<<SQL
                    UPDATE nl2_queue
                    SET `attempts` = ?,
                        `status` = ?,
                        `output` = ?,
                        `executed_at` = ?$fragment
                    WHERE `id` = ?
                    SQL,
                    $params
                );
            }
        }

        return array_keys($tasks);
    }

    /**
     * Cancel a task by ID
     *
     * @param int $taskId
     */
    public static function cancelTaskById(int $taskId): void {
        DB::getInstance()->update('queue', $taskId, [
            'status' => Task::STATUS_CANCELLED,
        ]);
        Log::getInstance()->log(Log::Action('admin/core/queue/cancel_task'), $taskId);
    }

    /**
     * Requeue a task by ID
     *
     * @param int $taskId
     */
    public static function requeueTaskById(int $taskId): void {
        DB::getInstance()->update('queue', $taskId, [
            'status' => Task::STATUS_READY,
        ]);
        Log::getInstance()->log(Log::Action('admin/core/queue/requeue_task'), $taskId);
    }
}
