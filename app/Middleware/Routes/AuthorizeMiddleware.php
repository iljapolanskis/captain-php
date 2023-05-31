<?php

namespace App\Middleware\Routes;

use App\Api\AuthInterface;
use App\Enum\SessionConstants;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthorizeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly AuthInterface $auth,
        private readonly ResponseFactoryInterface $config
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($user = $this->auth->user()) {
            return $handler->handle($request->withAttribute(SessionConstants::User->value, $user));
        }

        return $this->config->createResponse()->withHeader('Location', '/login')->withStatus(302);
    }
}
