<?php

$config = [
    'appDir' => APP_DIR,
    'webDir' => WEB_DIR,
    'site' => [
        'name' => 'Форма обратной связи',
        'comments' => [
            'creation_settings' => [
                'max_file_size' => '2097152',   // 2M
                'image' => [
                    'types_allowed' => '.jpg, .gif, .png, image/jpeg, image/gif, image/png',
                    'types_allowed_mime' => ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png', 'image/x-png'],
                    'max_size' => ['width' => 320, 'height' => 240],
                    'max_thumb_size' => ['width' => 60, 'height' => 60]
                ],
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
    'debug' => false
];

$config['debug'] = !empty($_GET['debug']);

return $config;