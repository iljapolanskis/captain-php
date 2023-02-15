<?php

declare(strict_types=1);

use App\Config;
use App\Controllers\HomeController;
use App\TwigCustomExtensions;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

const ROOT_DIR = __DIR__ . '/../';
const APP_DIR = ROOT_DIR . 'app/';
const STORAGE_PATH = ROOT_DIR . 'storage';
const VIEW_PATH = ROOT_DIR . 'views';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = new Container();

$container->set('Config', fn() => new Config($_ENV));

AppFactory::setContainer($container);

$app = AppFactory::create();

$twig = Twig::create(VIEW_PATH, [
    'cache' => STORAGE_PATH . '/cache',
    'auto_reload' => true,
]);

$twig->addExtension(new TwigCustomExtensions());

$app->addMiddleware(TwigMiddleware::create($app, $twig));

$app->get('/', [HomeController::class, 'index']);

$app->run();
