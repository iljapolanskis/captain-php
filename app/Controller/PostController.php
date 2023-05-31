<?php

namespace App\Controller;

use App\Api\Manager\PostProviderInterface;
use App\DTO\Context;
use App\Model\Data\Post;
use App\Service\Manager\CategoryProviderService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PostController
{
    public function __construct(
        private readonly Context $context,
        private readonly Twig $twig,
        private readonly PostProviderInterface $postProvider,
        private readonly CategoryProviderService $categoryProvider,
    ) {}

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
        if (!isset($args['id'])) {
            $post = null;
        } else {
            $post = $this->postProvider->getById($args['id']);
        }

        $categories = array_map(
            static fn($category) => $category->getName(),
            $this->categoryProvider->getParentCategories()
        );

        return $this->twig->render($response, 'post/edit.twig', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        $post = (new Post())->setAuthor($this->context->getUser());

        if (isset($args['id'])) {
            $post = $this->postProvider->getById($args['id']);
        }

        $post->setTitle($request->getParsedBody()['title']);
        $post->setSlug($request->getParsedBody()['slug']);
        $post->setContent($request->getParsedBody()['content']);

        // Assign categories
        array_map(
            fn($category) => $post->addCategory($this->categoryProvider->getByName($category)),
            explode(',', $request->getParsedBody()['selected_categories'])
        );

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
