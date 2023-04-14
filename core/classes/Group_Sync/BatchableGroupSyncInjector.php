<?php
/**
 * Represents a GroupSyncInjector which can add multiple groups to a {@see User} at once
 *
 * @package NamelessMC\GroupSync
 * @author Aberdeener
 * @version 2.0.3
 * @license MIT
 */
interface BatchableGroupSyncInjector {

    /**
     * Add multiple groups to a user at once.
     *
     * @param User $user The user to add groups to
     * @param array $group_ids Array of native group IDs to remove
     * @return false|array Array of group IDs and the status of the operation for each, or false if error
     */
    public function batchAddGroups(User $user, array $group_ids);

    /**
     * Remove multiple groups from a user at once.
     *
     * @param User $user The user to remove groups from
     * @param array $group_ids Array of native group IDs to remove
     * @return false|array Array of group IDs and the status of the operation for each, or false if error
     */
    public function batchRemoveGroups(User $user, array $group_ids);

}
