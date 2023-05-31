<?php

namespace App;

use App\Api\Data\ContextInterface;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctions extends AbstractExtension
{
    private ?ContextInterface $context = null;
    private ?RouteParserInterface $urlGenerator = null;

    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('active', [$this, 'active']),
            new TwigFunction('guest', [$this, 'guest']),
            new TwigFunction('auth', [$this, 'auth']),
            new TwigFunction('path_for', [$this, 'pathFor']),
        ];
    }

    public function active(string $url): bool
    {
        return $this->getContext()->getRequest()->getUri()->getPath() === $url;
    }

    private function getContext(): ContextInterface
    {
        if ($this->context === null) {
            $this->context = $this->container->get(ContextInterface::class);
        }

        return $this->context;
    }

    public function guest(): bool
    {
        return $this->getContext()->getUser() === null;
    }

    public function auth(): bool
    {
        return $this->getContext()->getUser() !== null;
    }

    public function pathFor(string $name, array $data = [], array $queryParams = []): string
    {
        return $this->getUrlGenerator()->urlFor($name, $data, $queryParams);
    }

    private function getUrlGenerator(): RouteParserInterface
    {
        if ($this->urlGenerator === null) {
            $this->urlGenerator = $this->container->get(App::class)->getRouteCollector()->getRouteParser();
        }

        return $this->urlGenerator;
    }
}
