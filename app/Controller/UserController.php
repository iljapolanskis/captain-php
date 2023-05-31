<?php

namespace App\Controller;

use App\Api\Data\ContextInterface;
use App\Api\Manager\CategoryProviderInterface;
use App\Api\Manager\PostProviderInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class UserController
{
    public function __construct(
        private readonly ContextInterface $context,
        private readonly Twig $twig,
        private readonly PostProviderInterface $postProvider,
        private readonly CategoryProviderInterface $categoryProvider,
    ) {}

    public function dashboard(Request $request, Response $response, array $args): Response
    {
        return $this->twig->render($response, 'user/dashboard.twig', [
            'posts' => $this->context->getUser()?->getPosts() ?? [],
            'categories' => $this->categoryProvider->getAll() ?? [],
        ]);
    }
}
