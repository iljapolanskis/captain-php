<?php

declare(strict_types=1);

use App\Api\ConfigInterface;
use App\Middleware\AddValidationErrorsIntoTwigMiddleware;
use App\Middleware\SessionMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return static function (App $app) {
    $container = $app->getContainer();

    if (! $container) {
        throw new RuntimeException('Container not found during application initialization');
    }

    $config = $container->get(ConfigInterface::class);

    // Twig
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)))
        ->add(ValidationExceptionMiddleware::class)
        ->add(AddValidationErrorsIntoTwigMiddleware::class)
        ->add(SessionMiddleware::class);

    // Error Logger. Use FQCN because is dev dependency
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
