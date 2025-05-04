<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Validate user event listener handler class
 */

class ValidateHook {

    public static function execute(UserValidatedEvent $event): void {
        if (!defined('VALIDATED_DEFAULT') || VALIDATED_DEFAULT === null) {
            define('VALIDATED_DEFAULT', 1);
        }

        $validated_user = $event->user;

        $groups_before = array_keys($validated_user->getGroups());

        $validated_user->setGroup(VALIDATED_DEFAULT);

        $groups_after = array_keys($validated_user->getGroups());

        $groups_to_add = array_diff($groups_after, $groups_before);
        $groups_to_remove = array_diff($groups_before, $groups_after);

        GroupSyncManager::getInstance()->broadcastGroupChange(
            $validated_user,
            NamelessMCGroupSyncInjector::class,
            $groups_to_add,
            $groups_to_remove,
        );
    }
}
