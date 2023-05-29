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
     * @return UserInterface|null
     */
    public function getById(int $id): ?UserInterface;

    /**
     * @param string $email
     * @return UserInterface|null
     */
    public function getByEmail(string $email): ?UserInterface;

    /**
     * @param string $username
     * @return UserInterface|null
     */
    public function getByUsername(string $username): ?UserInterface;

    /**
     * @param array $credentials
     * @return UserInterface|null
     */
    public function getByCredentials(array $credentials): ?UserInterface;

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function save(UserInterface $user): UserInterface;

    /**
     * @param RegisterUserData $data
     * @return UserInterface
     */
    public function createUser(RegisterUserData $data): UserInterface;
}
