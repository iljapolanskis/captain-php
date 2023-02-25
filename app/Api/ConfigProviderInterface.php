<?php

namespace App\Api;

use Doctrine\ORM\Configuration;
use PubNub\PNConfiguration;

/**
 * Agreement for sharing configuration data across the application
 */
interface ConfigProviderInterface
{
    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed;

    /**
     * @return bool
     */
    public function isLocal(): bool;

    /**
     * @return bool
     */
    public function isLive(): bool;

    /**
     * @return array
     */
    public function getSessionConfiguration(): array;

    /**
     * @return \Doctrine\ORM\Configuration
     */
    public function getOrmConfiguration(): Configuration;

    /*
     * @return array
     */
    public function getRedisConfiguration(): array;

    /**
     * @return \PubNub\PNConfiguration
     */
    public function getPubNubConfiguration(): PNConfiguration;
}
