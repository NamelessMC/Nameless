<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Hook contract
 */

namespace NamelessMC\Core\Events;

interface Hook {

    public static function execute(array $params = array()): void;

}
