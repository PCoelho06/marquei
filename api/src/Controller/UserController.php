<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;

#[Route('/api/user', name: 'user_')]
final class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser(#[MapRequestPayload(validationGroups: ['registration'])] UserDTO $userDTO, JWTTokenManagerInterface $JWTManager, RefreshTokenGeneratorInterface $refreshTokenGenerator): JsonResponse
    {
        try {
            $user = $this->userService->registerUser($userDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => json_decode($e->getMessage()),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $refreshToken = $refreshTokenGenerator->createForUserWithTtl($user, $this->getParameter('gesdinet_jwt_refresh_token.ttl'));

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'access_token' => $JWTManager->create($user),
                'refresh_token' => $refreshToken->getRefreshToken(),
                'user' => $user->toArray(),
            ],
        ]);

        // return $this->json([
        //     'status' => 'success',
        //     'data' => $user->toArray(),
        // ]);
    }

    #[Route('/refresh-token', methods: ['POST'])]
    public function refreshToken(Request $request, RefreshTokenManagerInterface $refreshTokenManager, RefreshTokenGeneratorInterface $refreshTokenGenerator, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $refreshTokenString = $data['refresh_token'] ?? '';

        $refreshToken = $refreshTokenManager->get($refreshTokenString);

        if (!$refreshToken || !$refreshToken->isValid()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid refresh token',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $username = $refreshToken->getUsername();
        try {
            $user = $this->userService->getUserByUsername($username);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $newAccessToken = $JWTManager->create($user);
        $refreshTokenManager->delete($refreshToken);

        return new JsonResponse([
            'access_token' => $newAccessToken,
            'refresh_token' => $refreshTokenGenerator->createForUserWithTtl($user, $this->getParameter('gesdinet_jwt_refresh_token.ttl'))->getRefreshToken(),
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
