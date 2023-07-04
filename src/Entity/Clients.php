<?php

namespace App\Entity;

use App\Entity\Ofs;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\Column(length: 20)]
    private ?string $code = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 3)]
    private ?string $country = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Ofs::class)]
    private Collection $ofs;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: SklblOf::class)]
    private Collection $sklblOf;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: SklblOrders::class, orphanRemoval: true)]
    private Collection $sklblOrders;

    public function __construct()
    {
        $this->ofs = new ArrayCollection();
        $this->sklblOf = new ArrayCollection();
        $this->sklblOrders = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

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
            $of->setClient($this);
        }

        return $this;
    }

    public function removeOf(Ofs $of): static
    {
        if ($this->ofs->removeElement($of)) {
            // set the owning side to null (unless already changed)
            if ($of->getClient() === $this) {
                $of->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OfsSklbl>
     */
    public function getSklblOf(): Collection
    {
        return $this->sklblOf;
    }

    public function addSklblOf(SklblOf $sklblOf): static
    {
        if (!$this->sklblOf->contains($sklblOf)) {
            $this->sklblOf->add($sklblOf);
            $sklblOf->setClient($this);
        }

        return $this;
    }

    public function removeSklblOf(SklblOf $sklblOf): static
    {
        if ($this->sklblOf->removeElement($sklblOf)) {
            // set the owning side to null (unless already changed)
            if ($sklblOf->getClient() === $this) {
                $sklblOf->setClient(null);
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
            $sklblOrder->setClient($this);
        }

        return $this;
    }

    public function removeSklblOrder(SklblOrders $sklblOrder): static
    {
        if ($this->sklblOrders->removeElement($sklblOrder)) {
            // set the owning side to null (unless already changed)
            if ($sklblOrder->getClient() === $this) {
                $sklblOrder->setClient(null);
            }
        }

        return $this;
    }
}
