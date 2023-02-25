<?php

namespace App\Model\Factory;

use App\Api\RequestValidatorFactoryInterface;
use App\Api\RequestValidatorInterface;
use App\Exception\FactoryException;
use Psr\Container\ContainerInterface;

class RequestValidatorFactory implements RequestValidatorFactoryInterface
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function make(string $validatorClass): RequestValidatorInterface
    {
        $validator = $this->container->get($validatorClass);

        if ($validator instanceof RequestValidatorInterface) {
            return $validator;
        }

        throw new FactoryException(sprintf('Validator %s must implement RequestValidatorInterface', $validatorClass));
    }
}
