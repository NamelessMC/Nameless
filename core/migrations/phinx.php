<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../..');
}

$config = Config::get('mysql');

$dir = defined('PHINX_MIGRATIONS_DIR') ? PHINX_MIGRATIONS_DIR : (__DIR__ . '/../../migrations');
$table = defined('PHINX_DB_TABLE') ? PHINX_DB_TABLE : 'nl2_phinxlog';

return [
    'paths' => [
        'migrations' => __DIR__,
    ],
    'environments' => [
        'nameless' => [
            'adapter' => 'mysql',
            'host' => $config['host'],
            'name' => $config['db'],
            'user' => $config['username'],
            'pass' => $config['password'],
            'port' => $config['port'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'default_migration_table' => $table,
        ],
    ],
];
