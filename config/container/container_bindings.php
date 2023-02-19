<?php

use App\Api\ConfigInterface;
use App\Config;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use PubNub\PubNub;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Extra\Intl\IntlExtension;

return [
    App::class => static function (ContainerInterface $container, Twig $twig) {

        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $addMiddlewares = require CONFIG_PATH . '/middlewares.php';
        $addMiddlewares($app);

        $addRoutes = require CONFIG_PATH . '/routes.php';
        $addRoutes($app);

        return $app;
    },
    ConfigInterface::class => static fn() => new Config(require CONFIG_PATH . '/app.php'),
    Connection::class => static fn(ConfigInterface $config) => DriverManager::getConnection(
        $config->get('doctrine.connection'),
        $config->getOrmConfiguration()
    ),
    EntityManager::class => static fn(Connection $connection, ConfigInterface $config) => new EntityManager(
        $connection,
        $config->getOrmConfiguration()
    ),
    Twig::class => static function (ConfigInterface $config, ContainerInterface $container) {
        $twig = Twig::create(VIEW_PATH, [
            'cache' => STORAGE_PATH . '/cache',
            'auto_reload' => $config->isLocal(),
        ]);

        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },
    /**
     * The following two bindings are needed for EntryFilesTwigExtension & AssetExtension to work for Twig
     */
    'webpack_encore.packages' => static fn() => new Packages(
        new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    ),
    'webpack_encore.tag_renderer' => static fn(ContainerInterface $container) => new TagRenderer(
        new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),
        $container->get('webpack_encore.packages')
    ),
    PubNub::class => static fn(ConfigInterface $config) => new PubNub($config->getPubNubConfiguration()),

    ResponseFactoryInterface::class => static fn(App $app) => $app->getResponseFactory(),
];
