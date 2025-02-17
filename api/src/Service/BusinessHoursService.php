<?php

namespace App\Service;

use App\Entity\Salon;
use App\DTO\BusinessHoursDTO;
use App\Entity\BusinessHoursRanges;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRangesRepository;

final class BusinessHoursService
{
    public function __construct(private EntityManagerInterface $entityManager, private BusinessHoursRangesRepository $businessHoursRangesRepository, SalonService $salonService) {}

    public function saveBusinessHours(Salon $salon, array $data): array
    {
        $businessHoursDTOs = array_map(fn($day) => BusinessHoursDTO::fromArray($day), $data);

        foreach ($salon->getBusinessHoursRanges() as $businessHoursRange) {
            $this->entityManager->remove($businessHoursRange);
        }

        $businessHoursRanges = [];

        foreach ($businessHoursDTOs as $dto) {
            if (!$dto->isOpen) {
                continue;
            }

            foreach ($dto->timeRanges as $range) {
                $businessHours = new BusinessHoursRanges();
                $businessHours->setDayOfWeek($dto->dayOfWeek);
                $businessHours->setStartTime(new \DateTime($range['start']));
                $businessHours->setEndTime(new \DateTime($range['end']));
                $businessHours->setSalon($salon);

                $businessHoursRanges[] = $businessHours;

                $this->entityManager->persist($businessHours);
            }
        }
        $this->entityManager->flush();

        return $businessHoursRanges;
    }
}
