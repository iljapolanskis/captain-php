<?php

namespace App\Api\Manager;

use App\Api\Data\UserInterface;
use App\DTO\RegisterUserData;

/**
 * Contract for user provider service
 */
interface UserProviderInterface
{
    /**
     * @param int $id
     * @return \App\Api\Data\UserInterface|null
     */
    public function getById(int $id): ?UserInterface;

    /**
     * @param string $email
     * @return \App\Api\Data\UserInterface|null
     */
    public function getByEmail(string $email): ?UserInterface;

    /**
     * @param string $username
     * @return \App\Api\Data\UserInterface|null
     */
    public function getByUsername(string $username): ?UserInterface;

    /**
     * @param array $credentials
     * @return \App\Api\Data\UserInterface|null
     */
    public function getByCredentials(array $credentials): ?UserInterface;

    /**
     * @param \App\Model\Data\User $user
     * @return \App\Api\Data\UserInterface
     */
    public function save(UserInterface $user): UserInterface;

    /**
     * @param \App\DTO\RegisterUserData $data
     * @return \App\Api\Data\UserInterface
     */
    public function createUser(RegisterUserData $data): UserInterface;
}
