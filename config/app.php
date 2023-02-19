<?php

use App\Enum\AppEnvironment;

$appEnv = $_ENV['APP_ENV'] ?? AppEnvironment::Live->value;

return [
    'app_name' => $_ENV['APP_NAME'],
    'app_version' => $_ENV['APP_VERSION'] ?? '1.0',
    'app_environment' => $appEnv,
    'display_error_details' => (bool)($_ENV['APP_DEBUG'] ?? 0),
    'log_errors' => true,
    'log_error_details' => true,
    'doctrine' => [
        'dev_mode' => AppEnvironment::isLocal($appEnv),
        'cache_dir' => STORAGE_PATH . '/cache/doctrine',
        'entity_dir' => [APP_PATH . '/Entity'],
        'connection' => [
            'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
        ],
    ],
    'pubnub' => [
        'publishKey' => $_ENV['PUBNUB_PUBLISH_KEY'],
        'subscribeKey' => $_ENV['PUBNUB_SUBSCRIBE_KEY'],
        'secretKey' => $_ENV['PUBNUB_SECRET_KEY'],
        'uuid' => $_ENV['PUBNUB_UUID'],
    ],
    'abstract' => [
        'apiKey' => $_ENV['ABSTRACT_EMAIL_API_KEY'],
    ]
];
