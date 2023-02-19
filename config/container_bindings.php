<?php

use App\Config;
use App\TwigCustomExtensions;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use PubNub\PubNub;
use Slim\Views\Twig;
use Twig\Extra\Intl\IntlExtension;

return [
    Config::class => fn() => new Config($_ENV),
    Connection::class => fn(Config $config) => DriverManager::getConnection(
        $config->db,
        $config->getOrmConfiguration()
    ),
    EntityManager::class => fn(Connection $connection, Config $config) => new EntityManager(
        $connection,
        $config->getOrmConfiguration()
    ),
    Twig::class => function (Config $config) {
        $twig = Twig::create(VIEW_PATH, [
            'cache' => STORAGE_PATH . '/cache',
            'auto_reload' => $config->isLocal,
        ]);

        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new TwigCustomExtensions());

        return $twig;
    },
    PubNub::class => fn(Config $config) => new PubNub($config->getPubNubConfiguration())
];
