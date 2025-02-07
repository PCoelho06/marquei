<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class UserDTO
{
    #[Assert\NotBlank(groups: ['registration'])]
    #[Assert\Email(groups: ['registration'])]
    public string $email;

    #[Assert\NotBlank(groups: ['registration'])]
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
