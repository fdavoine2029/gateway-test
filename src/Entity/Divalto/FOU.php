<?php

namespace App\Entity\Divalto;

use App\Repository\Divalto\FOURepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FOURepository::class)]
#[ORM\Table(name:'FOU')]
class FOU
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'FOU_ID')]
    private ?int $id = null;

    #[ORM\Column(name: 'DOS',length: 8)]
    private ?string $DOS = null;

    #[ORM\Column(name: 'TIERS',length: 20)]
    private ?string $TIERS = null;

    #[ORM\Column(name: 'NOM',length: 80)]
    private ?string $NOM = null;

    #[ORM\Column(name: 'PAY',length: 3)]
    private ?string $PAY = null;

    #[ORM\Column(name: 'TRANSJRNB',type: Types::DECIMAL, precision: 3, scale: '0')]
    private ?string $TRANSJRNB = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

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

    public function getTRANSJRNB(): ?string
    {
        return $this->TRANSJRNB;
    }

    public function setTRANSJRNB(string $TRANSJRNB): static
    {
        $this->TRANSJRNB = $TRANSJRNB;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
