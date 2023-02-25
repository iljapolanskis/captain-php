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
    /** @var \App\Api\Data\UserInterface|null */
    private ?UserInterface $user = null;

    /**
     * @param \App\Api\Manager\UserProviderInterface $userProvider
     */
    public function __construct(
        private readonly SessionInterface $session,
        private readonly UserProviderInterface $userProvider
    ) {}

    /**
     * @return \App\Api\Data\UserInterface|null
     */
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

        if (! $user) {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function attemptLogin(array $credentials): bool
    {
        $user = $this->userProvider->getByUsername($credentials['name']);

        if (! $user) {
            return false;
        }

        if (! $this->verifyCredentials($user, $credentials)) {
            return false;
        }

        $this->logIn($user);

        return true;
    }

    /**
     * @param \App\Api\Data\UserInterface $user
     * @param array $credentials
     * @return bool
     */
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

    /**
     * @return void
     */
    public function logOut(): void
    {
        $this->session->forget(SessionConstants::User->value);
        $this->user = null;
    }

    /**
     * @inheritDoc
     */
    public function register(RegisterUserData $data): UserInterface
    {
        $user = $this->userProvider->createUser($data);

        $this->logIn($user);

        return $user;
    }
}
