<?php

namespace App\Entity;

use App\Repository\SalonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Salon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, UserSalon>
     */
    #[ORM\OneToMany(mappedBy: "salon", targetEntity: UserSalon::class, cascade: ["persist", "remove"])]
    private Collection $users;

    /**
     * @var Collection<int, BusinessHoursRanges>
     */
    #[ORM\OneToMany(targetEntity: BusinessHoursRanges::class, mappedBy: 'salon', orphanRemoval: true)]
    private Collection $businessHoursRanges;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'salon', orphanRemoval: true)]
    private Collection $services;

    /**
     * @var Collection<int, Resource>
     */
    #[ORM\OneToMany(targetEntity: Resource::class, mappedBy: 'salon', orphanRemoval: true)]
    private Collection $resources;

    /**
     * @var Collection<int, Subscription>
     */
    #[ORM\OneToMany(targetEntity: Subscription::class, mappedBy: 'salon')]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->businessHoursRanges = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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

    /**
     * @return Collection<int, UserSalon>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserSalon $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSalon($this);
        }

        return $this;
    }

    public function removeUser(UserSalon $user): static
    {
        if ($this->users->removeElement($user)) {
            if ($user->getSalon() === $this) {
                $user->setSalon(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return Collection<int, BusinessHoursRanges>
     */
    public function getBusinessHoursRanges(): Collection
    {
        return $this->businessHoursRanges;
    }

    public function addBusinessHoursRange(BusinessHoursRanges $businessHoursRange): static
    {
        if (!$this->businessHoursRanges->contains($businessHoursRange)) {
            $this->businessHoursRanges->add($businessHoursRange);
            $businessHoursRange->setSalon($this);
        }

        return $this;
    }

    public function removeBusinessHoursRange(BusinessHoursRanges $businessHoursRange): static
    {
        if ($this->businessHoursRanges->removeElement($businessHoursRange)) {
            // set the owning side to null (unless already changed)
            if ($businessHoursRange->getSalon() === $this) {
                $businessHoursRange->setSalon(null);
            }
        }

        return $this;
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
            $service->setSalon($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getSalon() === $this) {
                $service->setSalon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setSalon($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): static
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getSalon() === $this) {
                $resource->setSalon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setSalon($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getSalon() === $this) {
                $subscription->setSalon(null);
            }
        }

        return $this;
    }
}
