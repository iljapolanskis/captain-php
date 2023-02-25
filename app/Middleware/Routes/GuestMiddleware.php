<?php

namespace App\Middleware\Routes;

use App\Api\SessionInterface;
use App\Enum\SessionConstants;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly ResponseFactoryInterface $config
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->has(SessionConstants::User->value)) {
            return $this->config->createResponse()->withHeader('Location', '/')->withStatus(302);
        }

        return $handler->handle($request);
    }
}
