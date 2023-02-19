<?php

namespace App;

use App\Api\ConfigInterface;
use App\Enum\AppEnvironment;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use PubNub\PNConfiguration;

class Config implements ConfigInterface
{
    /** @var \Doctrine\ORM\Configuration|null */
    private ?Configuration $ormConfiguration = null;

    /** @var \PubNub\PNConfiguration|null */
    private ?PNConfiguration $pubNubConfig = null;

    public function __construct(private readonly array $config) {}

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
     * @return mixed
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

    public function isLocal(): bool
    {
        return AppEnvironment::isLocal($this->get('app_environment'));
    }

    public function isLive(): bool
    {
        return AppEnvironment::isLive($this->get('app_environment'));
    }
}
