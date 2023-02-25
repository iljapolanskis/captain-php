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
    public function getPassword(): string;
}
