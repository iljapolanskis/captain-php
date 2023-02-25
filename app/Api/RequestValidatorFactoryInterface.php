<?php

namespace App\Api;

interface RequestValidatorFactoryInterface
{
    public function make(string $validatorClass): RequestValidatorInterface;
}
