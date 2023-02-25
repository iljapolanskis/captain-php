<?php

namespace App\Service\Manager;

use App\Api\Data\UserInterface;
use App\Api\Manager\UserProviderInterface;
use App\DTO\RegisterUserData;
use App\Model\Data\User;
use Doctrine\ORM\EntityManager;

class UserProviderService implements UserProviderInterface
{
    public function __construct(private readonly EntityManager $entityManager) {}

    public function getById(int $id): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function getByEmail(string $email): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function getByUsername(string $username): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['name' => $username]);
    }

    public function getByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['name' => $credentials['name']]);
    }

    /**
     * @inheritDoc
     */
    public function createUser(RegisterUserData $data): UserInterface
    {
        $user = new User();
        $user->setName($data->name);
        $user->setEmail($data->email);
        $user->setPassword(password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 12]));
        return $this->save($user);
    }

    /**
     * @inheritDoc
     */
    public function save(UserInterface $user): UserInterface
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
