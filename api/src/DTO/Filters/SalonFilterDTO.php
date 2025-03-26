<?php

namespace App\DTO\Filters;

use App\Utils\QueryStringParserTrait;
use Symfony\Component\Validator\Constraints as Assert;

class SalonFilterDTO extends BaseFilterDTO
{
    use QueryStringParserTrait;

    public string $name = '';

    public string $city = '';

    public string $country = '';

    public function __construct(array $queryParams)
    {
        parent::__construct($queryParams);
        $this->name = $queryParams['name'] ?? '';
        $this->city = $queryParams['city'] ?? '';
        $this->country = $queryParams['country'] ?? '';
    }
}
