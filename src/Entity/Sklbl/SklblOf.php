<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblOfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Articles;
use App\Entity\Clients;


#[ORM\Entity(repositoryClass: SklblOfRepository::class)]
class SklblOf
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $code = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $refCli = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $orderQte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $launchedQte = null;

    #[ORM\ManyToOne(inversedBy: 'ofsSklbls')]
    private ?Articles $article = null;

    #[ORM\ManyToOne(inversedBy: 'ofsSklbls')]
    private ?Clients $client = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref1 = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $sref2 = null;


    #[ORM\Column]
    private ?int $sync = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne]
    private ?SklblEmballage $emballage1 = null;

    #[ORM\ManyToOne]
    private ?SklblEmballage $emballage2 = null;

    #[ORM\ManyToOne]
    private ?SklblEmballage $emballage3 = null;

    #[ORM\ManyToOne]
    private ?SklblEmballage $emballage4 = null;

    #[ORM\ManyToOne]
    private ?SklblRubrique $fichier1 = null;

    #[ORM\ManyToOne]
    private ?SklblRubrique $fichier2 = null;

    #[ORM\ManyToOne]
    private ?SklblRubrique $miniComplet = null;

    #[ORM\ManyToOne]
    private ?SklblRubrique $masque = null;

    #[ORM\ManyToOne]
    private ?SklblRubrique $fichierRetour = null;

    #[ORM\ManyToOne]
    private ?SklblRubrique $options = null;

    #[ORM\Column]
    private ?int $ofStatus = null;

    #[ORM\Column]
    private ?int $sklblStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $planned_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $start_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $end_at = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $orderNum = null;

    #[ORM\ManyToOne(inversedBy: 'sKlblOfs2')]
    private ?SklblOrders $sklblOrder = null;

    #[ORM\OneToMany(mappedBy: 'sklblOf', targetEntity: SklblFiles::class)]
    private Collection $sklblFiles;

    #[ORM\OneToMany(mappedBy: 'sklblOf', targetEntity: SklblFx::class, orphanRemoval: true)]
    private Collection $sklblFxs;

    public function __construct()
    {
        $this->sklblFiles = new ArrayCollection();
        $this->sklblFxs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getRefCli(): ?string
    {
        return $this->refCli;
    }

    public function setRefCli(string $refCli): static
    {
        $this->refCli = $refCli;

        return $this;
    }

    public function getOrderQte(): ?string
    {
        return $this->orderQte;
    }

    public function setOrderQte(string $orderQte): static
    {
        $this->orderQte = $orderQte;

        return $this;
    }

    public function getLaunchedQte(): ?string
    {
        return $this->launchedQte;
    }

    public function setLaunchedQte(string $launchedQte): static
    {
        $this->launchedQte = $launchedQte;

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

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

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


    public function getSync(): ?int
    {
        return $this->sync;
    }

    public function setSync(int $sync): static
    {
        $this->sync = $sync;

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

    public function getEmballage1(): ?SklblEmballage
    {
        return $this->emballage1;
    }

    public function setEmballage1(?SklblEmballage $emballage1): static
    {
        $this->emballage1 = $emballage1;

        return $this;
    }

    public function getEmballage2(): ?SklblEmballage
    {
        return $this->emballage2;
    }

    public function setEmballage2(?SklblEmballage $emballage2): static
    {
        $this->emballage2 = $emballage2;

        return $this;
    }

    public function getEmballage3(): ?SklblEmballage
    {
        return $this->emballage3;
    }

    public function setEmballage3(?SklblEmballage $emballage3): static
    {
        $this->emballage3 = $emballage3;

        return $this;
    }

    public function getEmballage4(): ?SklblEmballage
    {
        return $this->emballage4;
    }

    public function setEmballage4(?SklblEmballage $emballage4): static
    {
        $this->emballage4 = $emballage4;

        return $this;
    }

    public function getFichier1(): ?SklblRubrique
    {
        return $this->fichier1;
    }

    public function setFichier1(?SklblRubrique $fichier1): static
    {
        $this->fichier1 = $fichier1;

        return $this;
    }

    public function getFichier2(): ?SklblRubrique
    {
        return $this->fichier2;
    }

    public function setFichier2(?SklblRubrique $fichier2): static
    {
        $this->fichier2 = $fichier2;

        return $this;
    }

    public function getMiniComplet(): ?SklblRubrique
    {
        return $this->miniComplet;
    }

    public function setMiniComplet(?SklblRubrique $miniComplet): static
    {
        $this->miniComplet = $miniComplet;

        return $this;
    }

    public function getMasque(): ?SklblRubrique
    {
        return $this->masque;
    }

    public function setMasque(?SklblRubrique $masque): static
    {
        $this->masque = $masque;

        return $this;
    }

    public function getFichierRetour(): ?SklblRubrique
    {
        return $this->fichierRetour;
    }

    public function setFichierRetour(?SklblRubrique $fichierRetour): static
    {
        $this->fichierRetour = $fichierRetour;

        return $this;
    }

    public function getOptions(): ?SklblRubrique
    {
        return $this->options;
    }

    public function setOptions(?SklblRubrique $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getOfStatus(): ?int
    {
        return $this->ofStatus;
    }

    public function setOfStatus(int $ofStatus): static
    {
        $this->ofStatus = $ofStatus;

        return $this;
    }

    public function getSklblStatus(): ?int
    {
        return $this->sklblStatus;
    }

    public function setSklblStatus(int $sklblStatus): static
    {
        $this->sklblStatus = $sklblStatus;

        return $this;
    }

    public function getPlannedAt(): ?\DateTimeImmutable
    {
        return $this->planned_at;
    }

    public function setPlannedAt(\DateTimeImmutable $planned_at): static
    {
        $this->planned_at = $planned_at;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeImmutable $start_at): static
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

    public function getOrderNum(): ?string
    {
        return $this->orderNum;
    }

    public function setOrderNum(string $orderNum): static
    {
        $this->orderNum = $orderNum;

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
     * @return Collection<int, SklblFiles>
     */
   /* public function getSklblFiles(): Collection
    {
        return $this->sklblFiles;
    }

    public function addSklblFile(SklblFiles $sklblFile): static
    {
        if (!$this->sklblFiles->contains($sklblFile)) {
            $this->sklblFiles->add($sklblFile);
            $sklblFile->setSklblOf($this);
        }

        return $this;
    }

    public function removeSklblFile(SklblFiles $sklblFile): static
    {
        if ($this->sklblFiles->removeElement($sklblFile)) {
            // set the owning side to null (unless already changed)
            if ($sklblFile->getSklblOf() === $this) {
                $sklblFile->setSklblOf(null);
            }
        }

        return $this;
    }*/

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
           $sklblFx->setSklblOf($this);
       }

       return $this;
   }

   public function removeSklblFx(SklblFx $sklblFx): static
   {
       if ($this->sklblFxs->removeElement($sklblFx)) {
           // set the owning side to null (unless already changed)
           if ($sklblFx->getSklblOf() === $this) {
               $sklblFx->setSklblOf(null);
           }
       }

       return $this;
   }

}
