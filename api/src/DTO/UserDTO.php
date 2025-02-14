<?php

namespace App\DTO;

use App\Model\RolesEnum;
use Symfony\Component\Validator\Constraints as Assert;

final class UserDTO
{
    #[Assert\NotBlank(groups: ['registration'])]
    #[Assert\Email(groups: ['registration'])]
    public string $email;

    #[Assert\NotBlank(groups: ['registration'])]
    public string $password;

    #[Assert\Choice(callback: [RolesEnum::class, 'values'], message: 'Bad choice value', groups: ['registration'])]
    public ?string $role;

    public function __construct(string $email, string $password, ?string $role = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role ?? RolesEnum::ROLE_USER;
    }
}
