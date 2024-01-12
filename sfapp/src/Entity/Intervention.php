<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $message = null;


    #[ORM\Column(length: 500, nullable: true)]
    private ?string $report = null;

    #[ORM\Column(length: 15, options: ['check' => "check (type_i in ('INSTALLATION','MAINTENANCE'))"])]
    private ?string $type_i = null;

    #[ORM\Column(length: 8, options: ['check' => "check (state in ('EN_COURS','FINIE','ANNULEE'))"], nullable: true)]
    private ?string $state = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SA $sa = null;

    #[ORM\ManyToOne]
    private ?User $technicien = null;

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

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(?string $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getType_I(): ?string
    {
        return $this->type_i;
    }

    public function setType_I(string $type_I): static
    {
        $this->type_i = $type_I;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

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

    public function getTechnicien(): ?User
    {
        return $this->technicien;
    }

    public function setTechnicien(?User $technicien): static
    {
        $this->technicien = $technicien;

        return $this;
    }
}
