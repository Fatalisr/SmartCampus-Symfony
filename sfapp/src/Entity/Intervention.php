<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startingDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endingDate = null;

    #[ORM\Column(length: 500)]
    private ?string $message = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SA $sa = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $report = null;

    #[ORM\Column(length: 15, options: ['check' => "check (type in ('INSTALLATION','MAINTENANCE'))"])]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    public function setEndingDate(\DateTimeInterface $endingDate): static
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getSa(): ?SA
    {
        return $this->sa;
    }

    public function setSa(?SA $sa): static
    {
        $this->sa = $sa;

        return $this;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(?string $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
