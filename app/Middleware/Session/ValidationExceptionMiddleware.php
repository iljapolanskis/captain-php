<?php

namespace App\Middleware\Session;

use App\Api\SessionInterface;
use App\Enum\SessionConstants;
use App\Exception\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly ResponseFactoryInterface $responseFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = $this->responseFactory->createResponse();

            $this
                ->putErrorsIntoSession($e)
                ->putOldDataIntoSession($request);

            $referer = $request->getHeaderLine('Referer');
            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }

    private function putErrorsIntoSession(ValidationException $e): self
    {
        $this->session->put(SessionConstants::Errors->value, $e->errors);
        return $this;
    }

    private function putOldDataIntoSession(ServerRequestInterface $request): self
    {
        $guarded = ['password', 'passwordConfirm'];
        $this->session->put(
            SessionConstants::Old->value,
            array_diff_key($request->getParsedBody(), array_flip($guarded))
        );
        return $this;
    }
}
