<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ClientDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'The first name should only contain letters.'
    )]
    public ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'The last name should only contain letters.'
    )]
    public ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    public ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]{7,15}$/',
        message: 'The phone number should be a valid format.'
    )]
    public ?string $phone = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public ?int $salonId = null;

    public function __construct(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
        ?string $phone = null,
        ?int $salonId = null
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->salonId = $salonId;
    }
}
