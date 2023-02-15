<?php

namespace App;

/**
 * Shares configuration data across the application
 */
class Config
{
    /** @var array|array[] */
    protected array $config = [];

    /**
     * @param array $env
     */
    public function __construct(array $env)
    {
        $this->config = [
            'db' => [
                'host' => $env['DB_HOST'],
                'user' => $env['DB_USER'],
                'password' => $env['DB_PASS'],
                'dbname' => $env['DB_DATABASE'],
                'driver' => $env['DB_DRIVER'] ?? 'pdo_mysql',
            ],
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
}
