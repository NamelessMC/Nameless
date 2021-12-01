<?php
/*
 * there is NO SUPPORT offered for this script.
 * this script is provided AS IS and without any warranty.
 * this script was made with the primary goal of making the install process automatic for hosting providers + our API test suite.
 */

$vars = [
    'mysql' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'username' => 'root',
        'password' => '',
        'db' => 'nameless',
        'prefix' => 'nl2_',
        'charset' => 'latin1',
        'engine' => 'InnoDB',
    ],
    'core' => [
        'hostname' => 'localhost',
        'path' => '',
        'friendly' => 'false',
    ],
    'admin_account' => [
        'username' => 'admin',
        'password' => '123456',
        'email' => 'contact@example.com',
        'uuid' => 'none',
    ],
    'language' => 'EnglishUK',
    'sitename' => 'NamelessMC',
];