<?php

declare (strict_types=1);

namespace MultiTenancyBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use MultiTenancyBundle\Repository\HostnameRepository;

#[Entity(repositoryClass: HostnameRepository::class)]
class Hostname
{
    #[Id, GeneratedValue, Column]
    private ?int $id = null;

    #[Column(length: 255)]
    private string $fqdn;

    #[ManyToOne(targetEntity: Tenant::class, fetch: "EAGER", inversedBy: "hostnames")]
    #[JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }

    public function setFqdn(string $fqdn): self
    {
        $this->fqdn = $fqdn;

        return $this;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;

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
}
