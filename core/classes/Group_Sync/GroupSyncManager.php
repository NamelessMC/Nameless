<?php
/**
 * Manages registration of GroupSyncInjectors as well as broadcasting group changes to them.
 *
 * @package NamelessMC\GroupSync
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
final class GroupSyncManager extends Instanceable {

    /** @var GroupSyncInjector[] */
    private iterable $_injectors = [];
    /** @var GroupSyncInjector[] */
    private iterable $_enabled_injectors;

    /**
     * Register a new GroupSyncInjector class.
     * Ensures the column name of the new injector has not been taken.
     *
     * @param GroupSyncInjector $injector New injector.
     */
    public function registerInjector(GroupSyncInjector $injector): void {
        if (in_array($injector->getColumnName(), $this->getColumnNames())) {
            throw new RuntimeException("GroupSyncInjector column name {$injector->getColumnName()} already taken.");
        }

        $this->_injectors[$injector->getColumnName()] = $injector;
    }

    /**
     * Get the column name from all injectors, not just enabled ones.
     *
     * @return string[] All column names
     */
    public function getColumnNames(): array {
        return array_map(static function (GroupSyncInjector $injector) {
            return $injector->getColumnName();
        }, $this->_injectors);
    }

    /**
     * Add a new column to the `nl2_group_sync` table for this injector
     * to use. Should be called during module initialisation.
     *
     * @param GroupSyncInjector $injector Injector to add column for.
     */
    public function registerInjectorColumn(GroupSyncInjector $injector): void {
        DB::getInstance()->addColumn('group_sync', $injector->getColumnName(), "{$injector->getColumnType()} NULL DEFAULT NULL");
    }

    /**
     * Get all the registered injectors.
     *
     * @return GroupSyncInjector[] Registered injectors
     */
    public function getInjectors(): iterable {
        return $this->_injectors;
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
    public function makeValidator(array $source, Language $language): Validate {
        return Validate::check(
            $source, $this->compileValidatorRules()
        )->messages(
            $this->compileValidatorMessages($language)
        );
    }

    /**
     * Get an array of column name => validation rule array for each
     * of the enabled injectors.
     *
     * @return array<string, array<string, mixed>> Array of each enabled injectors array of rules
     */
    private function compileValidatorRules(): array {
        $rules = [];

        foreach ($this->getEnabledInjectors() as $injector) {
            $rules[$injector->getColumnName()] = $injector->getValidationRules();
        }

        return $rules;
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
    public function getEnabledInjectors(): iterable {
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
     * Get an array of column name => validation rule => validation messages array for each
     * of the enabled injectors.
     *
     * @return array<string, array<string, string>>
     */
    private function compileValidatorMessages(Language $language): array {
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
     * @param array $group_ids Array of Group IDs native to the sending injector which were added/removed to the user
     *
     * @return array Array of logs of changed groups
     */
    public function broadcastChange(User $user, string $sending_injector_class, array $group_ids): array {
        $sending_injector = $this->getInjectorByClass($sending_injector_class);

        if ($sending_injector === null) {
            return [];
        }

        $logs = [];

        $modified = [];

        $namelessmc_injector = $this->getInjectorByClass(NamelessMCGroupSyncInjector::class);
        $namelessmc_column = $namelessmc_injector->getColumnName();

        // Get all group sync rules where this injector is not null
        $rules = DB::getInstance()->query("SELECT * FROM nl2_group_sync WHERE {$sending_injector->getColumnName()} IS NOT NULL")->results();
        foreach ($rules as $rule) {
            if ($rule->website_group_id == PRE_VALIDATED_DEFAULT) {
                // Require atleast 1 group if default group is synced
                if (count($group_ids) === 0) {
                    return [];
                }

                break;
            }
        }

        $batched_changes = [];
        foreach ($rules as $rule) {

            foreach ($this->getEnabledInjectors() as $injector) {

                if ($injector == $sending_injector) {
                    continue;
                }

                $injector_class = get_class($injector);

                $batchable = $injector instanceof BatchableGroupSyncInjector;
                if ($batchable && !array_key_exists($injector_class, $batched_changes)) {
                    $batched_changes[$injector_class] = [
                        'add' => [],
                        'remove' => [],
                    ];
                }

                $injector_column = $injector->getColumnName();
                $injector_group_id = $rule->{$injector_column};
                $sending_group_id = $rule->{$sending_injector->getColumnName()};

                // Skip this injector if it doesn't have a group id setup for this rule
                if ($injector_group_id === null) {
                    continue;
                }

                if (!isset($modified[$injector_column])) {
                    $modified[$injector_column] = [];
                }

                // Skip this specific injector for this rule if we have already modified the user
                // with the same injector group id
                if (in_array($injector_group_id, $modified[$injector_column])) {
                    continue;
                }

                if (in_array($sending_group_id, $group_ids)) {
                    // Attempt to add group if this group id was sent in the broadcastChange() method
                    // and if they don't have the namelessmc equivilant of it
                    $modified[$injector_column][] = $injector_group_id;
                    if ($batchable) {
                        $batched_changes[$injector_class]['add'][] = $injector_group_id;
                    } elseif ($injector->addGroup($user, $injector_group_id)) {
                        $logs['added'][] = "{$injector_column} -> {$injector_group_id}";
                    }
                } else {
                    foreach ($rules as $item) {
                        if (in_array($item->{$sending_injector->getColumnName()}, $group_ids)) {
                            if ($item->{$namelessmc_column} == $rule->{$namelessmc_column}) {
                                continue 2;
                            }
                        }
                    }

                    // Attempt to remove this group if it doesn't have multiple rules, or if the group ids
                    // list sent to broadcastChange() was empty - NOT both
                    $modified[$injector_column][] = $injector_group_id;
                    if ($batchable) {
                        $batched_changes[$injector_class]['remove'][] = $injector_group_id;
                    } elseif ($injector->removeGroup($user, $injector_group_id)) {
                        $logs['removed'][] = "{$injector_column} -> {$injector_group_id}";
                    }
                }
            }
        }

        foreach ($batched_changes as $injector_class => $data) {
            /** @var GroupSyncInjector&BatchableGroupSyncInjector $injector */
            $injector = $this->getInjectorByClass($injector_class);
            $injector_column = $injector->getColumnName();

            $add = $data['add'];
            $remove = $data['remove'];

            if (count($add)) {
                $result = $injector->batchAddGroups($user, $add);
                if (is_array($result)) {
                    foreach ($result as $res) {
                        if ($res['status'] === 'added') {
                            $logs['added'][] = "{$injector_column} -> {$res['group_id']}";
                        }
                    }
                }
            }

            if (count($remove)) {
                $result = $injector->batchRemoveGroups($user, $remove);
                if (is_array($result)) {
                    foreach ($result as $res) {
                        if ($res['status'] === 'removed') {
                            $logs['removed'][] = "{$injector_column} -> {$res['group_id']}";
                        }
                    }
                }
            }
        }

        return $logs;
    }

    /**
     * Get an enabled `GroupSyncInjector` from its class name, if it exists.
     *
     * @param string $class Class name to get injector from
     *
     * @return GroupSyncInjector|null Instance of injector, null if it doesn't exist
     * or isn't enabled
     */
    public function getInjectorByClass(string $class): ?GroupSyncInjector {
        foreach ($this->getEnabledInjectors() as $injector) {
            if ($injector instanceof $class) {
                return $injector;
            }
        }

        return null;
    }
}
