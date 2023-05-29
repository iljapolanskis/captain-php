<?php

namespace App\Api\Manager;

use App\Api\Data\CategoryInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * Contract for post provider service
 */
interface CategoryProviderInterface
{
    /**
     * @param int $id
     * @return CategoryInterface|null
     */
    public function getById(int $id): ?CategoryInterface;

    /**
     * @param string $name
     * @return CategoryInterface|null
     */
    public function getByName(string $name): ?CategoryInterface;

    /**
     * @return CategoryInterface[]
     */
    public function getAll(): array;

    /**
     * @return CategoryInterface[]
     */
    public function getParentCategories(): array;

    /**
     * @param CategoryInterface $category
     * @return CategoryInterface
     * @throws ORMException
     */
    public function save(CategoryInterface $category): CategoryInterface;
}
