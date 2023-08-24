<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblModelRepository::class)]
class SklblModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'SklblModel', targetEntity: SklblUploadConfig::class)]
    private Collection $sklblUploadConfig;


    public function __construct()
    {
        $this->sklblUploadConfig = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, SklblUploadConfig>
     */
    public function getSklblUploadConfig(): Collection
    {
        return $this->sklblUploadConfig;
    }

    public function addSklblUploadConfig(SklblUploadConfig $sklblUploadConfig): static
    {
        if (!$this->sklblUploadConfig->contains($sklblUploadConfig)) {
            $this->sklblUploadConfig->add($sklblUploadConfig);
            $sklblUploadConfig->setSklblModel($this);
        }

        return $this;
    }

    public function removeSklblUploadConfig(SklblUploadConfig $sklblUploadConfig): static
    {
        if ($this->sklblUploadConfig->removeElement($sklblUploadConfig)) {
            // set the owning side to null (unless already changed)
            if ($sklblUploadConfig->getSklblModel() === $this) {
                $sklblUploadConfig->setSklblModel(null);
            }
        }

        return $this;
    }

 
}
