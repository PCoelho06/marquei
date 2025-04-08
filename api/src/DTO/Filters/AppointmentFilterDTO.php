<?php

namespace App\DTO\Filters;

use App\Utils\QueryStringParserTrait;
use Symfony\Component\Validator\Constraints as Assert;

class AppointmentFilterDTO extends BaseFilterDTO
{
    use QueryStringParserTrait;

    public ?int $clientId = null;
    public ?int $resourceId = null;
    public ?int $serviceId = null;
    public ?int $salonId = null;

    #[Assert\DateTime(format: 'd/m/Y, H:i')]
    public ?string $startsAt = null;

    #[Assert\DateTime(format: 'd/m/Y, H:i')]
    public ?string $endsAt = null;

    #[Assert\Choice(choices: ['pending', 'confirmed', 'canceled'], message: 'Choose a valid status')]
    public ?string $status = null;

    public function __construct(array $queryParams)
    {
        parent::__construct($queryParams);
        $this->clientId = $queryParams['clientId'] ?? null;
        $this->resourceId = $queryParams['resourceId'] ?? null;
        $this->serviceId = $queryParams['serviceId'] ?? null;
        $this->salonId = $queryParams['salonId'] ?? null;
        $this->status = $queryParams['status'] ?? null;
        $this->startsAt = $queryParams['startsAt'] ?? null;
        $this->endsAt = $queryParams['endsAt'] ?? null;
    }
}
