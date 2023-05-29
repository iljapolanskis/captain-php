<?php

namespace App\Api\Manager;

use App\Api\Data\PostInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * Contract for post provider service
 */
interface PostProviderInterface
{
    /**
     * @param int $id
     * @return PostInterface|null
     */
    public function getById(int $id): ?PostInterface;

    /**
     * @param string $slug
     * @return PostInterface|null
     */
    public function getBySlug(string $slug): ?PostInterface;

    /**
     * @param string $category
     * @return PostInterface[]
     */
    public function getPostsByCategory(string $category): array;

    /**
     * @param int $userId
     * @return PostInterface[]
     */
    public function getPostsByAuthor(int $userId): array;

    /**
     * @return PostInterface[]
     */
    public function getRecentPosts(): array;

    /**
     * @param PostInterface $post
     * @return PostInterface
     * @throws ORMException
     */
    public function save(PostInterface $post): PostInterface;
}
