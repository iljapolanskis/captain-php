<?php

declare(strict_types=1);

namespace App\Enum;

enum AppEnvironment: string
{
    case Local = 'local';
    case Live = 'live';

    public static function isLive(string $appEnvironment): bool
    {
        return self::tryFrom($appEnvironment) === self::Live;
    }

    public static function isLocal(string $appEnvironment): bool
    {
        return self::tryFrom($appEnvironment) === self::Local;
    }
}
