<?php

namespace App\Service;

use App\Entity\User;
use App\DTO\SalonDTO;
use App\Entity\Salon;
use App\Entity\Service;
use App\Entity\Resource;
use App\Model\RolesEnum;
use App\Entity\UserSalon;
use App\Repository\UserRepository;
use App\Entity\BusinessHoursRanges;
use App\Entity\Subscription;
use App\Repository\SalonRepository;
use App\Repository\ServiceRepository;
use App\Repository\ResourceRepository;
use App\Repository\UserSalonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SalonService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SalonRepository $salonRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly ResourceRepository $resourceRepository,
        private UserRepository $userRepository,
        private UserSalonRepository $userSalonRepository,
        private EntityHydratorService $hydrator,
        private Security $security,
    ) {}

    public function checkUserIsSalonOwner(Salon $salon): void
    {
        $user = $this->security->getUser();

        if ($user === null) {
            throw new AccessDeniedException('Usuário não autenticado');
        }

        $userSalon = $this->userSalonRepository->findOneByUserAndSalon($user, $salon);

        if ($userSalon === null || $userSalon->getRole() !== RolesEnum::ROLE_OWNER) {
            throw new AccessDeniedException('Usuário não é o proprietário do salão');
        }
    }

    public function createSalon(SalonDTO $salonDTO): Salon
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Usuário não autenticado');
        }

        $salonExists = $this->salonRepository->findOneBy(['name' => $salonDTO->name]) !== null;

        if ($salonExists) {
            throw new \Exception('Um salão com este nome já existe');
        }

        $salon = $this->hydrator->hydrate(new Salon(), $salonDTO);
        $this->entityManager->persist($salon);

        $userSalon = new UserSalon();
        $userSalon->setUser($this->security->getUser());
        $userSalon->setSalon($salon);
        $userSalon->setRole(RolesEnum::ROLE_OWNER);
        $this->entityManager->persist($userSalon);

        $salon->addUser($userSalon);

        $this->entityManager->flush();

        return $salon;
    }

    public function listSalons(int $page = 1, int $limit = 10): array
    {
        $salons = $this->salonRepository->findAllPaginated($page, $limit);

        return array_map(fn(Salon $salon) => $salon->toArray(), $salons);
    }

    public function listUserSalons(User $user): array
    {
        $userSalons = $user->getSalons();

        $salons = array_map(fn(UserSalon $userSalon) => $userSalon->getSalon()->toArray(), $userSalons->toArray());

        return $salons;
    }

    public function getSalon(int $id): Salon
    {
        $salon = $this->salonRepository->find($id);

        if ($salon === null) {
            throw new \InvalidArgumentException('Salão não encontrado');
        }

        $this->checkUserIsSalonOwner($salon);

        return $salon;
    }

    public function getSalonBusinessHours(int $id): array
    {
        $salon = $this->getSalon($id);

        $businessHours = $salon->getBusinessHoursRanges()->toArray();

        return array_map(fn(BusinessHoursRanges $businessHoursRanges) => $businessHoursRanges->toArray(), $businessHours);
    }

    public function getSalonServices(int $id, int $page, int $limit): array
    {
        $salon = $this->getSalon($id);

        $services = $this->serviceRepository->findByPaginated($salon, $page, $limit);

        return array_map(fn(Service $service) => $service->toArray(), $services);
    }

    public function getSalonEmployees(int $id, int $page, int $limit): array
    {
        $salon = $this->getSalon($id);

        $employees = $this->resourceRepository->findEmployeeBySalonPaginated($salon, $page, $limit);

        return array_map(fn(Resource $employee) => $employee->toArray(), $employees);
    }

    public function getSalonMachines(int $id, int $page, int $limit): array
    {
        $salon = $this->getSalon($id);

        $machines = $this->resourceRepository->findMachineBySalonPaginated($salon, $page, $limit);

        return array_map(fn(Resource $machine) => $machine->toArray(), $machines);
    }

    public function getSalonCurrentSubscription(int $id): Subscription
    {
        $salon = $this->getSalon($id);

        $subscription = $salon->getSubscriptions()->filter(fn($subscription) => $subscription->getStatus() == 'active')->first();

        if ($subscription === null || !$subscription) {
            throw new \InvalidArgumentException('Salão não possui assinatura ativa');
        }

        return $subscription;
    }

    public function updateSalon(int $id, SalonDTO $salonDTO): Salon
    {
        $salon = $this->getSalon($id);

        $this->checkUserIsSalonOwner($salon);

        $salon = $this->hydrator->hydrate($salon, $salonDTO);

        $this->entityManager->flush();

        return $salon;
    }

    public function deleteSalon(int $id): void
    {
        $salon = $this->getSalon($id);

        $this->checkUserIsSalonOwner($salon);

        $this->entityManager->remove($salon);
        $this->entityManager->flush();
    }
}
