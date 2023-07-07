<?php

namespace App\Entity\Sklbl;

use App\Entity\Articles;
use App\Repository\Sklbl\SklblFxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblFxRepository::class)]
class SklblFx
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFxs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SklblOf $sklblOf = null;


    #[ORM\ManyToOne(inversedBy: 'sklblFxs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?sklblSku $sklblSku = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFxs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SklblFiles $sklblFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uniqueId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $redirectUrl = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $sent_on = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $received_on = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'sklbkFxs')]
    private ?SklblOrders $sklblOrder = null;

    

    


    public function getId(): ?int
    {
        return $this->id;
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

    public function getSklblSku(): ?sklblSku
    {
        return $this->sklblSku;
    }

    public function setSklblSku(?sklblSku $sklblSku): static
    {
        $this->sklblSku = $sklblSku;

        return $this;
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(?string $uniqueId): static
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(?string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;

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

    public function getSentOn(): ?\DateTimeImmutable
    {
        return $this->sent_on;
    }

    public function setSentOn(?\DateTimeImmutable $sent_on): static
    {
        $this->sent_on = $sent_on;

        return $this;
    }

    public function getReceivedOn(): ?\DateTimeImmutable
    {
        return $this->received_on;
    }

    public function setReceivedOn(?\DateTimeImmutable $received_on): static
    {
        $this->received_on = $received_on;

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

    public function getSklblOrder(): ?SklblOrders
    {
        return $this->sklblOrder;
    }

    public function setSklblOrder(?SklblOrders $sklblOrder): static
    {
        $this->sklblOrder = $sklblOrder;

        return $this;
    }


}
