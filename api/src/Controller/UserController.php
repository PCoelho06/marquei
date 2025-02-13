<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Constraints\Json;

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
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'data' => $user->toArray(),
        ]);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function loginUser(#[CurrentUser] ?User $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'status' => 'error',
                'message' => 'missing credentials',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'token' => $JWTManager->create($user),
                'user' => $user->toArray(),
            ],
        ]);
    }
}
