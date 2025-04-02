<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ResourceExceptionDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public int $day;
    #[Assert\NotBlank(groups: ['create'])]
    public string $startTime;
    #[Assert\NotBlank(groups: ['create'])]
    public string $endTime;
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Positive(groups: ['create'])]
    public int $resourceId;

    public function __construct(int $day, string $startTime, string $endTime, int $resourceId)
    {
        $this->day = $day;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->resourceId = $resourceId;
    }
}
