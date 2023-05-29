<?php

namespace App\Api\Data;

use Doctrine\Common\Collections\Collection;

/**
 *
 */
interface CategoryInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return CategoryInterface
     */
    public function setName(string $name): CategoryInterface;

    /**
     * @return Collection
     */
    public function getPosts(): Collection;

    /**
     * @param PostInterface $post
     * @return CategoryInterface
     */
    public function addPost(PostInterface $post): CategoryInterface;

    /**
     * @return string
     */
    public function getUrl(): string;
}
