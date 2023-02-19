<?php

namespace App\Middleware;

use App\Exception\SessionException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException('Session already started');
        }

        if (headers_sent($fileName, $lineNumber)) {
            throw new SessionException("Headers already sent in file '$fileName' on line $lineNumber");
        }

        session_start();

        $response = $handler->handle($request);

        session_write_close();

        return $response;
    }
}
