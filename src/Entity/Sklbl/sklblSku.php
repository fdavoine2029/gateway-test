<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\sklblSkuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: sklblSkuRepository::class)]
class sklblSku
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $order_qte = null;

    #[ORM\Column(nullable: true)]
    private ?int $produce_qte = null;

    #[ORM\Column(nullable: true)]
    private ?int $off_qte = null;

    #[ORM\ManyToOne(inversedBy: 'sklblSkus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SklblOrders $sklblOrder = null;

    #[ORM\OneToMany(mappedBy: 'sklblSku', targetEntity: SklblFx::class, orphanRemoval: true)]
    private Collection $sklblFxs;

    #[ORM\ManyToOne(inversedBy: 'sklblSkus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SklblFiles $sklblFile = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data3 = null;

  

   

    

    public function __construct()
    {
        $this->sklblFxs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getSklblOrder(): ?SklblOrders
    {
        return $this->sklblOrder;
    }

    public function setSklblOrder(?SklblOrders $sklblOrder): static
    {
        $this->sklblOrder = $sklblOrder;

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

    public function getData1(): ?string
    {
        return $this->data1;
    }

    public function setData1(?string $data1): static
    {
        $this->data1 = $data1;

        return $this;
    }

    public function getData2(): ?string
    {
        return $this->data2;
    }

    public function setData2(?string $data2): static
    {
        $this->data2 = $data2;

        return $this;
    }

    public function getData3(): ?string
    {
        return $this->data3;
    }

    public function setData3(?string $data3): static
    {
        $this->data3 = $data3;

        return $this;
    }

   

    

    
}
