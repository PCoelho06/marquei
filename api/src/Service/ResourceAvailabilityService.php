<?php

namespace App\Service;

use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\ResourceAvailabilityDTO;
use App\Entity\ResourceAvailability;

class ResourceAvailabilityService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function setResourceAvailabilities(Resource $resource, array $data): void
    {
        $resourceAvailabilityDTOs = array_map(fn($day) => ResourceAvailabilityDTO::fromArray($day), $data);

        foreach ($resource->getResourceAvailabilities() as $availability) {
            $this->entityManager->remove($availability);
        }

        $resourceAvailabilities = [];

        foreach ($resourceAvailabilityDTOs as $dto) {
            if (!$dto->isAvailable) {
                continue;
            }

            foreach ($dto->timeRanges as $range) {
                $availability = new ResourceAvailability();
                $availability->setDayOfWeek($dto->dayOfWeek);
                $availability->setStartTime(new \DateTime($range['start']));
                $availability->setEndTime(new \DateTime($range['end']));
                $resource->addResourceAvailability($availability);

                $resourceAvailabilities[] = $availability;

                $this->entityManager->persist($availability);
            }
        }
        $this->entityManager->flush();
    }

    public function isAvailable(Resource $resource, \DateTimeImmutable $dateTime): bool
    {
        $dayOfWeek = (int) $dateTime->format('w');
        $time = $dateTime->format('H:i');

        foreach ($resource->getResourceAvailabilities() as $availability) {
            if ($availability->getDayOfWeek() === $dayOfWeek && $availability->getStartTime()->format('H:i') <= $time && $availability->getEndTime()->format('H:i') >= $time) {
                return true;
            }
        }

        return false;
    }
}
