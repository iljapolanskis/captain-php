<?php

namespace App\DTO\Validator;

use App\Api\RequestValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class LoginUserDataValidator implements RequestValidatorInterface
{

    public function validate(array $data): array
    {
        $validator = new Validator($data);
        $validator->rule('required', ['name', 'password']);

        if (! $validator->validate()) {
            throw new ValidationException($validator->errors());
        }

        return $data;
    }
}
