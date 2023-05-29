<?php

namespace App\Service\Manager;

use App\Api\Data\CategoryInterface;
use App\Api\Manager\CategoryProviderInterface;
use App\Model\Data\Category;
use Doctrine\ORM\EntityManager;

class CategoryProviderService implements CategoryProviderInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function getById(int $id): ?CategoryInterface
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $id]);
    }

    public function getByName(string $name): ?CategoryInterface
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $name]);
    }

    public function getAll(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    public function getParentCategories(): array
    {
        // FIXME - Add parent category logic, for now return all categories
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    public function save(CategoryInterface $category): CategoryInterface
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return $category;
    }
}
