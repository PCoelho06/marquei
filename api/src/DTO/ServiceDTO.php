<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ServiceDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    public string $description;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $duration;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $price;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $salonId;

    public function __construct(string $name, string $description, int $duration, int $price, int $salonId)
    {
        $this->name = $name;
        $this->description = $description;
        $this->duration = $duration;
        $this->price = $price;
        $this->salonId = $salonId;
    }
}
