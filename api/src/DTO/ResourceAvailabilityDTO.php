<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ResourceAvailabilityDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public int $dayOfWeek;
    #[Assert\NotBlank(groups: ['create'])]
    public bool $isAvailable;
    #[Assert\NotBlank(groups: ['create'])]
    public array $timeRanges;

    public function __construct(int $dayOfWeek, bool $isAvailable, array $timeRanges)
    {
        $this->dayOfWeek = $dayOfWeek;
        $this->isAvailable = $isAvailable;
        $this->timeRanges = $timeRanges;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['dayOfWeek'],
            $data['isAvailable'],
            $data['timeRanges'],
        );
    }
    public function toArray(): array
    {
        return [
            'dayOfWeek' => $this->dayOfWeek,
            'isAvailable' => $this->isAvailable,
            'timeRanges' => $this->timeRanges,
        ];
    }
}
