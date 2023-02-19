<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/error_handling.php';

$container = require CONFIG_PATH . '/container.php';
$router = require CONFIG_PATH . '/routes.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addMiddleware(TwigMiddleware::create($app, $container->get(Twig::class)));

$router($app);

$app->run();
