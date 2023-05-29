<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\AuthInterface;
use App\Api\Manager\CategoryProviderInterface;
use App\Api\Manager\PostProviderInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly AuthInterface $auth,
        private readonly CategoryProviderInterface $categoryProvider,
        private readonly PostProviderInterface $postProvider
    ) {
    }

    public function index(Request $request, Response $response): Response
    {
        $user = $this->auth->user();
        return $this->twig->render($response, 'post/view.twig', [
            'user' => $user,
            'post' => $this->postProvider->getBySlug('test-post'),
            'categories' => $this->categoryProvider->getParentCategories(),
            'feed_posts' => $this->postProvider->getRecentPosts()
        ]);
    }
}
