<?php

namespace App\Entity\Divalto;

use App\Repository\Divalto\ARTRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ARTRepository::class)]
#[ORM\Table(name:'ART')]

class ART
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ART_ID')]
    private ?int $ART_ID= null;

    #[ORM\Column(name: 'DOS',length: 8)]
    private ?string $DOS = null;

    #[ORM\Column(name: 'REF',length: 25)]
    private ?string $REF = null;

    #[ORM\Column(name: 'DES',length: 80)]
    private ?string $DES = null;

    #[ORM\Column(name: 'ABCCOD',length: 1)]
    private ?string $ABCCOD = null;

    public function getART_ID(): ?int
    {
        return $this->ART_ID;
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

    public function getREF(): ?string
    {
        return $this->REF;
    }

    public function setREF(string $REF): static
    {
        $this->REF = $REF;

        return $this;
    }

    public function getDES(): ?string
    {
        return $this->DES;
    }

    public function setDES(string $DES): static
    {
        $this->DES = $DES;

        return $this;
    }

    public function getABCCOD(): ?string
    {
        return $this->ABCCOD;
    }

    public function setABCCOD(string $ABCCOD): static
    {
        $this->ABCCOD = $ABCCOD;

        return $this;
    }
}
