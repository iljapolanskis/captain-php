<?php

namespace App\Enum;

enum SessionConstants: string
{
    case User = 'user';
    // Errors & Old are used by the TwigValidationMiddleware, to show errors and old input values
    case Errors = 'errors';
    case Old = 'old';
}
