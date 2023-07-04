<?php

namespace App\Entity\Sklbl;

use App\Entity\Articles;
use App\Repository\Sklbl\SklblEmballageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblEmballageRepository::class)]
class SklblEmballage
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'emballages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Articles $article = null;

    #[ORM\Column(length: 8)]
    private ?string $dossier = null;

    #[ORM\Column(length: 4)]
    private ?string $code = null;

    #[ORM\Column(length: 40)]
    private ?string $libelle = null;

    #[ORM\Column(length: 4)]
    private ?string $unite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $qte = null;

    #[ORM\Column]
    private ?int $ordre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(string $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }
}
