<?php
declare(strict_types=1);

/**
 * Minecraft group sync injector implementation.
 *
 * @package Modules\Core\Group_Sync
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class MinecraftGroupSyncInjector implements GroupSyncInjector {

    /**
     * Get the name of the module this injector belongs to.
     *
     * @return string Module name
     */
    public function getModule(): string {
        return 'Core';
    }

    /**
     * Get a "friendly name" of this injector.
     *
     * Used on Group Sync page as the label for the dropdown.
     *
     * @return string Friendly name
     */
    public function getName(): string {
        return 'Minecraft rank';
    }

    /**
     * Get the name of the column to store this injector's group ID in,
     * in the database.
     *
     * @return string Column name
     */
    public function getColumnName(): string {
        return 'ingame_rank_name';
    }

    /**
     * Get the MySQL type to create the column with.
     * Example: bigint, varchar(64)...
     *
     * @return string Column type
     */
    public function getColumnType(): string {
        return 'VARCHAR(64)';
    }

    /**
     * Determine if this injector should be enabled or not.
     *
     * The GroupSyncManager will check if this injector's module is enabled or not.
     *
     * @return bool Whether to enable this injector or not.
     */
    public function shouldEnable(): bool {
        return Util::getSetting('group_sync_mc_server') !== '0' && count($this->getSelectionOptions()) > 0;
    }

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
    public function getSelectionOptions(): array {
        $row = DB::getInstance()->query(
            'SELECT `groups` FROM `nl2_query_results` WHERE `server_id` = ? ORDER BY `id` DESC LIMIT 1',
            [Util::getSetting('group_sync_mc_server')]
        )->first();

        if ($row === null) {
            // Plugin is not set up and/or they did not select a server to source groups from/default server
            return [];
        }

        $groups = json_decode($row->groups, true);

        $cleaned_groups = [];

        foreach ($groups as $group) {
            $cleaned_groups[] = [
                'id' => Output::getClean($group),
                'name' => Output::getClean($group),
            ];
        }

        return $cleaned_groups;
    }

    /**
     * Get the message to display in place of the group dropdown on the Group Sync page.
     *
     * @param Language $language The logged-in user's language to use for translations.
     *
     * @return string Not enabled message
     */
    public function getNotEnabledMessage(Language $language): string {
        return $language->get('admin', 'group_sync_plugin_not_set_up');
    }

    /**
     * Get an array of rules to validate Group Sync creation/update requests with for this group.
     *
     * Should not be an issue unless people edit the input values in Inspect Element before submitting,
     * simply a safety measure.
     *
     * Should be using the constants from the `Validate` class.
     *
     * @return array Validation rules
     * @see Validate
     *
     */
    public function getValidationRules(): array {
        return [
            Validate::MIN => 2,
            Validate::MAX => 64,
        ];
    }

    /**
     * Get specific error messages to display for each validation rule their
     * form submission does not meet.
     *
     * Can return an empty array to use automatically generated messages.
     *
     * @param Language $language The logged-in user's language to use for translations.
     *
     * @return array Validation error messages
     */
    public function getValidationMessages(Language $language): array {
        return [
            Validate::MIN => $language->get('admin', 'group_name_minimum'),
            Validate::MAX => $language->get('admin', 'ingame_group_maximum')
        ];
    }

    /**
     * Add this group to the user on your service.
     *
     * Can do anything in here (go for a walk, call your API, write a book, etc.),
     * as long as the user gets the group applied on your service!
     *
     * @param User $user Instance of affected NamelessMC user.
     * @param mixed $group_id Native group ID to use for lookup on your service.
     *
     * @return bool Whether the group was successfully added or not
     */
    public function addGroup(User $user, $group_id): bool {
        // Nothing to do here, changes will get picked up by plugin
        return true;
    }

    /**
     * Remove this group from the user
     *
     * @param User $user Instance of affected NamelessMC user.
     * @param mixed $group_id Native group ID to use for lookup on your service.
     *
     * @return bool Whether the group was successfully removed or not
     */
    public function removeGroup(User $user, $group_id): bool {
        // Nothing to do here, changes will get picked up by plugin
        return true;
    }
}
