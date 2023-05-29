<?php

namespace App\Api\Data;

interface UserInterface
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
     * @return string
     */
    public function getPassword(): string;

    /**
     * @return PostInterface[]
     */
    public function getPosts(): array;
}
