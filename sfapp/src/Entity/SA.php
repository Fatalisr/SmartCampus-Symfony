<?php

namespace App\Entity;

use App\Repository\SARepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SARepository::class)]
class SA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $name = null;

    #[ORM\Column(length: 15, options: ['check' => "check (state in ('INACTIF','ACTIF','MAINTENANCE','A_INSTALLER'))"])]
    private ?string $state = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Room $currentRoom = null;

    #[ORM\ManyToOne]
    private ?Room $oldRoom = null;


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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCurrentRoom(): ?Room
    {
        return $this->currentRoom;
    }

    public function setCurrentRoom(?Room $currentRoom): static
    {
        $this->currentRoom = $currentRoom;

        return $this;
    }

    public function getOldRoom(): ?Room
    {
        return $this->oldRoom;
    }

    public function setOldRoom(?Room $oldRoom): static
    {
        $this->oldRoom = $oldRoom;

        return $this;
    }

}
