<?php

namespace App\Api;

use App\Api\Data\UserInterface;
use App\DTO\RegisterUserData;

/**
 *
 */
interface AuthInterface
{
    public function user(): ?UserInterface;

    public function attemptLogin(array $credentials): bool;

    public function logIn(UserInterface $user): void;

    public function verifyCredentials(UserInterface $user, array $credentials): bool;

    public function logOut(): void;

    public function register(RegisterUserData $data): UserInterface;
}
