<?php

namespace App\Entity;

use App\Repository\ProfessionalCareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionalCareRepository::class)]
class ProfessionalCare
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $revenue_id = null;

    #[ORM\Column(length: 11)]
    private ?string $client_id = null;

    #[ORM\Column(length: 11)]
    private ?string $professional_id = null;

    #[ORM\Column]
    private ?\DateTime $dt_service = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getRevenueId(): ?int
    {
        return $this->revenue_id;
    }

    public function setRevenueId(int $revenue_id): static
    {
        $this->revenue_id = $revenue_id;

        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function setClientId(string $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getProfessionalId(): ?string
    {
        return $this->professional_id;
    }

    public function setProfessionalId(string $professional_id): static
    {
        $this->professional_id = $professional_id;

        return $this;
    }

    public function getDtService(): ?\DateTime
    {
        return $this->dt_service;
    }

    public function setDtService(\DateTime $dt_service): static
    {
        $this->dt_service = $dt_service;

        return $this;
    }
}
