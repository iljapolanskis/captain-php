<?php

declare(strict_types=1);

use App\Api\ConfigProviderInterface;
use App\Middleware\Session\StartSessionMiddleware;
use App\Middleware\Session\ValidationExceptionMiddleware;
use App\Middleware\Twig\CsrfFieldsMiddleware;
use App\Middleware\Twig\TwigContextMiddleware;
use App\Middleware\Twig\TwigValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return static function (App $app) {
    $container = $app->getContainer();

    if (!$container) {
        throw new RuntimeException('Container not found during application initialization');
    }

    $config = $container->get(ConfigProviderInterface::class);

    // Twig
    $app
        ->add(TwigContextMiddleware::class)
        ->add(CsrfFieldsMiddleware::class)
        ->add('csrf')
        ->add(TwigMiddleware::create($app, $container->get(Twig::class)))
        ->add(TwigValidationMiddleware::class)
        ->add(ValidationExceptionMiddleware::class)
        ->add(StartSessionMiddleware::class);

    // Define Custom Error Handler
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    ) use ($app) {
        $payload = ['fuck' => $exception->getMessage()];

        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response;
    };

    $errorMiddleware = $app->addErrorMiddleware(
        true,
        true,
        true
    );

//    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
//    $errorHandler->registerErrorRenderer('text/html', PageNotFoundRenderer::class);
};
