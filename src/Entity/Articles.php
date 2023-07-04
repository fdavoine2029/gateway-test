<?php

namespace App\Entity;

use App\Entity\Ofs;
use App\Entity\Sklbl\SklblEmballage;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\Column(length: 25)]
    private ?string $ref = null;

    #[ORM\Column(length: 80)]
    private ?string $designation = null;

    #[ORM\Column(length: 1)]
    private ?string $abccod = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?int $lot = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: SklblEmballage::class, orphanRemoval: true)]
    private Collection $emballages;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Ofs::class, orphanRemoval: true)]
    private Collection $ofs;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: SklblOf::class)]
    private Collection $ofsSklbls;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: SklblOrders::class, orphanRemoval: true)]
    private Collection $sklblOrders;


    public function __construct()
    {
        $this->emballages = new ArrayCollection();
        $this->ofs = new ArrayCollection();
        $this->ofsSklbls = new ArrayCollection();
        $this->sklblOrders = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

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

    public function getAbccod(): ?string
    {
        return $this->abccod;
    }

    public function setAbccod(string $abccod): self
    {
        $this->abccod = $abccod;

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

    public function getLot(): ?int
    {
        return $this->lot;
    }

    public function setLot(int $lot): static
    {
        $this->lot = $lot;

        return $this;
    }

    /**
     * @return Collection<int, Emballages>
     */
    public function getSklblEmballage(): Collection
    {
        return $this->emballages;
    }

    public function addSklblEmballage(SklblEmballage $emballage): static
    {
        if (!$this->emballages->contains($emballage)) {
            $this->emballages->add($emballage);
            $emballage->setArticle($this);
        }

        return $this;
    }

    public function removeSklblEmballage(SklblEmballage $emballage): static
    {
        if ($this->emballages->removeElement($emballage)) {
            // set the owning side to null (unless already changed)
            if ($emballage->getArticle() === $this) {
                $emballage->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ofs>
     */
    public function getOfs(): Collection
    {
        return $this->ofs;
    }

    public function addOf(Ofs $of): static
    {
        if (!$this->ofs->contains($of)) {
            $this->ofs->add($of);
            $of->setArticle($this);
        }

        return $this;
    }

    public function removeOf(Ofs $of): static
    {
        if ($this->ofs->removeElement($of)) {
            // set the owning side to null (unless already changed)
            if ($of->getArticle() === $this) {
                $of->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OfsSklbl>
     */
    public function getSklblOf(): Collection
    {
        return $this->ofsSklbls;
    }

    public function addSklblOf(SklblOf $sklblOf): static
    {
        if (!$this->ofsSklbls->contains($sklblOf)) {
            $this->ofsSklbls->add($sklblOf);
            $sklblOf->setArticle($this);
        }

        return $this;
    }

    public function removeSklblOf(SklblOf $sklblOf): static
    {
        if ($this->ofsSklbls->removeElement($sklblOf)) {
            // set the owning side to null (unless already changed)
            if ($sklblOf->getArticle() === $this) {
                $sklblOf->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SklblOrders>
     */
    public function getSklblOrders(): Collection
    {
        return $this->sklblOrders;
    }

    public function addSklblOrder(SklblOrders $sklblOrder): static
    {
        if (!$this->sklblOrders->contains($sklblOrder)) {
            $this->sklblOrders->add($sklblOrder);
            $sklblOrder->setArticle($this);
        }

        return $this;
    }

    public function removeSklblOrder(SklblOrders $sklblOrder): static
    {
        if ($this->sklblOrders->removeElement($sklblOrder)) {
            // set the owning side to null (unless already changed)
            if ($sklblOrder->getArticle() === $this) {
                $sklblOrder->setArticle(null);
            }
        }

        return $this;
    }

}
