<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\RefreshToken;
use App\Model\LoginModesEnum;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthenticationService
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private SalonService $salonService,
        private UserSalonService $userSalonService,
        private RefreshTokenRepository $refreshTokenRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityHydratorService $hydrator,
        private ValidatorInterface $validator
    ) {}

    public function generateJWT(User $user, ?int $salonId = null): string
    {
        $payload = [
            'id' => $user->getId(),
            'salons' => array_map(fn($userSalon) => $userSalon->getSalon()->getId(), $user->getSalons()->toArray()),
            'currentMode' => LoginModesEnum::MANAGEMENT_MODE,
        ];

        if ($salonId) {
            $salon = $this->salonService->getSalon($salonId);
            if ($salon) {
                $userSalon = $this->userSalonService->getUserSalon($user, $salon);
                if ($userSalon) {
                    $payload['currentSalon'] = $salonId;
                    $payload['currentMode'] = LoginModesEnum::STORE_MODE;
                    $payload['role'] = $userSalon->getRole()->value;
                    $payload['permissions'] = array_map(fn($perm) => $perm->getPermission()->getName(), $userSalon->getPermissions()->toArray());
                }
            }
        }

        return $this->jwtManager->createFromPayload($user, $payload);
    }

    public function generateRefreshToken(User $user, ?int $salonId, LoginModesEnum $mode): RefreshToken
    {
        $salon = $salonId ? $this->salonService->getSalon($salonId) : null;

        $token = bin2hex(random_bytes(64));
        $expiresAt = new \DateTimeImmutable('+7 days');

        $refreshToken = new RefreshToken($user, $token, $expiresAt, $salon, $mode);
        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return $refreshToken;
    }

    public function verifyRefreshToken(?string $token): ?RefreshToken
    {
        if (!$token) {
            throw new \InvalidArgumentException('Refresh token not provided');
        }

        $refreshToken = $this->refreshTokenRepository->findOneBy(['token' => $token]);

        if (!$refreshToken) {
            throw new \InvalidArgumentException('Invalid refresh token');
        }

        if ($refreshToken->isExpired()) {
            throw new \InvalidArgumentException('Refresh token expired');
        }

        return $refreshToken;
    }

    public function revokeRefreshToken(RefreshToken $refreshToken): void
    {
        $this->entityManager->remove($refreshToken);
        $this->entityManager->flush();
    }

    public function refreshTokens(?string $token): array
    {
        $refreshToken = $this->verifyRefreshToken($token);

        return [
            'access_token' => $this->refreshAccessToken($refreshToken),
            'refresh_token' => $this->refreshRefreshToken($refreshToken)->getToken(),
        ];
    }

    public function refreshAccessToken(RefreshToken $refreshToken): string
    {
        $user = $refreshToken->getUser();
        $salon = $refreshToken->getSalon();

        return $this->generateJWT($user, $salon ? $salon->getId() : null);
    }

    public function refreshRefreshToken(RefreshToken $refreshToken): RefreshToken
    {
        $this->revokeRefreshToken($refreshToken);

        return $this->generateRefreshToken($refreshToken->getUser(), $refreshToken->getSalon() ? $refreshToken->getSalon()->getId() : null, $refreshToken->getMode());
    }

    public function registerUser(UserDTO $userDTO): User
    {
        $userDTO->password = $this->passwordHasher->hashPassword(new User(), $userDTO->password);

        $user = $this->hydrator->hydrate(new User(), $userDTO);

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

    public function logout(User $user): void
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['user' => $user]);
        if ($refreshToken) $this->revokeRefreshToken($refreshToken);
    }
}
