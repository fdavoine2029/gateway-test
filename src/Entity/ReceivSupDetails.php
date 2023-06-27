<?php

namespace App\Entity;

use App\Repository\ReceivSupDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceivSupDetailsRepository::class)]
class ReceivSupDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $qteRecue = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $batch_num = null;

    #[ORM\Column(length: 10)]
    private ?string $numBlFou = null;

    #[ORM\ManyToOne(inversedBy: 'receivSupDetails')]
    private ?OrderSup $orderSup = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQteRecue(): ?string
    {
        return $this->qteRecue;
    }

    public function setQteRecue(string $qteRecue): static
    {
        $this->qteRecue = $qteRecue;

        return $this;
    }


    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

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

    public function getBatchNum(): ?string
    {
        return $this->batch_num;
    }

    public function setBatchNum(string $batch_num): static
    {
        $this->batch_num = $batch_num;

        return $this;
    }

    public function getNumBlFou(): ?string
    {
        return $this->numBlFou;
    }

    public function setNumBlFou(string $numBlFou): static
    {
        $this->numBlFou = $numBlFou;

        return $this;
    }

    public function getOrderSup(): ?OrderSup
    {
        return $this->orderSup;
    }

    public function setOrderSup(?OrderSup $orderSup): static
    {
        $this->orderSup = $orderSup;

        return $this;
    }
}
