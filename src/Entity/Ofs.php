<?php

namespace App\Entity;

use App\Repository\OfsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfsRepository::class)]
class Ofs
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'ofs', targetEntity: QualityCtrl::class)]
    private Collection $qualityCtrls;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\ManyToOne(inversedBy: 'ofs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Articles $article = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref1 = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref2 = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $refCli = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $order_qte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $launched_qte = null;

    #[ORM\ManyToOne(inversedBy: 'ofs')]
    private ?Clients $client = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $order_num = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $prevstart_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $start_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $end_at = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2)]
    private ?string $conditionnement = null;

    public function __construct()
    {
        $this->qualityCtrls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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
            $qualityCtrl->setOfs($this);
        }

        return $this;
    }

    public function removeQualityCtrl(QualityCtrl $qualityCtrl): static
    {
        if ($this->qualityCtrls->removeElement($qualityCtrl)) {
            // set the owning side to null (unless already changed)
            if ($qualityCtrl->getOfs() === $this) {
                $qualityCtrl->setOfs(null);
            }
        }

        return $this;
    }

    public function getDossier(): ?string
    {
        return $this->dossier;
    }

    public function setDossier(string $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getSref1(): ?string
    {
        return $this->sref1;
    }

    public function setSref1(?string $sref1): static
    {
        $this->sref1 = $sref1;

        return $this;
    }

    public function getSref2(): ?string
    {
        return $this->sref2;
    }

    public function setSref2(?string $sref2): static
    {
        $this->sref2 = $sref2;

        return $this;
    }

    public function getRefCli(): ?string
    {
        return $this->refCli;
    }

    public function setRefCli(?string $refCli): static
    {
        $this->refCli = $refCli;

        return $this;
    }

    public function getOrderQte(): ?string
    {
        return $this->order_qte;
    }

    public function setOrderQte(string $order_qte): static
    {
        $this->order_qte = $order_qte;

        return $this;
    }

    public function getLaunchedQte(): ?string
    {
        return $this->launched_qte;
    }

    public function setLaunchedQte(string $launched_qte): static
    {
        $this->launched_qte = $launched_qte;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getOrderNum(): ?string
    {
        return $this->order_num;
    }

    public function setOrderNum(?string $order_num): static
    {
        $this->order_num = $order_num;

        return $this;
    }

    public function getPrevstartAt(): ?\DateTimeImmutable
    {
        return $this->prevstart_at;
    }

    public function setPrevstartAt(\DateTimeImmutable $prevstart_at): static
    {
        $this->prevstart_at = $prevstart_at;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->start_at;
    }

    public function setStartAt(?\DateTimeImmutable $start_at): static
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->end_at;
    }

    public function setEndAt(?\DateTimeImmutable $end_at): static
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getConditionnement(): ?string
    {
        return $this->conditionnement;
    }

    public function setConditionnement(string $conditionnement): static
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }
}
