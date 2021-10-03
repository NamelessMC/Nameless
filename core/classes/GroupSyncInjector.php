<?php

abstract class GroupSyncInjector
{

    /**
     * Get the name of the module this injector belongs to.
     * 
     * @return string Module name
     */
    abstract public function getModule();

    /**
     * Get a "friendly name" of this injector.
     * 
     * Used on Group Sync page as the label for the dropdown.
     * 
     * @return string Friendly name
     */
    abstract public function getName();

    /**
     * Get the name of the column to store this injector's group ID in,
     * in the database.
     * 
     * @return string Column name
     */
    abstract public function getColumnName();

    /**
     * Get the MySQL type to create the column with.
     * Example: bigint, varchar(64)...
     * 
     * @return string Column type
     */
    abstract public function getColumnType();

    /**
     * Determine if this injector should be enabled or not.
     * 
     * The GroupSyncManager will check if this injector's module is enabled or not.
     * 
     * @return bool Whether to enable this injector or not.
     */
    abstract public function shouldEnable();

    /**
     * Get the message to display in place of the group dropdown on the Group Sync page.
     * 
     * @param Language $language The logged in user's language to use for translations.
     *
     * @return string Not enabled message
     */
    abstract public function getNotEnabledMessage(Language $language);

    /**
     * Get a list of groups available for this injector.
     * Returned array must be in the shape of:
     * 
     * ```
     *      [
     * 
     *          'id' => ID of the group used by your service to apply the group by.
     * 
     *          'name' => Name of group to display in the dropdown on the Group Sync page.
     * 
     *      ]
     * ```
     * 
     * @return array
     */
    abstract public function getSelectionOptions();

    /**
     * Get an array of rules to validate Group Sync creation/update requests with for this group.
     * 
     * Should not be an issue unless people edit the input values in Inspect Element before submitting,
     * simply a safety measure.
     * 
     * Should be using the constants from the `Validate` class.
     * 
     * @return array Validation rules
     */
    abstract public function getValidationRules();

    /**
     * Get specific error messages to display for each validation rule their
     * form submission does not meet.
     * 
     * Can return an empty array to use automatically generated messages.
     * 
     * @param Language $language The logged in user's language to use for translations.
     * 
     * @return array Validation error messages
     */
    abstract public function getValidationMessages(Language $language);

    /**
     * Add this group to the user on your service.
     * 
     * Can do anything in here (go for a walk, call your API, write a book, etc),
     * as long as they user gets the group applied on your service!
     * 
     * @param User Instance of affected NamelessMC user.
     * @param mixed $group_id Native group ID to use for lookup on your service.
     * 
     * @return bool Whether it was successfully added or not
     */
    abstract public function addGroup(User $user, $group_id);

    /**
     * Remove this group from the user
     * 
     * @param User Instance of affected NamelessMC user.
     * @param mixed $group_id Native group ID to use for lookup on your service.
     * 
     * @return bool Whether it was successfully removed or not
     */
    abstract public function removeGroup(User $user, $group_id);

    /**
     * Get an array of NamelessMC group ID => this injector's group ID which are setup to sync
     * 
     * @return array[] Array of IDs
     */
    public function getAllSyncedGroupIds()
    {
        $nameless_injector = GroupSyncManager::getInstance()->getInjectorByClass(NamelessMCGroupSyncInjector::class);

        $group_ids = [];

        $group_ids_query = DB::getInstance()->get('group_sync', [
            $this->getColumnName(),
            '<>',
            'null',
        ])->results();

        foreach ($group_ids_query as $row) {
            $group_ids[$row->{$nameless_injector->getColumnName()}] = $row->{$this->getColumnName()};
        }

        return $group_ids;
    }

}
