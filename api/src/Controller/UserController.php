<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\RefreshTokenService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api/user', name: 'user_')]
final class UserController extends AbstractController
{
    public function __construct(private UserService $userService, private RefreshTokenService $refreshTokenService) {}

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser(#[MapRequestPayload(validationGroups: ['registration'])] UserDTO $userDTO, JWTTokenManagerInterface $JWTManager, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $this->userService->registerUser($userDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => json_decode($e->getMessage()),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $refreshToken = $this->refreshTokenService->createRefreshToken($user);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'access_token' => $JWTManager->create($user),
                'refresh_token' => $refreshToken->getToken(),
                'user' => $user->toArray(),
            ],
        ]);
    }

    #[Route('/refresh-token', name: 'refresh_token', methods: ['POST'])]
    public function refreshToken(Request $request, JWTTokenManagerInterface $JWTManager, RefreshTokenService $refreshTokenService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['refresh_token'] ?? null;

        if (!$token) {
            return $this->json([
                'status' => 'error',
                'message' => 'Refresh token missing',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $refreshTokenService->verifyRefreshToken($token);

        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid or expired refresh token',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $refreshTokenService->revokeRefreshToken($token);

        $newAccessToken = $JWTManager->create($user);
        $refreshToken = $refreshTokenService->createRefreshToken($user);

        return new JsonResponse([
            'access_token' => $newAccessToken,
            'refresh_token' => $refreshToken->getToken(),
        ]);
    }

    #[Route('/salons', name: 'salons', methods: ['GET'])]
    public function getUserSalons(#[CurrentUser] User $user): JsonResponse
    {
        $salons = $user->getSalons();

        return $this->json([
            'status' => 'success',
            'data' => array_map(fn($salon) => $salon->toArray(), $salons->toArray()),
        ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getUserById(#[CurrentUser] ?User $user, int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUser($id);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'data' => $user->toArray(),
        ]);
    }
}
