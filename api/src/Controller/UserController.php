<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Service\UserService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/user', name: 'user_')]
final class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser(#[MapRequestPayload(validationGroups: ['registration'])] UserDTO $userDTO): JsonResponse
    {
        try {
            $user = $this->userService->registerUser($userDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => json_decode($e->getMessage()),
            ], 400);
        }

        return $this->json([
            'status' => 'success',
            'data' => $user->toArray(),
        ]);
    }
}
