<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblFilesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column]
    private ?bool $deleteSku = null;

    #[ORM\Column]
    private ?int $ligne = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $idColumn = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $skuTisseColumn = null;

    #[ORM\OneToMany(mappedBy: 'sklblFile', targetEntity: SklblFx::class)]
    private Collection $sklblFxs;

    #[ORM\OneToMany(mappedBy: 'sklblFile', targetEntity: sklblSku::class)]
    private Collection $sklblSkus;

    #[ORM\Column]
    private ?int $status = null;

    public function __construct()
    {
        $this->sklblFxs = new ArrayCollection();
        $this->sklblSkus = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
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


    public function isDeleteSku(): ?bool
    {
        return $this->deleteSku;
    }

    public function setDeleteSku(bool $deleteSku): static
    {
        $this->deleteSku = $deleteSku;

        return $this;
    }

    public function getLigne(): ?int
    {
        return $this->ligne;
    }

    public function setLigne(int $ligne): static
    {
        $this->ligne = $ligne;

        return $this;
    }

    public function getIdColumn(): ?string
    {
        return $this->idColumn;
    }

    public function setIdColumn(?string $idColumn): static
    {
        $this->idColumn = $idColumn;

        return $this;
    }

    public function getSkuTisseColumn(): ?string
    {
        return $this->skuTisseColumn;
    }

    public function setSkuTisseColumn(string $skuTisseColumn): static
    {
        $this->skuTisseColumn = $skuTisseColumn;

        return $this;
    }

    /**
     * @return Collection<int, SklblFx>
     */
    public function getSklblFxs(): Collection
    {
        return $this->sklblFxs;
    }

    public function addSklblFx(SklblFx $sklblFx): static
    {
        if (!$this->sklblFxs->contains($sklblFx)) {
            $this->sklblFxs->add($sklblFx);
            $sklblFx->setSklblFile($this);
        }

        return $this;
    }

    public function removeSklblFx(SklblFx $sklblFx): static
    {
        if ($this->sklblFxs->removeElement($sklblFx)) {
            // set the owning side to null (unless already changed)
            if ($sklblFx->getSklblFile() === $this) {
                $sklblFx->setSklblFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, sklblSku>
     */
    public function getSklblSkus(): Collection
    {
        return $this->sklblSkus;
    }

    public function addSklblSku(sklblSku $sklblSku): static
    {
        if (!$this->sklblSkus->contains($sklblSku)) {
            $this->sklblSkus->add($sklblSku);
            $sklblSku->setSklblFile($this);
        }

        return $this;
    }

    public function removeSklblSku(sklblSku $sklblSku): static
    {
        if ($this->sklblSkus->removeElement($sklblSku)) {
            // set the owning side to null (unless already changed)
            if ($sklblSku->getSklblFile() === $this) {
                $sklblSku->setSklblFile(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
