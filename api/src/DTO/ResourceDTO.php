<?php

namespace App\DTO;

use App\Model\ResourceTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

final class ResourceDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [ResourceTypeEnum::class, 'values'], message: 'Invalid resource type')]
    public string $type;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $salonId;

    public function __construct(string $name, string $type, int $salon)
    {
        $this->name = $name;
        $this->type = $type;
        $this->salonId = $salon;
    }
}
