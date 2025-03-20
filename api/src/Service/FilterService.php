<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilterService
{
    public function __construct(private ValidatorInterface $validator) {}

    public function createDtoFromRequest(string $dtoClass, array $queryParams)
    {
        $dto = new $dtoClass($queryParams);
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        return $dto;
    }
}
