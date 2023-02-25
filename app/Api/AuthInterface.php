<?php

namespace App\Api;

use App\Api\Data\UserInterface;
use App\DTO\RegisterUserData;

/**
 *
 */
interface AuthInterface
{
    /**
     * @return \App\Api\Data\UserInterface|null
     */
    public function user(): ?UserInterface;

    /**
     * @param array $credentials
     * @return mixed
     */
    public function attemptLogin(array $credentials): bool;

    /**
     * @param \App\Api\Data\UserInterface $user
     * @return void
     */
    public function logIn(UserInterface $user): void;

    /**
     * @param \App\Api\Data\UserInterface $user
     * @param array $credentials
     * @return bool
     */
    public function verifyCredentials(UserInterface $user, array $credentials): bool;

    /**
     * @return void
     */
    public function logOut(): void;

    /**
     * @param \App\DTO\RegisterUserData $data
     * @return UserInterface
     */
    public function register(RegisterUserData $data): UserInterface;
}
