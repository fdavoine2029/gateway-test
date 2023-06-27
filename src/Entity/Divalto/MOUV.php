<?php

namespace App\Entity\Divalto;

use App\Repository\Divalto\MOUVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MOUVRepository::class)]
class MOUV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $DOS = null;

    #[ORM\Column(length: 25)]
    private ?string $REF = null;

    #[ORM\Column(length: 8)]
    private ?string $SREF1 = null;

    #[ORM\Column(length: 8)]
    private ?string $SREF2 = null;

    #[ORM\Column(length: 20)]
    private ?string $TIERS = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 14, scale: '0')]
    private ?string $ENRNO = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $CDNO = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $CDDT = null;

    #[ORM\Column(length: 80)]
    private ?string $DES = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $CDQTE = null;

    #[ORM\Column(length: 4)]
    private ?string $REFUN = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2)]
    private ?string $MONT = null;

    #[ORM\Column(length: 4)]
    private ?string $DEV = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $BLNO = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $BLQTE = null;

    #[ORM\Column(length: 3)]
    private ?string $DEPO = null;

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

    public function getREF(): ?string
    {
        return $this->REF;
    }

    public function setREF(string $REF): static
    {
        $this->REF = $REF;

        return $this;
    }

    public function getSREF1(): ?string
    {
        return $this->SREF1;
    }

    public function setSREF1(string $SREF1): static
    {
        $this->SREF1 = $SREF1;

        return $this;
    }

    public function getSREF2(): ?string
    {
        return $this->SREF2;
    }

    public function setSREF2(?string $SREF2): static
    {
        $this->SREF2 = $SREF2;

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

    public function getENRNO(): ?string
    {
        return $this->ENRNO;
    }

    public function setENRNO(string $ENRNO): static
    {
        $this->ENRNO = $ENRNO;

        return $this;
    }

    public function getCDNO(): ?string
    {
        return $this->CDNO;
    }

    public function setCDNO(string $CDNO): static
    {
        $this->CDNO = $CDNO;

        return $this;
    }

    public function getCDDT(): ?\DateTimeInterface
    {
        return $this->CDDT;
    }

    public function setCDDT(\DateTimeInterface $CDDT): static
    {
        $this->CDDT = $CDDT;

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

    public function getCDQTE(): ?string
    {
        return $this->CDQTE;
    }

    public function setCDQTE(string $CDQTE): static
    {
        $this->CDQTE = $CDQTE;

        return $this;
    }

    public function getREFUN(): ?string
    {
        return $this->REFUN;
    }

    public function setREFUN(string $REFUN): static
    {
        $this->REFUN = $REFUN;

        return $this;
    }

    public function getMONT(): ?string
    {
        return $this->MONT;
    }

    public function setMONT(string $MONT): static
    {
        $this->MONT = $MONT;

        return $this;
    }

    public function getDEV(): ?string
    {
        return $this->DEV;
    }

    public function setDEV(string $DEV): static
    {
        $this->DEV = $DEV;

        return $this;
    }

    public function getBLNO(): ?string
    {
        return $this->BLNO;
    }

    public function setBLNO(string $BLNO): static
    {
        $this->BLNO = $BLNO;

        return $this;
    }

    public function getBLQTE(): ?string
    {
        return $this->BLQTE;
    }

    public function setBLQTE(string $BLQTE): static
    {
        $this->BLQTE = $BLQTE;

        return $this;
    }

    public function getDEPO(): ?string
    {
        return $this->DEPO;
    }

    public function setDEPO(string $DEPO): static
    {
        $this->DEPO = $DEPO;

        return $this;
    }
}
