<?php

namespace App\Entity;

use App\Repository\QualityCtrlRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualityCtrlRepository::class)]
class QualityCtrl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'qualityCtrls')]
    private ?OrderSup $order_sup = null;

    #[ORM\ManyToOne(inversedBy: 'qualityCtrls')]
    private ?Ofs $ofs = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $checked_on = null;

    #[ORM\ManyToOne(inversedBy: 'qualityCtrls')]
    private ?Users $checked_by = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderSup(): ?OrderSup
    {
        return $this->order_sup;
    }

    public function setOrderSup(?OrderSup $order_sup): static
    {
        $this->order_sup = $order_sup;

        return $this;
    }

    public function getOfs(): ?Ofs
    {
        return $this->ofs;
    }

    public function setOfs(?Ofs $ofs): static
    {
        $this->ofs = $ofs;

        return $this;
    }

    public function getCheckedOn(): ?\DateTimeInterface
    {
        return $this->checked_on;
    }

    public function setCheckedOn(\DateTimeInterface $checked_on): static
    {
        $this->checked_on = $checked_on;

        return $this;
    }

    public function getCheckedBy(): ?Users
    {
        return $this->checked_by;
    }

    public function setCheckedBy(?Users $checked_by): static
    {
        $this->checked_by = $checked_by;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
