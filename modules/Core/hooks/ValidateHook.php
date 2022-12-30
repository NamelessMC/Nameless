<?php

use GuzzleHttp\Exception\GuzzleException;

/**
 * Validate user event listener handler class
 *
 * @package Modules\Core\Hooks
 * @author Samerton
 * @version 2.0.0
 * @license MIT
 */
class ValidateHook extends HookBase {

    /**
     * @param array{user_id: ?string} $params
     *
     * @return void
     * @throws GuzzleException
     */
    public static function execute(array $params = ["user_id" => null]): void {
        if (!parent::validateParams($params, ["user_id"])) {
            return;
        }

        if (!defined('VALIDATED_DEFAULT') || VALIDATED_DEFAULT === null) {
            define('VALIDATED_DEFAULT', 1);
        }

        $validate_user = new User($params['user_id']);
        if (!$validate_user->exists()) {
            return;
        }

        $validate_user->setGroup((string)VALIDATED_DEFAULT);

        GroupSyncManager::getInstance()->broadcastChange(
            $validate_user,
            NamelessMCGroupSyncInjector::class,
            [VALIDATED_DEFAULT]
        );
    }
}
