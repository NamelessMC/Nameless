<?php

return [
    'paths' => [
        'migrations' => './core/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'nameless' => [
            'adapter' => 'mysql',
            'host' => PhinxAdapter::getConfig('host'),
            'name' => PhinxAdapter::getConfig('db'),
            'user' => PhinxAdapter::getConfig('username'),
            'pass' => PhinxAdapter::getConfig('password'),
            'port' => PhinxAdapter::getConfig('port'),
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
