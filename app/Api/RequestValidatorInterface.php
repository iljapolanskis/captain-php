<?php

namespace App\Api;

/**
 *
 */
interface RequestValidatorInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function validate(array $data): array;
}
