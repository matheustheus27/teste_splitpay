<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $revenue_id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $method = null;

    #[ORM\Column]
    private ?\DateTime $dt_payment = null;

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

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getDtPayment(): ?\DateTime
    {
        return $this->dt_payment;
    }

    public function setDtPayment(\DateTime $dt_payment): static
    {
        $this->dt_payment = $dt_payment;

        return $this;
    }
}
