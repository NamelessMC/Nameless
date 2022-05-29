<?php

require __DIR__ . '/../config.php';
$conf = $conf['mysql'];

return [
    'paths' => [
        'migrations' => __DIR__,
    ],
    'environments' => [
        'nameless' => [
            'adapter' => 'mysql',
            'host' => $conf['host'],
            'name' => $conf['db'],
            'user' => $conf['username'],
            'pass' => $conf['password'],
            'port' => $conf['port'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'default_migration_table' => 'nl2_phinxlog',
        ],
    ],
];
