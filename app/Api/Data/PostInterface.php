<?php

namespace App\Api\Data;

use DateTime;

/**
 * Contract for post data
 */
interface PostInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return PostInterface
     */
    public function setId(int $id): PostInterface;

    /**
     * @return string
     */
    public function getSlug(): string;

    /**
     * @param string $slug
     * @return PostInterface
     */
    public function setSlug(string $slug): PostInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return PostInterface
     */
    public function setTitle(string $title): PostInterface;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): PostInterface;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @param DateTime|null $createdAt
     * @return PostInterface
     */
    public function setCreatedAt(?DateTime $createdAt): PostInterface;

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime;

    /**
     * @param DateTime|null $updatedAt
     * @return PostInterface
     */
    public function setUpdatedAt(?DateTime $updatedAt): PostInterface;

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface;

    /**
     * @param UserInterface $user
     * @return PostInterface
     */
    public function setAuthor(UserInterface $user): PostInterface;

    /**
     * @return CategoryInterface[]
     */
    public function getCategories(): array;

    /**
     * @param CategoryInterface $category
     * @return PostInterface
     */
    public function addCategory(CategoryInterface $category): PostInterface;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return string
     */
    public function getHumanReadableDate(): string;
}
