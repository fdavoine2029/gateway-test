<?php

namespace App\Entity\Sklbl;
use App\Entity\Articles;
use App\Entity\Clients;
use App\Entity\SklblSklblLisageConfig;
use App\Repository\Sklbl\SklblOrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblOrdersRepository::class)]
class SklblOrders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $order_num = null;

    #[ORM\ManyToOne(inversedBy: 'sklblOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'sklblOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Articles $article = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref1 = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref2 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 3)]
    private ?string $order_qte = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $order_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'sklblOrders')]
    private ?self $sklblOrder = null;

    #[ORM\OneToMany(mappedBy: 'sklblOrder', targetEntity: SklblOf::class)]
    private Collection $sklblOfs;

    #[ORM\Column]
    private ?int $sklblStatus = null;

    #[ORM\OneToMany(mappedBy: 'sklblOrder', targetEntity: SklblFiles::class)]
    private Collection $sklblFiles;

    #[ORM\OneToMany(mappedBy: 'sklblOrder', targetEntity: sklblSku::class)]
    private Collection $sklblSkus;

    #[ORM\Column(nullable: true)]
    private ?int $qteLimit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1, nullable: true)]
    private ?string $percentAboveLimit = null;

    #[ORM\OneToMany(mappedBy: 'sklblOrder', targetEntity: SklblFx::class)]
    private Collection $sklbkFxs;

    #[ORM\OneToMany(mappedBy: 'sklblOrder', targetEntity: SklblUploadConfig::class)]
    private Collection $sklblUploadConfigs;

    #[ORM\OneToMany(mappedBy: 'sklblOrder', targetEntity: SklblLisageConfig::class)]
    private Collection $sklblLisageConfigs;


    public function __construct()
    {
        $this->sklblOfs = new ArrayCollection();
        $this->sklblFiles = new ArrayCollection();
        $this->sklblSkus = new ArrayCollection();
        $this->sklbkFxs = new ArrayCollection();
        $this->sklblUploadConfigs = new ArrayCollection();
        $this->sklblLisageConfigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderNum(): ?string
    {
        return $this->order_num;
    }

    public function setOrderNum(string $order_num): static
    {
        $this->order_num = $order_num;

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

    public function getOrderQte(): ?string
    {
        return $this->order_qte;
    }

    public function setOrderQte(string $order_qte): static
    {
        $this->order_qte = $order_qte;

        return $this;
    }

    public function getOrderAt(): ?\DateTimeImmutable
    {
        return $this->order_at;
    }

    public function setOrderAt(\DateTimeImmutable $order_at): static
    {
        $this->order_at = $order_at;

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

    public function getSklblOrder(): ?self
    {
        return $this->sklblOrder;
    }

    public function setSklblOrder(?self $sklblOrder): static
    {
        $this->sklblOrder = $sklblOrder;

        return $this;
    }

    public function __toString()
    {
        return $this->order_num;
    }

    /**
     * @return Collection<int, SklblOf>
     */
   /* public function getSKlblOfs(): Collection
    {
        return $this->$sklblOfs;
    }

    public function addSKlblOfs(SklblOf $sklblOfs): static
    {
        if (!$this->$sklblOfs->contains($sklblOfs)) {
            $this->$sklblOfs->add($sklblOfs);
            $sklblOfs->setSklblOrder($this);
        }

        return $this;
    }

    public function removeSKlblOfs(SklblOf $sklblOfs): static
    {
        if ($this->$sklblOfs->removeElement($sklblOfs)) {
            // set the owning side to null (unless already changed)
            if ($sklblOfs->getSklblOrder() === $this) {
                $sklblOfs->setSklblOrder(null);
            }
        }

        return $this;
    }*/

    public function getSklblStatus(): ?int
    {
        return $this->sklblStatus;
    }

    public function setSklblStatus(int $sklblStatus): static
    {
        $this->sklblStatus = $sklblStatus;

        return $this;
    }

    /**
     * @return Collection<int, SklblFiles>
     */
    /*public function getSklblFiles(): Collection
    {
        return $this->sklblFiles;
    }

    public function addSklblFile(SklblFiles $sklblFile): static
    {
        if (!$this->sklblFiles->contains($sklblFile)) {
            $this->sklblFiles->add($sklblFile);
            $sklblFile->setSklblOrder($this);
        }

        return $this;
    }

    public function removeSklblFile(SklblFiles $sklblFile): static
    {
        if ($this->sklblFiles->removeElement($sklblFile)) {
            // set the owning side to null (unless already changed)
            if ($sklblFile->getSklblOrder() === $this) {
                $sklblFile->setSklblOrder(null);
            }
        }

        return $this;
    }*/

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
            $sklblSku->setSklblOrder($this);
        }

        return $this;
    }

    public function removeSklblSku(sklblSku $sklblSku): static
    {
        if ($this->sklblSkus->removeElement($sklblSku)) {
            // set the owning side to null (unless already changed)
            if ($sklblSku->getSklblOrder() === $this) {
                $sklblSku->setSklblOrder(null);
            }
        }

        return $this;
    }

    public function getQteLimit(): ?int
    {
        return $this->qteLimit;
    }

    public function setQteLimit(?int $qteLimit): static
    {
        $this->qteLimit = $qteLimit;

        return $this;
    }

    public function getPercentAboveLimit(): ?string
    {
        return $this->percentAboveLimit;
    }

    public function setPercentAboveLimit(?string $percentAboveLimit): static
    {
        $this->percentAboveLimit = $percentAboveLimit;

        return $this;
    }

    /**
     * @return Collection<int, SklblFx>
     */
    public function getSklbkFxs(): Collection
    {
        return $this->sklbkFxs;
    }

    public function addSklbkFx(SklblFx $sklbkFx): static
    {
        if (!$this->sklbkFxs->contains($sklbkFx)) {
            $this->sklbkFxs->add($sklbkFx);
            $sklbkFx->setSklblOrder($this);
        }

        return $this;
    }

    public function removeSklbkFx(SklblFx $sklbkFx): static
    {
        if ($this->sklbkFxs->removeElement($sklbkFx)) {
            // set the owning side to null (unless already changed)
            if ($sklbkFx->getSklblOrder() === $this) {
                $sklbkFx->setSklblOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SklblUploadConfig>
     */
    public function getSklblUploadConfigs(): Collection
    {
        return $this->sklblUploadConfigs;
    }

    public function addSklblUploadConfig(SklblUploadConfig $sklblUploadConfig): static
    {
        if (!$this->sklblUploadConfigs->contains($sklblUploadConfig)) {
            $this->sklblUploadConfigs->add($sklblUploadConfig);
            $sklblUploadConfig->setSklblOrder($this);
        }

        return $this;
    }

    public function removeSklblUploadConfig(SklblUploadConfig $sklblUploadConfig): static
    {
        if ($this->sklblUploadConfigs->removeElement($sklblUploadConfig)) {
            // set the owning side to null (unless already changed)
            if ($sklblUploadConfig->getSklblOrder() === $this) {
                $sklblUploadConfig->setSklblOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SklblSklblLisageConfig>
     */
    public function getSklblLisageConfigs(): Collection
    {
        return $this->sklblLisageConfigs;
    }

    public function addSklblSklblLisageConfig(SklblLisageConfig $sklblLisageConfig): static
    {
        if (!$this->sklblLisageConfigs->contains($sklblLisageConfig)) {
            $this->sklblLisageConfigs->add($sklblLisageConfig);
            $sklblLisageConfig->setSklblOrder($this);
        }

        return $this;
    }

    public function removeSklblSklblLisageConfig(SklblLisageConfig $sklblLisageConfig): static
    {
        if ($this->sklblLisageConfigs->removeElement($sklblLisageConfig)) {
            // set the owning side to null (unless already changed)
            if ($sklblLisageConfig->getSklblOrder() === $this) {
                $sklblLisageConfig->setSklblOrder(null);
            }
        }

        return $this;
    }

 
}
