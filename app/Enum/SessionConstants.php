<?php

namespace App\Enum;

enum SessionConstants: string
{
    case User = 'user';

    case FormInput = 'form_input';
    case FormErrors = 'form_errors';
}
