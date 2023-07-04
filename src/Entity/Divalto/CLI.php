<?php

namespace App\Entity\Divalto;

use App\Repository\Divalto\CLIRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CLIRepository::class)]
class CLI
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $DOS = null;

    #[ORM\Column(length: 20)]
    private ?string $TIERS = null;

    #[ORM\Column(length: 80)]
    private ?string $NOM = null;

    #[ORM\Column(length: 3)]
    private ?string $PAY = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDOS(): ?string
    {
        return $this->DOS;
    }

    public function setDOS(string $DOS): static
    {
        $this->DOS = $DOS;

        return $this;
    }

    public function getTIERS(): ?string
    {
        return $this->TIERS;
    }

    public function setTIERS(string $TIERS): static
    {
        $this->TIERS = $TIERS;

        return $this;
    }

    public function getNOM(): ?string
    {
        return $this->NOM;
    }

    public function setNOM(string $NOM): static
    {
        $this->NOM = $NOM;

        return $this;
    }

    public function getPAY(): ?string
    {
        return $this->PAY;
    }

    public function setPAY(string $PAY): static
    {
        $this->PAY = $PAY;

        return $this;
    }


}
