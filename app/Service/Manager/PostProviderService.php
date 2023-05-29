<?php

namespace App\Service\Manager;

use App\Api\Data\PostInterface;
use App\Api\Manager\PostProviderInterface;
use App\Model\Data\Post;
use Doctrine\ORM\EntityManager;

class PostProviderService implements PostProviderInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /** @inheritDoc */
    public function getById(int $id): ?PostInterface
    {
        return $this->entityManager->getRepository(Post::class)->findOneBy(['id' => $id]);
    }

    /** @inheritDoc */
    public function getBySlug(string $slug): ?PostInterface
    {
        return $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);
    }

    /**
     * @inheritDoc
     */
    public function getPostsByCategory(string $category): array
    {
        $query = $this->entityManager->getRepository(Post::class)->createQueryBuilder('posts')
            ->innerJoin('posts.categories', 'categories')
            ->where('categories.name = :category')
            ->setParameter('category', $category)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getPostsByAuthor(int $userId): array
    {
        return $this->entityManager->getRepository(Post::class)->findBy(['user' => $userId]);
    }

    /**
     * @inheritDoc
     */
    public function getRecentPosts(): array
    {
        return $this->entityManager->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC'], 5);
    }

    /**
     * @inheritDoc
     */
    public function save(PostInterface $post): PostInterface
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
        return $post;
    }
}
