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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData5 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData6 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData7 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData8 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData9 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optData10 = null;

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

    public function getOptData1(): ?string
    {
        return $this->optData1;
    }

    public function setOptData1(?string $optData1): static
    {
        $this->optData1 = $optData1;

        return $this;
    }

    public function getOptData2(): ?string
    {
        return $this->optData2;
    }

    public function setOptData2(?string $optData2): static
    {
        $this->optData2 = $optData2;

        return $this;
    }

    public function getOptData3(): ?string
    {
        return $this->optData3;
    }

    public function setOptData3(?string $optData3): static
    {
        $this->optData3 = $optData3;

        return $this;
    }

    public function getOptData4(): ?string
    {
        return $this->optData4;
    }

    public function setOptData4(?string $optData4): static
    {
        $this->optData4 = $optData4;

        return $this;
    }

    public function getOptData5(): ?string
    {
        return $this->optData5;
    }

    public function setOptData5(?string $optData5): static
    {
        $this->optData5 = $optData5;

        return $this;
    }

    public function getOptData6(): ?string
    {
        return $this->optData6;
    }

    public function setOptData6(?string $optData6): static
    {
        $this->optData6 = $optData6;

        return $this;
    }

    public function getOptData7(): ?string
    {
        return $this->optData7;
    }

    public function setOptData7(?string $optData7): static
    {
        $this->optData7 = $optData7;

        return $this;
    }

    public function getOptData8(): ?string
    {
        return $this->optData8;
    }

    public function setOptData8(?string $optData8): static
    {
        $this->optData8 = $optData8;

        return $this;
    }

    public function getOptData9(): ?string
    {
        return $this->optData9;
    }

    public function setOptData9(?string $optData9): static
    {
        $this->optData9 = $optData9;

        return $this;
    }

    public function getOptData10(): ?string
    {
        return $this->optData10;
    }

    public function setOptData10(?string $optData10): static
    {
        $this->optData10 = $optData10;

        return $this;
    }
}
