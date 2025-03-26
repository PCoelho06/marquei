<?php

namespace App\DTO\Filters;

use App\Utils\QueryStringParserTrait;
use Symfony\Component\Validator\Constraints as Assert;

class ResourceFilterDTO extends BaseFilterDTO
{
    use QueryStringParserTrait;

    #[Assert\Choice(choices: ['employee', 'machine'], multiple: true)]
    public array $types = [];

    public array $salons = [];

    public string $name = '';

    public function __construct(array $queryParams)
    {
        parent::__construct($queryParams);
        $this->types = isset($queryParams['type']) ? $this->parseArray($queryParams['type']) : [];
        $this->salons = isset($queryParams['salon']) ? $this->parseArray($queryParams['salon']) : [];
        $this->name = $queryParams['name'] ?? '';
    }
}
