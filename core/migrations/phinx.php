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
        ],
    ],
];
