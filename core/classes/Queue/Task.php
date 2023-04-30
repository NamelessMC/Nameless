<?php
/**
 * Abstract task class for queue task implementations
 *
 * @package NamelessMC\Queue
 * @author Samerton
 * @version 2.1.0
 * @license MIT
 */

use \DI\Container;

abstract class Task {
    /**
     * Cancelled status
     */
    public const STATUS_CANCELLED = 'cancelled';
    /**
     * Completed status
     */
    public const STATUS_COMPLETED = 'completed';
    /**
     * Error status
     */
    public const STATUS_ERROR = 'error';
    /**
     * Failed status - when task has been in error status 3 times
     */
    public const STATUS_FAILED = 'failed';
    /**
     * In progress status
     */
    public const STATUS_IN_PROGRESS = 'in_progress';
    /**
     * Ready status
     */
    public const STATUS_READY = 'ready';

    /**
     * @var ?int Task ID, only set for existing tasks in the database
     */
    private ?int $_id;

    /**
     * @var ?int Number of attempts already performed for this task
     */
    private ?int $_attempts;

    /**
     * @var ?string Entity which this task relates to
     */
    private ?string $_entity;

    /**
     * @var ?int ID of entity which this task relates to
     */
    private ?int $_entityId;

    /**
     * @var ?int Unix timestamp at which the task was last executed
     */
    private ?int $_executedAt;

    /**
     * @var int Module ID to which the task belongs
     */
    private int $_moduleId;

    /**
     * @var string Task status
     */
    private string $_status;

    /**
     * @var string Task class::function to be run
     */
    private string $_task;

    /**
     * @var string Human readable task name
     */
    private string $_name;

    /**
     * @var ?array Task data
     */
    private ?array $_data;

    /**
     * @var ?array Task output
     */
    private ?array $_output;

    /**
     * @var int Timestamp the task should be scheduled for
     */
    private int $_scheduledFor;

    /**
     * @var bool Whether to fragment the task or not
     */
    private bool $_fragment;

    /**
     * @var ?int Total number of items to process
     */
    private ?int $_fragmentTotal;

    /**
     * @var ?int Index of next item in fragment to process
     */
    private ?int $_fragmentNext;

    /**
     * @var ?int User ID that created the task
     */
    private ?int $_userId;

    /**
     * @var Container Dependency container
     */
    protected Container $_container;

    /**
     * Initialise new empty task
     */
    public function __construct() {
    }

    /**
     * Initialise task from ID
     * @param int $id
     *
     * @throws Exception
     * @return ?Task
     */
    public function fromId(int $id): ?Task {
        $task = DB::getInstance()->query('SELECT * FROM nl2_queue WHERE `id` = ?', [$id]);

        if ($task->count()) {
            $task = $task->first();
            $this->_attempts = $task->attempts;
            $this->_data = json_decode($task->data ?? '[]', true);
            $this->_entity = $task->entity;
            $this->_entityId = $task->entity_id;
            $this->_executedAt = $task->executed_at;
            $this->_fragment = boolval($task->fragment);
            $this->_fragmentNext = $task->fragment_next;
            $this->_fragmentTotal = $task->fragment_total;
            $this->_id = $task->id;
            $this->_moduleId = $task->module_id;
            $this->_name = $task->name;
            $this->_output = json_decode($task->output ?? '[]', true);
            $this->_scheduledFor = $task->scheduled_for;
            $this->_status = $task->status;
            $this->_task = $task->task;
            $this->_userId = $task->userId;

            return $this;
        }

        throw new Exception('Unable to find task ' . $id);
    }

    /**
     * Initialise new task
     * @param int $moduleId Module ID to which this task belongs
     * @param string $name Name of the task
     * @param ?array $data Any data which needs passing into the task when it executes
     * @param int $scheduledFor Unix timestamp representing the earliest time from which the task will be executed
     * @param ?string $entity Optional entity the task is associated with
     * @param ?int $entityId Optional entity ID the task is associated with
     * @param bool $fragment Whether to fragment the task's execution or not (split up into multiple runs)
     * @param ?int $fragmentTotal Total number of items which need processing if fragmenting
     * @param ?int $userId Optional user ID which triggered this task's execution
     *
     * @return Task
     */
    public function fromNew(
        int $moduleId,
        string $name,
        ?array $data,
        int $scheduledFor,
        ?string $entity = null,
        ?int $entityId = null,
        bool $fragment = false,
        ?int $fragmentTotal = null,
        ?int $userId = null
    ): Task {
        $this->_moduleId = $moduleId;
        $this->_name = $name;
        $this->_data = $data;
        $this->_scheduledFor = $scheduledFor;
        $this->_entity = $entity;
        $this->_entityId = $entityId;
        $this->_fragment = $fragment;
        $this->_fragmentTotal = $fragmentTotal;
        $this->_userId = $userId;
        $this->_task = get_called_class();

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->_id;
    }

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data = []) {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array {
        return $this->_data ?? [];
    }

    /**
     * @param array $output
     * @return void
     */
    public function setOutput(array $output = []) {
        $this->_output = $output;
    }

    /**
     * @param Container $container
     * @return void
     */
    public function setContainer(Container $container) {
        $this->_container = $container;
    }

    /**
     * @return array
     */
    public function getOutput(): array {
        return $this->_output ?? [];
    }

    /**
     * @return ?int
     */
    public function getAttempts(): ?int {
        return $this->_attempts;
    }

    /**
     * @return ?string
     */
    public function getEntity(): ?string {
        return $this->_entity;
    }

    /**
     * @return ?int
     */
    public function getEntityId(): ?int {
        return $this->_entityId;
    }

    /**
     * @return ?int
     */
    public function getExecutedAt(): ?int {
        return $this->_executedAt;
    }

    /**
     * @return int
     */
    public function getModuleId(): int {
        return $this->_moduleId;
    }

    /**
     * @return string
     */
    public function getTask(): string {
        return $this->_task;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * @return int
     */
    public function getScheduledFor(): int {
        return $this->_scheduledFor;
    }

    /**
     * Will this task be fragmented?
     * @return bool
     */
    public function getWillFragment(): bool {
        return $this->_fragment;
    }

    /**
     * @return ?int
     */
    public function getFragmentTotal(): ?int {
        return $this->_fragmentTotal;
    }

    /**
     * @param int $next Index to resume processing on next time the task is run
     * @return void
     */
    public function setFragmentNext(int $next) {
        $this->_fragmentNext = $next;
    }

    /**
     * @return ?int
     */
    public function getFragmentNext(): ?int {
        return $this->_fragmentNext;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->_status;
    }

    /**
     * @return ?int
     */
    public function getUserId(): ?int {
        return $this->_userId;
    }

    /**
     * Run the task
     * @return string Status of task following execution
     */
    abstract function run(): string;
}
