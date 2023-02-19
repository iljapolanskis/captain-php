<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

/**
 * Middleware injects errors into twig templates
 */
class AddValidationErrorsIntoTwigMiddleware implements MiddlewareInterface
{
    public function __construct(private Twig $twig) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            $this->twig->getEnvironment()->addGlobal('errors', $errors);
            unset($_SESSION['errors']);
        }

        if (isset($_SESSION['old'])) {
            $old = $_SESSION['old'];
            $this->twig->getEnvironment()->addGlobal('old', $old);
            unset($_SESSION['old']);
        }

        return $handler->handle($request);
    }
}
