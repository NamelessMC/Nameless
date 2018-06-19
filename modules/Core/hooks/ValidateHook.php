<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  Validate user hook handler class
 */

class ValidateHook {
    public static function validatePromote($params = array()){
        $db = DB::getInstance();

        if(!defined('VALIDATED_DEFAULT'))
            define('VALIDATED_DEFAULT', 1);

        $db->createQuery("UPDATE nl2_users SET group_id = ? WHERE id = ?", array(VALIDATED_DEFAULT, $params['user_id']));
    }
}