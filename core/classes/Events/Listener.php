<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Event listener contract
 */

interface Listener {

    public static function execute(array $params = []): void;

}
