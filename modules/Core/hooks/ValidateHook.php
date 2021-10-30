<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Validate user event listener handler class
 */

class ValidateHook implements Listener {

    public static function execute(array $params = []): void {
        if (!defined('VALIDATED_DEFAULT')) {
            define('VALIDATED_DEFAULT', 1);
        }

        $validate_user = new User($params['user_id']);
        if (!$validate_user->data()) {
            return;
        }

        $validate_user->setGroup(VALIDATED_DEFAULT);

        GroupSyncManager::getInstance()->broadcastChange(
            $validate_user,
            NamelessMCGroupSyncInjector::class,
            [VALIDATED_DEFAULT]
        );
    }
}
