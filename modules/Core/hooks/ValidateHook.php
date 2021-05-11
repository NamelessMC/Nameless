<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  Validate user hook handler class
 */

class ValidateHook {
    public static function validatePromote($params = array()) {
        if (!defined('VALIDATED_DEFAULT'))
            define('VALIDATED_DEFAULT', 1);

        $validate_user = new User($params['user_id']);
        if (!$validate_user->data()) {
            return false;
        }

        $validate_user->setGroup(VALIDATED_DEFAULT);

        Discord::updateDiscordRoles($validate_user, [VALIDATED_DEFAULT], [], new Language(), false);
    }
}
