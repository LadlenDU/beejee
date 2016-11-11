<?php

$config = [
    'appDir' => dirname(__DIR__),
    'site' => [
        'name' => 'Форма обратной связи',
        'comments' => [
            'creation_settings' => [
                'max_file_size' => '3000000',
                'types_allowed' => '.jpg, .gif, .png, image/jpeg, image/gif, image/png'
            ]
        ]
    ],
    'database' => [
        'type' => 'Mysqli',
        'connection' => [
            'host' => 'localhost',
            'user' => 'root',
            'password' => 'temp123',
            'databaseName' => 'beejee'
        ],
    ],
    // Log file info.
    'log' => [
        // Path to log file.
        'filePath' => dirname(__DIR__) . '/runtime/logs/app.log',
    ],
    'globalEncoding' => 'UTF-8',
    'csrf' => [
        'salt' => 'pfbr6as',
        'tokenName' => '_csrf',
    ],
    // Switch the debug mode
    'debug' => true
];

#$config['debug'] = !empty($_GET['debug']);

return $config;