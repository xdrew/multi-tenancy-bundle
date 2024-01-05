<?php

declare (strict_types=1);

namespace MultiTenancyBundle\Entity;

use MultiTenancyBundle\Repository\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: TenantRepository::class)]
class Tenant implements \JsonSerializable
{
    #[Id, GeneratedValue, Column]
    private ?int $id = null;

    #[Column(unique: true)]
    private string $uuid;

    #[Column(nullable: true)]
    private ?\DateTimeImmutable $created_at;

    #[Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at;

    #[Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at;

    #[OneToMany(mappedBy: "tenant", targetEntity: Hostname::class, orphanRemoval: true)]
    private Collection $hostnames;

    public function __construct()
    {
        $this->hostnames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    /**
     * @return Collection|Hostname[]
     */
    public function getHostnames(): Collection
    {
        return $this->hostnames;
    }

    public function addHostname(Hostname $hostname): self
    {
        if (!$this->hostnames->contains($hostname)) {
            $this->hostnames[] = $hostname;
            $hostname->setTenant($this);
        }

        return $this;
    }

    public function removeHostname(Hostname $hostname): self
    {
        if ($this->hostnames->removeElement($hostname)) {
            // set the owning side to null (unless already changed)
            if ($hostname->getTenant() === $this) {
                $hostname->setTenant(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'uuid' => $this->getUuid(),
            'created_at' => $this->getCreatedAt(),
            'hostnames' => $this->getHostnames()->map(fn($object) => $object->getFqdn())
        ];
    }
}
