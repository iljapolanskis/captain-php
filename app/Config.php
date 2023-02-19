<?php

namespace App;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use PubNub\PNConfiguration;

/**
 * Shares configuration data across the application
 */
class Config
{
    /** @var array|array[] */
    protected array $config = [];

    /** @var \Doctrine\ORM\Configuration|null */
    private ?Configuration $ormConfiguration = null;

    /** @var \PubNub\PNConfiguration|null */
    private ?PNConfiguration $pubNubConfig = null;

    /**
     * @param array $env
     */
    public function __construct(array $env)
    {
        $this->config = [
            'isLive' => $env['APP_ENV'] === 'live',
            'isLocal' => $env['APP_ENV'] === 'local',
            'db' => [
                'host' => $env['DB_HOST'],
                'user' => $env['DB_USER'],
                'password' => $env['DB_PASS'],
                'dbname' => $env['DB_DATABASE'],
                'driver' => $env['DB_DRIVER'] ?? 'pdo_mysql',
            ],
            'pubnub' => [
                'publishKey' => $env['PUBNUB_PUBLISH_KEY'],
                'subscribeKey' => $env['PUBNUB_SUBSCRIBE_KEY'],
                'secretKey' => $env['PUBNUB_SECRET_KEY'],
                'uuid' => $env['PUBNUB_UUID'],
            ],
            'abstract' => [
                'apiKey' => $env['ABSTRACT_EMAIL_API_KEY'],
            ]
        ];
    }

    /**
     * @param string $name
     * @return array|mixed|null
     */
    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }

    /**
     * @return mixed
     */
    public function getOrmConfiguration(): Configuration
    {
        if ($this->ormConfiguration === null) {
            $this->ormConfiguration = ORMSetup::createAttributeMetadataConfiguration(
                [ENTITY_PATH],
                $this->isLocal
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
            $pnConfig->setPublishKey($this->pubnub['publishKey']);
            $pnConfig->setSubscribeKey($this->pubnub['subscribeKey']);
            $pnConfig->setUuid($this->pubnub['uuid']);
            $this->pubNubConfig = $pnConfig;
        }
        return $this->pubNubConfig;
    }
}
