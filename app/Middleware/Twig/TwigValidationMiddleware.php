<?php

namespace App\Middleware\Twig;

use App\Api\SessionInterface;
use App\Enum\SessionConstants;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

/**
 * Middleware injects errors into twig templates
 */
class TwigValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly Twig $twig
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->has(SessionConstants::Errors->value)) {
            $this->twig->getEnvironment()->addGlobal(
                SessionConstants::Errors->value,
                $this->session->get(SessionConstants::Errors->value)
            );
            $this->session->forget(SessionConstants::Errors->value);
        }

        if ($this->session->has(SessionConstants::Old->value)) {
            $this->twig->getEnvironment()->addGlobal(
                SessionConstants::Old->value,
                $this->session->get(SessionConstants::Old->value)
            );
            $this->session->forget(SessionConstants::Old->value);
        }

        return $handler->handle($request);
    }
}
