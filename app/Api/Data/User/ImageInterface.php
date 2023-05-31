<?php

namespace App\Api\Data\User;

use App\Api\Data\UserInterface;

interface ImageInterface
{
    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): ImageInterface;

    public function getUrl(): string;

    public function setUrl(string $url): ImageInterface;

    public function getWidth(): int;

    public function getHeight(): int;

    public function getSizeInKb(): int;
}