<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblFx2Repository;
use Doctrine\DBAL\Types\BinaryType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use FontLib\BinaryStream;

#[ORM\Entity(repositoryClass: SklblFx2Repository::class)]
class SklblFx2
{
    #[ORM\Id]
    #[ORM\Column(type: "binary")]
    private $id = null;

    #[ORM\Column]
    private ?int $of_num = null;

    #[ORM\Column(length: 255)]
    private ?string $sku = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $skuTisse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uniqueId = null;

    #[ORM\Column(length: 255)]
    private ?string $redirectUrl = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deals_on = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $gen_scalabel_on = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFx2s')]
    private ?SklblFx $SklblFx = null;

    #[ORM\Column(length: 255)]
    private ?string $sklblFilename = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFx2s')]
    private ?SklblFiles $sklblFile = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFx2s')]
    private ?SklblOf $sklblOf = null;

    #[ORM\ManyToOne(inversedBy: 'sklblCustFx2s')]
    private ?SklblFiles $sklblCustfile = null;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getOfNum(): ?int
    {
        return $this->of_num;
    }

    public function setOfNum(int $of_num): static
    {
        $this->of_num = $of_num;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getSkuTisse(): ?string
    {
        return $this->skuTisse;
    }

    public function setSkuTisse(?string $skuTisse): static
    {
        $this->skuTisse = $skuTisse;

        return $this;
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(string $uniqueId): static
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;

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

    public function getDealsOn(): ?\DateTimeImmutable
    {
        return $this->deals_on;
    }

    public function setDealsOn(?\DateTimeImmutable $deals_on): static
    {
        $this->deals_on = $deals_on;

        return $this;
    }

    public function getGenScalabelOn(): ?\DateTimeImmutable
    {
        return $this->gen_scalabel_on;
    }

    public function setGenScalabelOn(\DateTimeImmutable $gen_scalabel_on): static
    {
        $this->gen_scalabel_on = $gen_scalabel_on;

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

    public function getSklblFx(): ?SklblFx
    {
        return $this->SklblFx;
    }

    public function setSklblFx(?SklblFx $SklblFx): static
    {
        $this->SklblFx = $SklblFx;

        return $this;
    }

    public function getSklblFilename(): ?string
    {
        return $this->sklblFilename;
    }

    public function setSklblFilename(string $sklblFilename): static
    {
        $this->sklblFilename = $sklblFilename;

        return $this;
    }

    public function getSklblFile(): ?SklblFiles
    {
        return $this->sklblFile;
    }

    public function setSklblFile(?SklblFiles $sklblFile): static
    {
        $this->sklblFile = $sklblFile;

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

    public function getSklblCustfile(): ?SklblFiles
    {
        return $this->sklblCustfile;
    }

    public function setSklblCustfile(?SklblFiles $sklblCustfile): static
    {
        $this->sklblCustfile = $sklblCustfile;

        return $this;
    }

    

}
