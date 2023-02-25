<?php

declare(strict_types=1);

use App\Api\ConfigProviderInterface;
use App\Middleware\Session\StartSessionMiddleware;
use App\Middleware\Session\ValidationExceptionMiddleware;
use App\Middleware\Twig\TwigValidationMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return static function (App $app) {
    $container = $app->getContainer();

    if (! $container) {
        throw new RuntimeException('Container not found during application initialization');
    }

    $config = $container->get(ConfigProviderInterface::class);

    // Twig
    $app
        ->add(TwigMiddleware::create($app, $container->get(Twig::class)))
        ->add(TwigValidationMiddleware::class)
        ->add(ValidationExceptionMiddleware::class)
        ->add(StartSessionMiddleware::class);

    if ($config->isLocal()) {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        $whoops->register();
    } else {
        $app->addErrorMiddleware(
            false,
            true,
            true
        );
    }
};
