<?php

namespace App\Controller;

use App\Api\Manager\PostProviderInterface;
use App\Model\Data\Post;
use App\Service\Manager\CategoryProviderService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PostController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly PostProviderInterface $postProvider,
        private readonly CategoryProviderService $categoryProvider,
    ) {
    }

    public function view(Request $request, Response $response, array $args): Response
    {
        $post = $this->postProvider->getBySlug($request->getAttribute('slug'));
        $posts = $this->postProvider->getRecentPosts();

        return $this->twig->render($response, 'post/view.twig', [
            'post' => $post,
            'feed_posts' => $posts,
            'categories' => $this->categoryProvider->getParentCategories(),
        ]);
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $post = $this->postProvider->getById($args['id']);

        return $this->twig->render($response, 'post/edit.twig', [
            'post' => $post
        ]);
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        $post = $this->postProvider->getById($args['id']);

        if ($post === null) {
            $post = new Post();
        }

        $post->setContent($request->getParsedBody()['content']);

        $this->postProvider->save($post);

        return $this->twig->render($response, 'post/edit.twig', [
            'post' => $post
        ]);
    }

    public function list(Request $request, Response $response, array $args): Response
    {
        $category = $request->getAttribute('category');

        $posts = $category
            ? $this->postProvider->getPostsByCategory($category)
            : $this->postProvider->getRecentPosts();

        return $this->twig->render($response, 'post/list.twig', [
            'posts' => $posts,
            'feed_posts' => $this->postProvider->getRecentPosts(),
            'categories' => $this->categoryProvider->getParentCategories(),
        ]);
    }
}
