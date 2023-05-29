<?php

namespace App\Middleware\Twig;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

/**
 * Add CSRF fields to Twig context
 */
class CsrfFieldsMiddleware implements MiddlewareInterface
{
    /**
     * @param \Slim\Views\Twig $twig
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(
        private readonly Twig $twig,
        private readonly ContainerInterface $container,
    ) {
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Slim\Csrf\Guard $csrf */
        $csrf = $this->container->get('csrf');

        $tokenNameKey = $csrf->getTokenNameKey();
        $tokenValueKey = $csrf->getTokenValueKey();
        $tokenName = $csrf->getTokenName();
        $tokenValue = $csrf->getTokenValue();

        $fields = <<<CSRF
                    <input type="hidden" name="{$tokenNameKey}" value="{$tokenName}">
                    <input type="hidden" name="{$tokenValueKey}" value="{$tokenValue}">
        CSRF;


        $this->twig->getEnvironment()->addGlobal('csrf', [
            'keys' => [
                'name' => $tokenNameKey,
                'value' => $tokenValueKey,
            ],
            'name' => $tokenName,
            'value' => $tokenValue,
            'fields' => $fields,
        ]);

        return $handler->handle($request);
    }
}
