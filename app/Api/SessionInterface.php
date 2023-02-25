<?php

namespace App\Api;

/**
 * Agreement for session management
 */
interface SessionInterface
{
    /**
     * @return void
     */
    public function start(): void;

    /**
     * @return void
     */
    public function save(): void;

    /**
     * @return bool
     */
    public function regenerate(): bool;

    /**
     * @return void
     */
    public function destroy(): void;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function put(string $key, mixed $value): void;

    /**
     * @param string $key
     * @return void
     */
    public function forget(string $key): void;

    /**
     * @return bool
     */
    public function isActive(): bool;
}
