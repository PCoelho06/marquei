<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Model\LoginModesEnum;
use App\Service\UserSalonService;
use App\Service\AuthenticationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;

#[Route('/api/auth', name: 'auth_')]
final class AuthenticationController extends AbstractController
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private UserSalonService $userSalonService,
        private JWTTokenManagerInterface $JWTManager,
        private LoggerInterface $logger,
    ) {}

    #[Route('/', name: 'fetch_user', methods: ['GET'])]
    public function fetchUser(#[CurrentUser()] User $user): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'data' => [
                'user' => $user->toArray(),
                'hasSalons' => $this->userSalonService->hasSalons($user),
            ],
        ]);
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser(#[MapRequestPayload(validationGroups: ['registration'])] UserDTO $userDTO): JsonResponse
    {
        try {
            $user = $this->authenticationService->registerUser($userDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => json_decode($e->getMessage()),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $refreshToken = $this->authenticationService->generateRefreshToken($user, null, LoginModesEnum::MANAGEMENT_MODE);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'user' => $user->toArray(),
                'access_token' => $this->authenticationService->generateJWT($user),
                'refresh_token' => $refreshToken->getToken(),
            ],
        ]);
    }

    #[Route('/refresh-token', name: 'refresh_token', methods: ['POST'])]
    public function refreshToken(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['refresh_token'] ?? null;

        try {
            [
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
            ] = $this->authenticationService->refreshTokens($token);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
            ],
        ]);
    }

    #[Route('/confirm-password', name: 'confirm_password', methods: ['POST'])]
    public function confirmPassword(#[MapRequestPayload(validationGroups: ['confirmation'])] UserDTO $userDto, #[CurrentUser()] User $user): JsonResponse
    {
        $this->logger->info('Confirming password for user: ' . $userDto->password);
        try {
            $this->authenticationService->confirmPassword($user, $userDto->password);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'status' => 'success',
            'message' => 'Password confirmed successfully.',
        ]);
    }

    #[Route('/{mode}', name: 'select_mode', methods: ['GET'])]
    public function getModeToken(string $mode, Request $request): JsonResponse
    {
        $mode = $mode === 'store' ? LoginModesEnum::STORE_MODE : LoginModesEnum::MANAGEMENT_MODE;
        $selectedSalon = $request->query->get('salonId') ?: null;

        if ($selectedSalon && $mode === LoginModesEnum::STORE_MODE) {
            $accessToken = $this->authenticationService->generateJWT($this->getUser(), $selectedSalon);
        } else {
            $accessToken = $this->authenticationService->generateJWT($this->getUser());
        }

        $refreshToken = $this->authenticationService->generateRefreshToken($this->getUser(), $selectedSalon, $mode);

        return $this->json([
            'status' => 'success',
            'data' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken->getToken(),
            ],
        ]);
    }
}
