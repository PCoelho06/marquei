<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class SalonDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(max: 255, groups: ['create', 'update'])]
    public ?string $name = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $street = null;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Regex('/^\\d{4}-\\d{3}$/', message: 'O còdigo postal deve ter o formato 0000-000', groups: ['create', 'update'])]
    public ?string $postalCode = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $city = null;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Regex('/^(\+351)?[29]\d{8}$/', message: 'O número de telefone não é válido', groups: ['create', 'update'])]
    public ?string $phone = null;

    public function __construct(string $name, string $street, string $postalCode, string $city, string $phone, int $owner)
    {
        $this->name = $name;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->phone = $phone;
    }
}
