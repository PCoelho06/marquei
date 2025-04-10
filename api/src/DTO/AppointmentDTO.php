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
    public ?\DateTimeImmutable $startsAt = null;

    public ?string $status = null;

    public function __construct(?string $startsAt, ?int $serviceId, ?int $clientId, ?int $resourceId, ?int $salonId, ?string $status)
    {
        if ($startsAt) {
            $this->startsAt = \DateTimeImmutable::createFromFormat("d/m/Y, H:i", $startsAt);
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
