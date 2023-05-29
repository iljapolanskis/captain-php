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
    'session' => [
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 600,
        'path' => $_ENV['SESSION_PATH'] ?? '/',
        'domain' => $_ENV['SESSION_DOMAIN'] ?? $_SERVER['HTTP_HOST'],
        'secure' => $_ENV['SESSION_SECURE'] ?? true,
        'httponly' => $_ENV['SESSION_HTTPONLY'] ?? true,
        'samesite' => $_ENV['SESSION_SAMESITE'] ?? 'Lax',
    ],
    'doctrine' => [
        'dev_mode' => AppEnvironment::isLocal($appEnv),
        'cache_dir' => STORAGE_PATH . '/cache/doctrine',
        'entity_dir' => [APP_PATH . '/Entity'],
        'connection' => [
            'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            'path' => $_ENV['DB_PATH'] ?? STORAGE_PATH . '/db.sqlite',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
        ],
    ],
    'redis' => [
        'tcp' => $_ENV['REDIS_TCP'] ?? 'tcp',
        'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
        'port' => $_ENV['REDIS_PORT'] ?? 6379,
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
