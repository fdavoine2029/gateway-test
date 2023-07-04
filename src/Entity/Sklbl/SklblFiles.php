<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblFilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblFilesRepository::class)]
class SklblFiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFiles')]
    private ?SklblOrders $sklblOrder = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFiles')]
    private ?SklblOf $sklblOf = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientFilename = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $vendorColumn = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $skuColumn = null;

    #[ORM\Column(length: 3)]
    private ?string $qteColumn = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSklblOrder(): ?SklblOrders
    {
        return $this->sklblOrder;
    }

    public function setSklblOrder(?SklblOrders $sklblOrder): static
    {
        $this->sklblOrder = $sklblOrder;

        return $this;
    }

    public function getSklblOf(): ?SklblOf
    {
        return $this->sklblOf;
    }

    public function setSklblOf(?SklblOf $sklblOf): static
    {
        $this->sklblOf = $sklblOf;

        return $this;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    public function setClientFilename(?string $clientFilename): static
    {
        $this->clientFilename = $clientFilename;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getVendorColumn(): ?string
    {
        return $this->vendorColumn;
    }

    public function setVendorColumn(?string $vendorColumn): static
    {
        $this->vendorColumn = $vendorColumn;

        return $this;
    }

    public function getSkuColumn(): ?string
    {
        return $this->skuColumn;
    }

    public function setSkuColumn(?string $skuColumn): static
    {
        $this->skuColumn = $skuColumn;

        return $this;
    }

    public function getQteColumn(): ?string
    {
        return $this->qteColumn;
    }

    public function setQteColumn(string $qteColumn): static
    {
        $this->qteColumn = $qteColumn;

        return $this;
    }
}
