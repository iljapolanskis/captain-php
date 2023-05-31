<?php

// phpcs:disable Generic.Files.LineLength.TooLong

use App\Api\AuthInterface;
use App\Api\ConfigProviderInterface;
use App\Api\Data\ContextInterface;
use App\Api\Manager\CategoryProviderInterface;
use App\Api\Manager\PostProviderInterface;
use App\Api\Manager\UserProviderInterface;
use App\Api\RequestValidatorFactoryInterface;
use App\Api\SessionInterface;
use App\DTO\Context;
use App\Exception\CsrfException;
use App\Model\Factory\RequestValidatorFactory;
use App\Service\AuthProviderService;
use App\Service\ConfigProviderService;
use App\Service\Manager\CategoryProviderService;
use App\Service\Manager\PostProviderService;
use App\Service\Manager\UserProviderService;
use App\Service\SessionProviderService;
use App\TwigFunctions;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Predis\Client as RedisClient;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use PubNub\PubNub;
use Slim\App;
use Slim\Csrf\Guard;
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
    /**
     * Application services
     */
    App::class => static function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $addMiddlewares = require CONFIG_PATH . '/middlewares.php';
        $addMiddlewares($app);

        $addRoutes = require CONFIG_PATH . '/routes.php';
        $addRoutes($app);

        return $app;
    },

    SessionInterface::class => static fn(ConfigProviderInterface $config) => new SessionProviderService($config->getSessionConfiguration()),
    ContextInterface::class => static fn(ContainerInterface $container) => $container->get(Context::class),
    RequestValidatorFactoryInterface::class => static fn(ContainerInterface $container) => $container->get(RequestValidatorFactory::class),

    ConfigProviderInterface::class => static fn() => new ConfigProviderService(require CONFIG_PATH . '/app.php'),
    AuthInterface::class => static fn(ContainerInterface $container) => $container->get(AuthProviderService::class),
    ResponseFactoryInterface::class => static fn(App $app) => $app->getResponseFactory(),

    /**
     * View services
     */
    Twig::class => static function (ConfigProviderInterface $config, ContainerInterface $container) {
        $twig = Twig::create(VIEW_PATH, [
            'cache' => STORAGE_PATH . '/cache',
            'auto_reload' => $config->isLocal(),
        ]);

        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new TwigFunctions($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },

    'webpack_encore.packages' => static fn() => new Packages(
        new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    ),
    'webpack_encore.tag_renderer' => static fn(ContainerInterface $container) => new TagRenderer(
        new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),
        $container->get('webpack_encore.packages')
    ),
    'csrf' => static fn(ResponseFactoryInterface $responseFactory) => new Guard(
        $responseFactory,
        failureHandler: fn() => throw new CsrfException('CSRF token validation failed'),
        persistentTokenMode: true
    ),

    /**
     * Storage services
     */
    Connection::class => static fn(ConfigProviderInterface $config) => DriverManager::getConnection(
        $config->get('doctrine.connection'),
        $config->getOrmConfiguration()
    ),
    EntityManager::class => static fn(Connection $connection, ConfigProviderInterface $config) => new EntityManager(
        $connection,
        $config->getOrmConfiguration()
    ),
    RedisClient::class => static fn(ConfigProviderInterface $config) => new RedisClient($config->getRedisConfiguration()),

    /**
     * Models Data & Managers
     */
    UserProviderInterface::class => static fn(ContainerInterface $container) => $container->get(UserProviderService::class),
    PostProviderInterface::class => static fn(ContainerInterface $container) => $container->get(PostProviderService::class),
    CategoryProviderInterface::class => static fn(ContainerInterface $container) => $container->get(CategoryProviderService::class),

    /**
     * Third party services
     */
    PubNub::class => static fn(ConfigProviderInterface $config) => new PubNub($config->getPubNubConfiguration()),
];
