<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ValidatorInterface $validator, private readonly UserPasswordHasherInterface $passwordHasher) {}

    private function DTOToUser(UserDTO $userDTO): User
    {
        $user = new User();

        $user->setEmail($userDTO->email)
            ->setPassword($this->passwordHasher->hashPassword($user, $userDTO->password))
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());


        return $user;
    }

    public function registerUser(UserDTO $userDTO): User
    {
        $user = $this->DTOToUser($userDTO);

        $errors = $this->validator->validate($user, null, ['registration']);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }

            throw new \InvalidArgumentException(json_encode($errorMessages));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
