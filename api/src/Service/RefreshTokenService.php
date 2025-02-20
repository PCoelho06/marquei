<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class RefreshTokenService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $JWTManager,
        private RefreshTokenRepository $refreshTokenRepository,
    ) {}

    public function createRefreshToken(UserInterface $user): RefreshToken
    {
        $token = bin2hex(random_bytes(40));
        $expiresAt = (new \DateTimeImmutable())->modify('+7 days');

        $refreshToken = new RefreshToken();
        $refreshToken->setToken($token)
            ->setExpiresAt($expiresAt)
            ->setUser($user);

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return $refreshToken;
    }

    public function verifyRefreshToken(string $token): ?User
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['token' => $token]);

        if (!$refreshToken || $refreshToken->isExpired()) {
            return null;
        }

        return $refreshToken->getUser();
    }

    public function revokeRefreshToken(string $token): void
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['token' => $token]);

        $this->entityManager->remove($refreshToken);
        $this->entityManager->flush();
    }
}
