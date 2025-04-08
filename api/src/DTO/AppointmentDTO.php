<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AppointmentDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public ?int $clientId = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?int $resourceId = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?int $serviceId = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?int $salonId = null;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\GreaterThanOrEqual('now Europe/Lisbon', groups: ['create'])]
    #[Assert\LessThan(propertyPath: 'endsAt', groups: ['create'])]
    public ?\DateTimeImmutable $startsAt = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?\DateTimeImmutable $endsAt = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $status = null;

    public function __construct(?string $startsAt, ?string $endsAt, ?int $serviceId, ?int $clientId, ?int $resourceId, ?int $salonId, ?string $status)
    {
        if ($startsAt) {
            $this->startsAt = \DateTimeImmutable::createFromFormat("d/m/Y, H:i", $startsAt);
        }
        if ($endsAt) {
            $this->endsAt = \DateTimeImmutable::createFromFormat("d/m/Y, H:i", $endsAt);
        }
        if ($serviceId) {
            $this->serviceId = $serviceId;
        }
        if ($clientId) {
            $this->clientId = $clientId;
        }
        if ($resourceId) {
            $this->resourceId = $resourceId;
        }
        if ($salonId) {
            $this->salonId = $salonId;
        }
        if ($status) {
            $this->status = $status;
        }
    }
}
