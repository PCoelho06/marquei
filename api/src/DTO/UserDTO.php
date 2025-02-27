<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class UserDTO
{
    #[Assert\NotBlank(groups: ['registration'])]
    #[Assert\Email(groups: ['registration'])]
    public string $email;

    #[Assert\NotBlank(groups: ['registration'])]
    #[Assert\Length(min: 6, max: 20, groups: ['registration'], minMessage: 'A senha deve ter no mínimo 6 caracteres', maxMessage: 'A senha deve ter no máximo 20 caracteres')]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-_])[A-Za-z\d@$!%*?&]/',
        message: 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial',
        groups: ['registration']
    )]
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
