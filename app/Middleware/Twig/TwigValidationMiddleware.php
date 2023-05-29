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
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->has(SessionConstants::FormErrors->value)) {
            $this->twig->getEnvironment()->addGlobal(
                SessionConstants::FormErrors->value,
                $this->session->get(SessionConstants::FormErrors->value)
            );
            $this->session->forget(SessionConstants::FormErrors->value);
        }

        if ($this->session->has(SessionConstants::FormInput->value)) {
            $this->twig->getEnvironment()->addGlobal(
                SessionConstants::FormInput->value,
                $this->session->get(SessionConstants::FormInput->value)
            );
            $this->session->forget(SessionConstants::FormInput->value);
        }

        return $handler->handle($request);
    }
}
