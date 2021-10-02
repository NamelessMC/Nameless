<?php

final class GroupSyncManager
{

    /** @var GroupSyncManager */
    private static $_instance;
    /** @var GroupSyncInjector[] */
    private $_injectors = [];
    /** @var GroupSyncInjector[] */
    private $_enabled_injectors;

    /**
     * Get a singleton instance of the GroupSyncManager
     * 
     * @return GroupSyncManager New or existing instance
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new GroupSyncManager();
        }

        return self::$_instance;
    }

    /**
     * Register a new GroupSyncInjector class.
     * 
     * Ensures the provided class name is a valid GroupSyncInjector instance,
     * and that the column name of the new injector has not been taken.
     * 
     * @param string $class Class name of new injector
     */
    public function registerInjector($class)
    {
        /** @var GroupSyncInjector */
        $injector = new $class();

        if (!($injector instanceof GroupSyncInjector)) {
            throw new Exception("Class: {$class} is not an instanceof " . GroupSyncInjector::class);
        }

        if (in_array($injector->getColumnName(), $this->getColumnNames())) {
            throw new Exception("GroupSyncInjector column name {$injector->getColumnName()} already taken, {$class} tried to use it as well.");
        }

        $this->addColumnToDb(
            $injector->getColumnName(),
            $injector->getColumnType()
        );

        $this->_injectors[$injector->getColumnName()] = $injector;
    }

    /**
     * Add a new column to the `nl2_group_sync` table for this injector
     * to use.
     * 
     * @param string $column_name Unique column name to use.
     * @param string $column_type Valid MySQL column type to assign the new column.
     */
    private function addColumnToDb($column_name, $column_type)
    {
        try {
            DB::getInstance()->alterTable('groups', $column_name, "{$column_type} NULL DEFAULT NULL");
        } catch (PDOException $ignored) {
        }
    }

    /**
     * Get all the registered injectors.
     * 
     * @return GroupSyncInjector[] Registered injectors
     */
    public function getInjectors()
    {
        return $this->_injectors;
    }

    /**
     * Get all injectors which should be enabled.
     * 
     * Injectors will only considered be enabled if:
     * - Their parent module is enabled.
     * - The `shouldEnable()` method in the injector returns true.
     * 
     * Keeps a cache for the duration of the request incase
     * any `shouldEnable()` method is intensive to execute.
     * 
     * @return GroupSyncInjector[] Enabled injectors
     */
    public function getEnabledInjectors()
    {
        if (!isset($this->_enabled_injectors)) {
            $this->_enabled_injectors = [];

            foreach ($this->_injectors as $injector) {
                if (
                    Util::isModuleEnabled($injector->getModule())
                    && $injector->shouldEnable()
                ) {
                    $this->_enabled_injectors[$injector->getColumnName()] = $injector;
                }
            }
        }

        return $this->_enabled_injectors;
    }

    /**
     * Get the column name from all injectors, not just enabled ones.
     * 
     * @return string[] All column names
     */
    public function getColumnNames()
    {
        $form_names = [];

        foreach ($this->_injectors as $injector) {
            $form_names[] = $injector->getColumnName();
        }

        return $form_names;
    }

    /**
     * Create a new `Validate` instance and add the injector defined 
     * rules and messages to it.
     * 
     * @param array $source Input array to validate, often `$_POST`
     * @param Language $language Language to use for error messages
     * 
     * @return Validate New `Validate` instance
     */
    public function makeValidator($source, Language $language)
    {
        return (new Validate)
            ->check($source, $this->compileValidatorRules())
            ->messages($this->compileValidatorMessages($language));
    }

    /**
     * Get an array of column name => validation rule array for each
     * of the enabled injectors.
     * 
     * @return array<string, array<string, mixed>> Array of each enabled injectors array of rules
     */
    private function compileValidatorRules()
    {
        $rules = [];

        foreach ($this->getEnabledInjectors() as $injector) {
            $rules[$injector->getColumnName()] = $injector->getValidationRules();
        }

        return $rules;
    }

    /**
     * Get an array of column name => validation rule => validation messages array for each
     * of the enabled injectors.
     * 
     * @return array<string, array<string, string>>
     */
    private function compileValidatorMessages(Language $language)
    {
        $messages = [];

        foreach ($this->getEnabledInjectors() as $column_name => $injector) {
            $messages[$column_name] = $injector->getValidationMessages($language);
        }

        return $messages;
    }

