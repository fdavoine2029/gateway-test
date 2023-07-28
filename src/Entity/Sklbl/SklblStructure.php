<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblStructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblStructureRepository::class)]
class SklblStructure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'sklblStructure', targetEntity: SklblLisageConfig::class)]
    private Collection $sklblLisageConfigs;

    #[ORM\OneToMany(mappedBy: 'sklblStructure', targetEntity: SklblUploadConfig::class)]
    private Collection $sklblUploadConfigs;

    public function __construct()
    {
        $this->sklblLisageConfigs = new ArrayCollection();
        $this->sklblUploadConfigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, SklblLisageConfig>
     */
    public function getSklblLisageConfigs(): Collection
    {
        return $this->sklblLisageConfigs;
    }

    public function addSklblLisageConfig(SklblLisageConfig $sklblLisageConfig): static
    {
        if (!$this->sklblLisageConfigs->contains($sklblLisageConfig)) {
            $this->sklblLisageConfigs->add($sklblLisageConfig);
            $sklblLisageConfig->setSklblStructure($this);
        }

        return $this;
    }

    public function removeSklblLisageConfig(SklblLisageConfig $sklblLisageConfig): static
    {
        if ($this->sklblLisageConfigs->removeElement($sklblLisageConfig)) {
            // set the owning side to null (unless already changed)
            if ($sklblLisageConfig->getSklblStructure() === $this) {
                $sklblLisageConfig->setSklblStructure(null);
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
            $sklblUploadConfig->setSklblStructure($this);
        }

        return $this;
    }

    public function removeSklblUploadConfig(SklblUploadConfig $sklblUploadConfig): static
    {
        if ($this->sklblUploadConfigs->removeElement($sklblUploadConfig)) {
            // set the owning side to null (unless already changed)
            if ($sklblUploadConfig->getSklblStructure() === $this) {
                $sklblUploadConfig->setSklblStructure(null);
            }
        }

        return $this;
    }
}
