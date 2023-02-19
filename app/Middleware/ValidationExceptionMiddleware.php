<?php

namespace App\Middleware;

use App\Exception\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = $this->responseFactory->createResponse();

            $_SESSION['errors'] = $e->errors;

            $this->putOldDataIntoSession($request);

            $referer = $request->getHeaderLine('Referer');
            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }

    private function putOldDataIntoSession(ServerRequestInterface $request): void
    {
        $guarded = ['password', 'password-confirm', 'passwordConfirm'];
        $_SESSION['old'] = array_filter(
            $request->getParsedBody(),
            static fn($key) => ! in_array($key, $guarded, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}
