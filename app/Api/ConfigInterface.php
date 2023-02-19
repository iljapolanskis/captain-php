<?php

namespace App\Api;

use Doctrine\ORM\Configuration;
use PubNub\PNConfiguration;

/**
 * Agreement for sharing configuration data across the application
 */
interface ConfigInterface
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
     * @return \Doctrine\ORM\Configuration
     */
    public function getOrmConfiguration(): Configuration;

    /**
     * @return \PubNub\PNConfiguration
     */
    public function getPubNubConfiguration(): PNConfiguration;
}
