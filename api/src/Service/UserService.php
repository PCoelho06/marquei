<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private EntityHydratorService $hydrator
    ) {}

    public function registerUser(UserDTO $userDTO): User
    {
        $userDTO->password = $this->passwordHasher->hashPassword(new User(), $userDTO->password);

        $user = $this->hydrator->hydrate(new User(), $userDTO);

        $user->setRoles([$userDTO->role]);

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

    public function getUser(int $id): User
    {
        $user = $this->userRepository->find($id);

        if ($user === null) {
            throw new \InvalidArgumentException('Usuário não encontrado');
        }

        return $user;
    }

    public function getUserByUsername(string $username): User
    {
        $user = $this->userRepository->findOneByEmail($username);

        if ($user === null) {
            throw new \InvalidArgumentException('Usuário não encontrado');
        }

        return $user;
    }
}
