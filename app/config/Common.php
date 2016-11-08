<?php

#define('DOCUMENT_ENCODING', 'UTF-8');

$config = [
    'appDir' => dirname(__DIR__),
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

#$config['database']['className'] = $config['database']['type'] . 'DatabaseComponent';

return $config;