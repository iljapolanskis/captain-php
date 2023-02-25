<?php

namespace App\Service;

use App\Api\SessionInterface;
use App\Exception\SessionException;

/**
 * Session PHP native implementation
 */
class SessionProviderService implements SessionInterface
{
    /**
     * @param array $config
     */
    public function __construct(private readonly array $config) {}

    /**
     * @return void
     */
    public function start(): void
    {
        if ($this->isActive()) {
            throw new SessionException('Session already started');
        }

        if (headers_sent($fileName, $lineNumber)) {
            throw new SessionException("Headers already sent in file '$fileName' on line $lineNumber");
        }

        session_set_cookie_params($this->config);

        session_start();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        session_write_close();
    }

    /**
     * @return bool
     */
    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    /**
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
