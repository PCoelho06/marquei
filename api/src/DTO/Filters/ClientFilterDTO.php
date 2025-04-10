<?php

namespace App\DTO\Filters;

use App\Utils\QueryStringParserTrait;
use Symfony\Component\Validator\Constraints as Assert;

class ClientFilterDTO extends BaseFilterDTO
{
    use QueryStringParserTrait;

    #[Assert\Length(max: 255)]
    public ?string $name = null;
    #[Assert\Length(max: 255)]
    public ?string $email = null;
    #[Assert\Length(max: 255)]
    public ?string $phone = null;
    #[Assert\Positive]
    public ?int $salonId = null;

    public function __construct(array $queryParams)
    {
        parent::__construct($queryParams);
        $this->name = $queryParams['name'] ?? null;
        $this->email = $queryParams['email'] ?? null;
        $this->phone = $queryParams['phone'] ?? null;
    }
}
