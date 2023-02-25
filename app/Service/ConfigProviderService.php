<?php

namespace App\Service;

use App\Api\ConfigProviderInterface;
use App\Enum\AppEnvironment;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use PubNub\PNConfiguration;

/**
 *
 */
class ConfigProviderService implements ConfigProviderInterface
{
    /** @var \Doctrine\ORM\Configuration|null */
    private ?Configuration $ormConfiguration = null;

    /** @var \PubNub\PNConfiguration|null */
    private ?PNConfiguration $pubNubConfig = null;

    /**
     * @param array $config
     */
    public function __construct(private readonly array $config) {}

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        $path = explode('.', $name);
        $value = $this->config[array_shift($path)] ?? null;

        if ($value === null) {
            return $default;
        }

        foreach ($path as $key) {
            if (! isset($value[$key])) {
                return $default;
            }

            $value = $value[$key];
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return AppEnvironment::isLocal($this->get('app_environment'));
    }

    /**
     * @return bool
     */
    public function isLive(): bool
    {
        return AppEnvironment::isLive($this->get('app_environment'));
    }

    /**
     * @return array
     */
    public function getSessionConfiguration(): array
    {
        return [
            'lifetime' => $this->get('session.lifetime'),
            'secure' => $this->get('session.secure'),
            'httponly' => $this->get('session.httponly'),
            'samesite' => $this->get('session.samesite'),
        ];
    }

    /**
     * @return \Doctrine\ORM\Configuration
     */
    public function getOrmConfiguration(): Configuration
    {
        if ($this->ormConfiguration === null) {
            $this->ormConfiguration = ORMSetup::createAttributeMetadataConfiguration(
                [ENTITY_PATH],
                $this->isLocal(),
            );
        }

        return $this->ormConfiguration;
    }

    /**
     * @return array
     */
    public function getRedisConfiguration(): array
    {
        return [
            'scheme' => $this->get('redis.tcp'),
            'host' => $this->get('redis.host'),
            'port' => $this->get('redis.port'),
        ];
    }

    /**
     * @return \PubNub\PNConfiguration
     * @throws \PubNub\Exceptions\PubNubConfigurationException
     */
    public function getPubNubConfiguration(): PNConfiguration
    {
        if ($this->pubNubConfig === null) {
            $pnConfig = new PNConfiguration();
            $pnConfig->setPublishKey($this->get('pubnub.publishKey'));
            $pnConfig->setSubscribeKey($this->get('pubnub.subscribeKey'));
            $pnConfig->setUuid($this->get('pubnub.uuid'));
            $this->pubNubConfig = $pnConfig;
        }
        return $this->pubNubConfig;
    }
}
