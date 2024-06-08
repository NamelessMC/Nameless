<?php
/**
 * Allows modules to define permissions.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class PermissionCalculator
{
    private PermissionCache $_permission_cache;

    public function __construct(PermissionCache $permission_cache)
    {
        $this->_permission_cache = $permission_cache;
    }

    public function userHasPermission(User $user, string $permission): bool
    {
        $user_permissions = $this->_permission_cache->getOrLoad(User::class, $user->data()->id);

        $result = $user_permissions[$permission] ?? PermissionTristate::INHERIT;

        if ($result === PermissionTristate::TRUE) {
            return true;
        }

        if ($result === PermissionTristate::FALSE) {
            return false;
        }

        if ($result === PermissionTristate::INHERIT) {
            foreach ($user->getGroups() as $group) {
                if ($this->groupHasPermission($group, $permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function groupHasPermission(Group $group, string $permission): bool
    {
        $result = $this->_permission_cache->getOrLoad(Group::class, $group->id)[$permission] ?? PermissionTristate::INHERIT;

        if ($result === PermissionTristate::TRUE) {
            return true;
        }

        if ($result === PermissionTristate::FALSE) {
            return false;
        }

        if ($result === PermissionTristate::INHERIT) {
            // if any of the groups with a lower order have the permission set to true or false, then this group inherits that value
            $inherit = false;
            $lower_order_groups = DB::getInstance()->query('SELECT id FROM nl2_groups WHERE `order` < ? ORDER BY `order`', [$group->order]);
            foreach ($lower_order_groups as $lower_order_group) {
                $result2 = $this->_permission_cache->getOrLoad(Group::class, $lower_order_group->id)[$permission] ?? PermissionTristate::INHERIT;
                if ($result2 === PermissionTristate::TRUE) {
                    $inherit = true;
                    break;
                } elseif ($result2 === PermissionTristate::FALSE) {
                    $inherit = false;
                    break;
                }
            }
            return $inherit;
        }

        return false;
    }
}