    /**
     * Execute respective `addGroup()` or `removeGroup()` function on each of the injectors
     * synced to the changed group.
     * 
     * @param User $user NamelessMC user to apply changes to
     * @param string $sending_injector_class Class name of injector broadcasting this change
     * @param array $group_ids Array of Group IDs native to the sending injector 
     * which were added/removed to the user 
     * 
     * @return array Array of logs of changed groups
     */
    public function broadcastChange(User $user, $sending_injector_class, $group_ids)
    {
        if (!count($group_ids)) {
            return [];
        }

        $sending_injector = $this->getInjectorByClass($sending_injector_class);

        if ($sending_injector == null) {
            return [];
        }

        $logs = [];

        foreach ($group_ids as $group_id) {
            $nameless_group_ids = $this->getNamelessGroupIds($sending_injector, $group_id);
            foreach ($nameless_group_ids as $nameless_group_id) {
                if (!in_array($nameless_group_id, $user->getAllGroupIds())) {
                    $added_log = $this->addGroup($user, $sending_injector, $group_id);
                    if (count($added_log)) {
                        $logs['added'][] = $added_log;
                    }
                } else {
                    $removed_log = $this->removeGroup($user, $sending_injector, $group_id);
                    if (count($removed_log)) {
                        $logs['removed'][] = $removed_log;
                    }
                }
            }
        }

        return $logs;
    }

    /**
     * Get an enabled `GroupSyncInjector` from it's class name, if it exists.
     * 
     * @param string $class Class name to get injector from
     * 
     * @return GroupSyncInjector|null Instance of injector, null if it doesnt exist
     * or isnt enabled
     */
    public function getInjectorByClass($class)
    {
        foreach ($this->getEnabledInjectors() as $injector) {
            if ($injector instanceof $class) {
                return $injector;
            }
        }

        return null;
    }

    /**
     * @return int[]
     */
    private function getNamelessGroupIds(GroupSyncInjector $sending_injector, $group_id)
    {
        $nameless_injector = $this->getInjectorByClass(NamelessMCGroupSyncInjector::class);

        $nameless_groups = DB::getInstance()->get('group_sync', [
            $sending_injector->getColumnName(),
            '=',
            $group_id,
        ])->results();

        $nameless_group_ids = [];

        foreach ($nameless_groups as $group) {
            $nameless_group_ids[] = $group->{$nameless_injector->getColumnName()};
        }

        return $nameless_group_ids;
    }

    /**
     * @return string[]
     */
    private function addGroup(User $user, GroupSyncInjector $sending_injector, $sending_group_id)
    {
        $added_log = [];

        foreach (
            $this->getSyncedInjectors($sending_injector, $sending_group_id) as $target_group_id => $target_injector
        ) {
            if ($target_injector->addGroup($user, $target_group_id)) {
                $added_log[] = $target_injector->getName() . '-' . $target_group_id;
            }
        }

        return $added_log;
    }

    /**
     * @return string[]
     */
    private function removeGroup(User $user, GroupSyncInjector $sending_injector, $sending_group_id)
    {
        $removed_log = [];

        foreach (
            $this->getSyncedInjectors($sending_injector, $sending_group_id) as $target_group_id => $target_injector
        ) {
            if ($target_injector->removeGroup($user, $target_group_id)) {
                $removed_log[] = $target_injector->getName() . '-' . $target_group_id;
            }
        }

        return $removed_log;
    }

    /**
     * @return array<string, GroupSyncInjector>
     */
    private function getSyncedInjectors(GroupSyncInjector $injector, $sending_group_id)
    {
        $syncs = DB::getInstance()->get('group_sync', [
            $injector->getColumnName(),
            '=',
            $sending_group_id,
        ])->results();

        $synced_injectors = [];

        foreach ($syncs as $row) {
            foreach ($this->getColumnNames() as $column) {
                if ($injector->getColumnName() == $column) {
                    continue;
                }

                if ($row->{$column} == null) {
                    continue;
                }

                if (isset($synced_injectors[$row->{$column}])) {
                    if ($synced_injectors[$row->{$column}] == $this->_enabled_injectors[$column]) {
                        continue;
                    }
                }

                if (isset($this->_enabled_injectors[$column])) {
                    $synced_injectors[$row->{$column}] = $this->_enabled_injectors[$column];
                }
            }
        }

        return $synced_injectors;
    }
}
