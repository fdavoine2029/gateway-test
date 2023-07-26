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


    #[ORM\OneToMany(mappedBy: 'sklblFile', targetEntity: SklblFx::class)]
    private Collection $sklblFxs;

    #[ORM\OneToMany(mappedBy: 'sklblFile', targetEntity: sklblSku::class)]
    private Collection $sklblSkus;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\OneToMany(mappedBy: 'sklblFile', targetEntity: SklblFx2::class)]
    private Collection $sklblFx2s;

    #[ORM\OneToMany(mappedBy: 'sklblCustfileId', targetEntity: SklblFx2::class)]
    private Collection $sklblCustFx2s;

    #[ORM\Column]
    private ?int $ligne = null;

    public function __construct()
    {
        $this->sklblFxs = new ArrayCollection();
        $this->sklblSkus = new ArrayCollection();
        $this->sklblFx2s = new ArrayCollection();
        $this->sklblCustFx2s = new ArrayCollection();
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

    public function getLigne(): ?int
    {
        return $this->ligne;
    }

    public function setLigne(int $ligne): static
    {
        $this->ligne = $ligne;

        return $this;
    }

    /**
     * @return Collection<string, SklblFx2>
     */
    public function getSklblFx2s(): Collection
    {
        return $this->sklblFx2s;
    }

    public function addSklblFx2(SklblFx2 $sklblFx2): static
    {
        if (!$this->sklblFx2s->contains($sklblFx2)) {
            $this->sklblFx2s->add($sklblFx2);
            $sklblFx2->setSklblFile($this);
        }

        return $this;
    }

    public function removeSklblFx2(SklblFx2 $sklblFx2): static
    {
        if ($this->sklblFx2s->removeElement($sklblFx2)) {
            // set the owning side to null (unless already changed)
            if ($sklblFx2->getSklblFile() === $this) {
                $sklblFx2->setSklblFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SklblFx2>
     */
    public function getSklblCustFx2s(): Collection
    {
        return $this->sklblCustFx2s;
    }

    public function addSklblCustFx2(SklblFx2 $sklblCustFx2): static
    {
        if (!$this->sklblCustFx2s->contains($sklblCustFx2)) {
            $this->sklblCustFx2s->add($sklblCustFx2);
            $sklblCustFx2->setSklblCustfile($this);
        }

        return $this;
    }

    public function removeSklblCustFx2(SklblFx2 $sklblCustFx2): static
    {
        if ($this->sklblCustFx2s->removeElement($sklblCustFx2)) {
            // set the owning side to null (unless already changed)
            if ($sklblCustFx2->getSklblCustfile() === $this) {
                $sklblCustFx2->setSklblCustfile(null);
            }
        }

        return $this;
    }


}
