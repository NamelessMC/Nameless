<?php
/**
 * Provides a baseline for custom group sync injectors.
 *
 * @package NamelessMC\GroupSync
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
interface GroupSyncInjector {

    /**
     * Get the name of the module this injector belongs to.
     *
     * @return string Module name
     */
    public function getModule(): string;

    /**
     * Get a "friendly name" of this injector.
     *
     * Used on Group Sync page as the label for the dropdown.
     *
     * @return string Friendly name
     */
    public function getName(): string;

    /**
     * Get the name of the column to store this injector's group ID in,
     * in the database.
     *
     * @return string Column name
     */
    public function getColumnName(): string;

    /**
     * Get the MySQL type to create the column with.
     * Example: bigint, varchar(64)...
     *
     * @return string Column type
     */
    public function getColumnType(): string;

    /**
     * Determine if this injector should be enabled or not.
     *
     * The GroupSyncManager will check if this injector's module is enabled or not.
     *
     * @return bool Whether to enable this injector or not.
     */
    public function shouldEnable(): bool;

    /**
     * Get the message to display in place of the group dropdown on the Group Sync page.
     *
     * @param Language $language The logged in user's language to use for translations.
     * @return string Not enabled message
     */
    public function getNotEnabledMessage(Language $language): string;

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
    public function getSelectionOptions(): array;

    /**
     * Get an array of rules to validate Group Sync creation/update requests with for this group.
     *
     * Should not be an issue unless people edit the input values in Inspect Element before submitting,
     * simply a safety measure.
     *
     * Should be using the constants from the `Validate` class.
     *
     * @see Validate
     *
     * @return array Validation rules
     */
    public function getValidationRules(): array;

    /**
     * Get specific error messages to display for each validation rule their
     * form submission does not meet.
     *
     * Can return an empty array to use automatically generated messages.
     *
     * @param Language $language The logged-in user's language to use for translations.
     * @return array Validation error messages
     */
    public function getValidationMessages(Language $language): array;

    /**
     * Add this group to the user on your service.
     *
     * Can do anything in here (go for a walk, call your API, write a book, etc),
     * as long as the user gets the group applied on your service!
     *
     * @param User $user Instance of affected NamelessMC user.
     * @param mixed $group_id Native group ID to use for lookup on your service.
     * @return bool Whether the group was successfully added or not
     */
    public function addGroup(User $user, $group_id): bool;

    /**
     * Remove this group from the user
     *
     * @param User $user Instance of affected NamelessMC user.
     * @param mixed $group_id Native group ID to use for lookup on your service.
     * @return bool Whether the group was successfully removed or not
     */
    public function removeGroup(User $user, $group_id): bool;
}
