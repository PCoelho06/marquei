<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class BaseFilterDTO
{
    #[Assert\Positive]
    public int $page = 1;

    #[Assert\Positive]
    public int $limit = 10;

    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_]+:(asc|desc)$/", message: "Sort format must be 'field:asc' or 'field:desc'")]
    public ?string $sort = null;

    public function __construct(array $queryParams)
    {
        $this->page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
        $this->limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : 10;
        $this->sort = $queryParams['sort'] ?? null;
    }
}
