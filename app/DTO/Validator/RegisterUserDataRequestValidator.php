<?php

namespace App\DTO\Validator;

use App\Api\Manager\UserProviderInterface;
use App\Api\RequestValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class RegisterUserDataRequestValidator implements RequestValidatorInterface
{
    public function __construct(
        private readonly UserProviderInterface $userProvider,
    ) {}

    public function validate(array $data): array
    {
        $validator = new Validator($data);

        $validator->rule('required', ['name', 'email', 'password', 'passwordConfirm']);
        $validator->rule('email', 'email');
        $validator->rule('equals', 'password', 'passwordConfirm')->message('Passwords do not match.');

        $validator->rule(
            fn($field, $value, $params, $fields) => $this->userProvider->getByEmail($value) === null,
            'email'
        )->message('Email is already taken.');

        $validator->rule(
            fn($field, $value, $params, $fields) => $this->userProvider->getByUsername($value) === null,
            'name'
        )->message('Username is already taken.');

        if (! $validator->validate()) {
            throw new ValidationException($validator->errors());
        }

        return $data;
    }
}
