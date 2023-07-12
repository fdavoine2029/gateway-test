<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\sklblSkuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: sklblSkuRepository::class)]
class sklblSku
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sku = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $skuTisse = null;

    #[ORM\Column]
    private ?int $order_qte = null;

    #[ORM\Column(nullable: true)]
    private ?int $produce_qte = null;

    #[ORM\Column(nullable: true)]
    private ?int $off_qte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'sklblSkus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SklblOrders $sklblOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $vendor = null;

    #[ORM\OneToMany(mappedBy: 'sklblSku', targetEntity: SklblFx::class, orphanRemoval: true)]
    private Collection $sklblFxs;

    #[ORM\ManyToOne(inversedBy: 'sklblSkus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SklblFiles $sklblFile = null;

    #[ORM\Column]
    private ?int $status = null;

    public function __construct()
    {
        $this->sklblFxs = new ArrayCollection();
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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getSkuTisse(): ?string
    {
        return $this->skuTisse;
    }

    public function setSkuTisse(string $skuTisse): static
    {
        $this->skuTisse = $skuTisse;

        return $this;
    }

    public function getOrderQte(): ?int
    {
        return $this->order_qte;
    }

    public function setOrderQte(int $order_qte): static
    {
        $this->order_qte = $order_qte;

        return $this;
    }

    public function getProduceQte(): ?int
    {
        return $this->produce_qte;
    }

    public function setProduceQte(?int $produce_qte): static
    {
        $this->produce_qte = $produce_qte;

        return $this;
    }

    public function getOffQte(): ?int
    {
        return $this->off_qte;
    }

    public function setOffQte(?int $off_qte): static
    {
        $this->off_qte = $off_qte;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(string $vendor): static
    {
        $this->vendor = $vendor;

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
            $sklblFx->setSklblSku($this);
        }

        return $this;
    }

    public function removeSklblFx(SklblFx $sklblFx): static
    {
        if ($this->sklblFxs->removeElement($sklblFx)) {
            // set the owning side to null (unless already changed)
            if ($sklblFx->getSklblSku() === $this) {
                $sklblFx->setSklblSku(null);
            }
        }

        return $this;
    }

    public function getSklblFile(): ?SklblFiles
    {
        return $this->sklblFile;
    }

    public function setSklblFile(?SklblFiles $sklblFile): static
    {
        $this->sklblFile = $sklblFile;

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
