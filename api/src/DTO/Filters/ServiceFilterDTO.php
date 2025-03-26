<?php

namespace App\DTO\Filters;

use App\Utils\QueryStringParserTrait;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceFilterDTO extends BaseFilterDTO
{
    use QueryStringParserTrait;

    public int $salonId = 0;

    public string $name = '';

    public int $minPrice = 0;

    public int $maxPrice;

    public int $minDuration = 0;

    public int $maxDuration;

    public function __construct(array $queryParams)
    {
        parent::__construct($queryParams);
        $this->salonId = $this->getInt('salonId', $queryParams);
        $this->name = $queryParams['name'] ?? '';
        $this->minPrice = $this->getInt('minPrice', $queryParams) * 100;
        $this->maxPrice = $this->getInt('maxPrice', $queryParams) * 100;
        $this->minDuration = $this->getInt('minDuration', $queryParams);
        $this->maxDuration = $this->getInt('maxDuration', $queryParams);
    }
}
