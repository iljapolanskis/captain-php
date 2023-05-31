<?php

namespace App\Service;

use App\Api\AuthInterface;
use App\Api\Data\UserInterface;
use App\Api\Manager\UserProviderInterface;
use App\Api\SessionInterface;
use App\DTO\RegisterUserData;
use App\Enum\SessionConstants;

/**
 *
 */
class AuthProviderService implements AuthInterface
{
    private ?UserInterface $user = null;

    public function __construct(
        private readonly SessionInterface $session,
        private readonly UserProviderInterface $userProvider
    ) {}

    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get(SessionConstants::User->value);

        if ($userId === null) {
            return null;
        }

        $user = $this->userProvider->getById($userId);

        if (!$user) {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    public function attemptLogin(array $credentials): bool
    {
        $user = $this->userProvider->getByUsername($credentials['name']);

        if (!$user) {
            return false;
        }

        if (!$this->verifyCredentials($user, $credentials)) {
            return false;
        }

        $this->logIn($user);

        return true;
    }

    public function verifyCredentials(UserInterface $user, array $credentials): bool
    {
        return password_verify($credentials['password'], $user->getPassword());
    }

    public function logIn(UserInterface $user): void
    {
        $this->session->put(SessionConstants::User->value, $user->getId());
        $this->user = $user;
        $this->session->regenerate();
    }

    public function logOut(): void
    {
        $this->session->forget(SessionConstants::User->value);
        $this->user = null;
    }

    public function register(RegisterUserData $data): UserInterface
    {
        $user = $this->userProvider->createUser($data);

        $this->logIn($user);

        return $user;
    }
}
