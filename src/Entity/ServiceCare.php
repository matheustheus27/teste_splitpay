<?php

namespace App\Entity;

use App\Repository\ServiceCareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceCareRepository::class)]
class ServiceCare
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $care_id = null;

    #[ORM\Column]
    private ?int $service_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCareId(): ?int
    {
        return $this->care_id;
    }

    public function setCareId(int $care_id): static
    {
        $this->care_id = $care_id;

        return $this;
    }

    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    public function setServiceId(int $service_id): static
    {
        $this->service_id = $service_id;

        return $this;
    }
}
