<?php

namespace App\Entity;

use App\Repository\OrderSupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderSupRepository::class)]
class OrderSup
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\ManyToOne]
    private ?Fournisseurs $fournisseurs = null;

    #[ORM\ManyToOne]
    private ?Articles $articles = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $order_num = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 14, scale: '0')]
    private ?string $record_num = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(length: 80)]
    private ?string $designation = null;

    #[ORM\Column(length: 20)]
    private ?string $buyer = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 3)]
    private ?string $order_qte = null;

    #[ORM\Column(length: 4)]
    private ?string $unit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 4)]
    private ?string $currency = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref1 = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $delay = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: '0')]
    private ?string $trans = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $delivery_place = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 3)]
    private ?string $toDeliver_Qte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $delivery_note = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $batch_num = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $receiv_qte = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(length: 4)]
    private ?string $blmod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $delay_trsp = null;

    #[ORM\OneToMany(mappedBy: 'orderSup', targetEntity: ReceivSupDetails::class)]
    private Collection $receivSupDetails;

    #[ORM\Column]
    private ?int $sync = null;

    #[ORM\Column(length: 3)]
    private ?string $Etablissement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    private ?string $order_line = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 14, scale: '0')]
    private ?string $bl_line = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 14, scale: '0')]
    private ?string $no_ventilation = null;

    #[ORM\OneToMany(mappedBy: 'order_sup', targetEntity: QualityCtrl::class)]
    private Collection $qualityCtrls;

    public function __construct()
    {
        $this->receivSupDetails = new ArrayCollection();
        $this->qualityCtrls = new ArrayCollection();
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


    public function getDossier(): ?string
    {
        return $this->dossier;
    }

    public function setDossier(string $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getFournisseur(): ?Fournisseurs
    {
        return $this->fournisseurs;
    }

    public function setFournisseur(?Fournisseurs $fournisseurs): self
    {
        $this->fournisseurs = $fournisseurs;
        return $this;
    }

    public function getArticle(): ?Articles
    {
        return $this->articles;
    }

    public function setArticle(?Articles $articles): self
    {
        $this->articles = $articles;
        return $this;
    }

    public function getOrderNum(): ?string
    {
        return $this->order_num;
    }

    public function setOrderNum(string $order_num): self
    {
        $this->order_num = $order_num;

        return $this;
    }

    public function getRecordNum(): ?string
    {
        return $this->record_num;
    }

    public function setRecordNum(string $record_num): self
    {
        $this->record_num = $record_num;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): self
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getBuyer(): ?string
    {
        return $this->buyer;
    }

    public function setBuyer(string $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getOrderQte(): ?string
    {
        return $this->order_qte;
    }

    public function setOrderQte(string $order_qte): self
    {
        $this->order_qte = $order_qte;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getSref1(): ?string
    {
        return $this->sref1;
    }

    public function setSref1(string $sref1): self
    {
        $this->sref1 = $sref1;

        return $this;
    }

    public function getSref2(): ?string
    {
        return $this->sref2;
    }

    public function setSref2(?string $sref2): self
    {
        $this->sref2 = $sref2;

        return $this;
    }

    public function getDelay(): ?\DateTimeInterface
    {
        return $this->delay;
    }

    public function setDelay(\DateTimeInterface $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function getTrans(): ?string
    {
        return $this->trans;
    }

    public function setTrans(string $trans): self
    {
        $this->trans = $trans;

        return $this;
    }

    public function getDeliveryPlace(): ?string
    {
        return $this->delivery_place;
    }

    public function setDeliveryPlace(?string $delivery_place): self
    {
        $this->delivery_place = $delivery_place;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, ReceivSup>
     */

    public function getToDeliverQte(): ?string
    {
        return $this->toDeliver_Qte;
    }

    public function setToDeliverQte(string $toDeliver_Qte): static
    {
        $this->toDeliver_Qte = $toDeliver_Qte;

        return $this;
    }

    public function getDeliveryNote(): ?string
    {
        return $this->delivery_note;
    }

    public function setDeliveryNote(?string $delivery_note): static
    {
        $this->delivery_note = $delivery_note;

        return $this;
    }

    public function getBatchNum(): ?string
    {
        return $this->batch_num;
    }

    public function setBatchNum(?string $batch_num): static
    {
        $this->batch_num = $batch_num;

        return $this;
    }

    public function getReceivQte(): ?string
    {
        return $this->receiv_qte;
    }

    public function setReceivQte(string $receiv_qte): static
    {
        $this->receiv_qte = $receiv_qte;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

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

    public function getBlmod(): ?string
    {
        return $this->blmod;
    }

    public function setBlmod(string $blmod): static
    {
        $this->blmod = $blmod;

        return $this;
    }

    public function getDelayTrsp(): ?\DateTimeInterface
    {
        return $this->delay_trsp;
    }

    public function setDelayTrsp(\DateTimeInterface $delay_trsp): static
    {
        $this->delay_trsp = $delay_trsp;

        return $this;
    }

    /**
     * @return Collection<int, ReceivSupDetails>
     */
    public function getReceivSupDetails(): Collection
    {
        return $this->receivSupDetails;
    }

    public function addReceivSupDetail(ReceivSupDetails $receivSupDetail): static
    {
        if (!$this->receivSupDetails->contains($receivSupDetail)) {
            $this->receivSupDetails->add($receivSupDetail);
            $receivSupDetail->setOrderSup($this);
        }

        return $this;
    }

    public function removeReceivSupDetail(ReceivSupDetails $receivSupDetail): static
    {
        if ($this->receivSupDetails->removeElement($receivSupDetail)) {
            // set the owning side to null (unless already changed)
            if ($receivSupDetail->getOrderSup() === $this) {
                $receivSupDetail->setOrderSup(null);
            }
        }

        return $this;
    }

    public function getSync(): ?int
    {
        return $this->sync;
    }

    public function setSync(int $sync): static
    {
        $this->sync = $sync;

        return $this;
    }

    public function getEtablissement(): ?string
    {
        return $this->Etablissement;
    }

    public function setEtablissement(string $Etablissement): static
    {
        $this->Etablissement = $Etablissement;

        return $this;
    }

    public function getOrderLine(): ?string
    {
        return $this->order_line;
    }

    public function setOrderLine(string $order_line): static
    {
        $this->order_line = $order_line;

        return $this;
    }

    public function getBlLine(): ?string
    {
        return $this->bl_line;
    }

    public function setBlLine(string $bl_line): static
    {
        $this->bl_line = $bl_line;

        return $this;
    }

    public function getNoVentilation(): ?string
    {
        return $this->no_ventilation;
    }

    public function setNoVentilation(string $no_ventilation): static
    {
        $this->no_ventilation = $no_ventilation;

        return $this;
    }

    /**
     * @return Collection<int, QualityCtrl>
     */
    public function getQualityCtrls(): Collection
    {
        return $this->qualityCtrls;
    }

    public function addQualityCtrl(QualityCtrl $qualityCtrl): static
    {
        if (!$this->qualityCtrls->contains($qualityCtrl)) {
            $this->qualityCtrls->add($qualityCtrl);
            $qualityCtrl->setOrderSup($this);
        }

        return $this;
    }

    public function removeQualityCtrl(QualityCtrl $qualityCtrl): static
    {
        if ($this->qualityCtrls->removeElement($qualityCtrl)) {
            // set the owning side to null (unless already changed)
            if ($qualityCtrl->getOrderSup() === $this) {
                $qualityCtrl->setOrderSup(null);
            }
        }

        return $this;
    }
}
