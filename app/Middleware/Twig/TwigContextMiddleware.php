<?php

namespace App\Middleware\Twig;

use App\Api\AuthInterface;
use App\Api\Data\ContextInterface;
use App\Api\Manager\PostProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class TwigContextMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Twig $twig,
        private readonly AuthInterface $auth,
        private readonly ContextInterface $context,
        private readonly PostProviderInterface $postProvider,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: FeedPosts can become more personalized as per user's interests

        $this->context->setData([
            ContextInterface::REQUEST => $request,
            ContextInterface::USER => $this->auth->user(),
            ContextInterface::FEED_POSTS => $this->postProvider->getRecentPosts()
        ]);

        $this->twig->getEnvironment()->addGlobal('context', $this->context);

        return $handler->handle($request);
    }
}