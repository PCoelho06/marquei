<?php

namespace App\Entity;

use App\Model\ResourceTypeEnum;
use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: ResourceTypeEnum::class)]
    private ?ResourceTypeEnum $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salon $salon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'resources')]
    private Collection $services;

    /**
     * @var Collection<int, ResourceAvailability>
     */
    #[ORM\OneToMany(targetEntity: ResourceAvailability::class, mappedBy: 'resource')]
    private Collection $resourceAvailabilities;

    /**
     * @var Collection<int, ResourceException>
     */
    #[ORM\OneToMany(targetEntity: ResourceException::class, mappedBy: 'resource')]
    private Collection $resourceExceptions;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'resource')]
    private Collection $appointments;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->resourceAvailabilities = new ArrayCollection();
        $this->resourceExceptions = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?ResourceTypeEnum
    {
        return $this->type;
    }

    public function setType(ResourceTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSalon(): ?Salon
    {
        return $this->salon;
    }

    public function setSalon(?Salon $salon): static
    {
        $this->salon = $salon;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'salon' => $this->salon->toArray(),
            'resourceAvailabilities' => $this->resourceAvailabilities->map(fn(ResourceAvailability $availability) => $availability->toArray())->toArray(),
            'resourceExceptions' => $this->resourceExceptions->map(fn(ResourceException $exception) => $exception->toArray())->toArray(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addResource($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            $service->removeResource($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ResourceAvailability>
     */
    public function getResourceAvailabilities(): Collection
    {
        return $this->resourceAvailabilities;
    }

    public function addResourceAvailability(ResourceAvailability $resourceAvailability): static
    {
        if (!$this->resourceAvailabilities->contains($resourceAvailability)) {
            $this->resourceAvailabilities->add($resourceAvailability);
            $resourceAvailability->setResource($this);
        }

        return $this;
    }

    public function removeResourceAvailability(ResourceAvailability $resourceAvailability): static
    {
        if ($this->resourceAvailabilities->removeElement($resourceAvailability)) {
            // set the owning side to null (unless already changed)
            if ($resourceAvailability->getResource() === $this) {
                $resourceAvailability->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResourceException>
     */
    public function getResourceExceptions(): Collection
    {
        return $this->resourceExceptions;
    }

    public function addResourceException(ResourceException $resourceException): static
    {
        if (!$this->resourceExceptions->contains($resourceException)) {
            $this->resourceExceptions->add($resourceException);
            $resourceException->setResource($this);
        }

        return $this;
    }

    public function removeResourceException(ResourceException $resourceException): static
    {
        if ($this->resourceExceptions->removeElement($resourceException)) {
            // set the owning side to null (unless already changed)
            if ($resourceException->getResource() === $this) {
                $resourceException->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setResource($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getResource() === $this) {
                $appointment->setResource(null);
            }
        }

        return $this;
    }
}
